<?php

namespace App\Http\Controllers;

use App\InventoryBalance;
use App\Material;
use App\Item;
use App\Entry;
use App\DetailEntry;
use App\Output;
use App\OutputDetail;
use App\Services\TipoCambioService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryBalanceExport;

class InventoryBalanceController extends Controller
{
    protected $tipoCambioService;

    public function __construct(TipoCambioService $tipoCambioService)
    {
        $this->tipoCambioService = $tipoCambioService;
    }

    public function runO()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        DB::beginTransaction();

        try {
            // 1) Crear sesión de cuadre
            $balance = InventoryBalance::create([
                'executed_at'    => now(),
                'user_id'        => $user->id,
                'total_materials'=> 0,
                'total_entries'  => 0,
                'total_outputs'  => 0,
            ]);

            $rows = []; // para el Excel
            $totalEntries = 0;
            $totalOutputs = 0;
            $totalMaterials = 0;

            // Tipo de cambio del día
            $tipoCambio = $this->tipoCambioService->obtenerPorFecha($today);

            //$materials = Material::all(); // aquí puedes filtrar por estado, categoría, etc.
            $materials = Material::whereIn('code', ['P-00012','P-00018','P-00024', 'P-00047'])->get();

            foreach ($materials as $material) {
                $totalMaterials++;

                // --- 1. stocks base ---
                $stockSystem   = $material->stock_current ?? 0;
                $stockPhysical = $material->inventory ?? 0;

                // Si es retacería, solo usamos parte entera
                if (!is_null($material->typescrap_id)) {
                    $stockSystem   = (int) floor($stockSystem);
                    $stockPhysical = (int) floor($stockPhysical);
                }

                $diff = $stockPhysical - $stockSystem;
                $action = 'Nada';
                $qty = 0;

                // Para armar lista de ubicaciones después de actualizar
                // primero ejecutamos la corrección
                if ($diff > 0) {
                    // ------------------ INGRESO POR INVENTARIO ------------------
                    $qty = $diff;

                    // Crear ingreso
                    $entry = Entry::create([
                        'referral_guide'   => null,
                        'purchase_order'   => null,
                        'invoice'          => null,
                        'deferred_invoice' => 'off',
                        'supplier_id'      => null,
                        'entry_type'       => 'Inventario',
                        'date_entry'       => now(),
                        'finance'          => false,
                        'currency_invoice' => 'USD',
                        'currency_compra'  => (float) $tipoCambio->precioCompra,
                        'currency_venta'   => (float) $tipoCambio->precioVenta,
                        'observation'      => 'INGRESO DE MATERIAL '.$material->full_name.' POR INVENTARIO',
                    ]);

                    // Actualizar stock del material
                    $material->stock_current = $material->stock_current + $qty;
                    $material->save();

                    $detailEntry = DetailEntry::create([
                        'entry_id'         => $entry->id,
                        'material_id'      => $material->id,
                        'ordered_quantity' => $qty,
                        'entered_quantity' => $qty,
                    ]);

                    // Localización del último item ingresado de ese material
                    $lastItem = Item::where('material_id', $material->id)
                        ->where('state_item', 'entered')
                        ->latest('id')
                        ->first();

                    /*$locationId = $lastItem ? $lastItem->location_id : null;*/
                    $locationId = 1; // valor por defecto

                    if ($lastItem && $lastItem->location_id) {
                        $locationId = $lastItem->location_id;
                    }

                    $price = (float) $material->unit_price;

                    // Crear N items
                    for ($i = 0; $i < $qty; $i++) {
                        $baseItem = [
                            'detail_entry_id' => $detailEntry->id,
                            'material_id'     => $material->id,
                            'code'            => 'INV-'.$material->code.'-'.($i+1), // TODO: ajustar a tu patrón
                            'weight'          => 0,
                            'price'           => $price,
                            'percentage'      => 1,
                            'location_id'     => $locationId,
                            'state'           => 'good', // o lo que uses
                            'state_item'      => 'entered',
                        ];

                        if ($material->typescrap_id) {
                            $baseItem = array_merge($baseItem, [
                                'length'      => (float) $material->typeScrap->length,
                                'width'       => (float) $material->typeScrap->width,
                                'typescrap_id'=> $material->typescrap_id,
                            ]);
                        } else {
                            $baseItem = array_merge($baseItem, [
                                'length' => 0,
                                'width'  => 0,
                            ]);
                        }

                        Item::create($baseItem);
                    }

                    $totalEntries++;
                    $action = "Se realizó un ingreso de {$qty} unidades";

                } elseif ($diff < 0) {
                    // ------------------ SALIDA POR INVENTARIO ------------------
                    $qty = abs($diff);

                    // Crear salida
                    $output = Output::create([
                        'execution_order' => 'SALIDA DE MATERIAL '.$material->full_name.' POR INVENTARIO',
                        'request_date'    => today(),
                        'requesting_user' => $user->id,
                        'responsible_user'=> $user->id,
                        'state'           => 'created',
                        'indicator'       => 'or',
                    ]);

                    // Items que salen (los primeros "entered")
                    $itemsToOutput = Item::where('material_id', $material->id)
                        ->where('state_item', 'entered')
                        ->orderBy('id')
                        ->limit($qty)
                        ->get();

                    $countItems = $itemsToOutput->count();

                    if ($countItems < $qty) {
                        // no hay suficientes items, ajustamos a lo que hay
                        $qty = $countItems;
                    }

                    foreach ($itemsToOutput as $item) {
                        // cambiar estado del item
                        $item->state_item = 'reserved'; // o el estado que corresponda a salida por inventario
                        $item->save();

                        OutputDetail::create([
                            'output_id'    => $output->id,
                            'item_id'      => $item->id,
                            'length'       => $item->length,
                            'width'        => $item->width,
                            'price'        => $item->price,
                            'percentage'   => $item->percentage,
                            'material_id'  => $material->id,
                            'equipment_id' => null,
                            'quote_id'     => null,
                            'custom'       => 0,
                        ]);
                    }

                    // Actualizar stock del material
                    $material->stock_current = $material->stock_current - $qty;
                    $material->save();

                    $totalOutputs++;
                    $action = "Se realizó una salida de {$qty} unidades";
                }

                // --- ubicaciones (después del ajuste) ---
                /*$locations = Item::where('material_id', $material->id)
                    ->where('state_item', 'entered')
                    ->pluck('location_id')
                    ->unique()
                    ->filter()
                    ->implode(', ');*/
                $locations = Item::where('material_id', $material->id)
                    ->where('state_item', 'entered')
                    ->whereNotNull('location_id')
                    ->with('location') // para evitar N+1
                    ->get()
                    ->pluck('location.full_location')
                    ->unique()
                    ->implode(' | ');

                // Agregar fila al resumen (para el Excel)
                $rows[] = [
                    'codigo'             => $material->code,
                    'material'           => $material->description,
                    'stock_sistema'      => $material->stock_current,
                    'stock_fisico'       => $material->inventory,
                    'ubicaciones'        => $locations,
                    'accion'             => $action,
                ];
            }

            // Actualizar totales en la sesión de cuadre
            $balance->update([
                'total_materials' => $totalMaterials,
                'total_entries'   => $totalEntries,
                'total_outputs'   => $totalOutputs,
            ]);

            DB::commit();

            // Al final, en vez de Excel::download(...)
            $fileName = 'cuadre_inventario_'.$balance->id.'.xlsx';
            $filePath = 'inventory_balances/'.$fileName;

            // guarda en disco 'public'
            Excel::store(new InventoryBalanceExport($rows), $filePath, 'public');

            $balance->update(['excel_path' => $filePath]);

            DB::commit();

            return response()->json([
                'ok'           => true,
                'message'      => 'Cuadre realizado con éxito',
                'download_url' => route('inventory.balance.download', $balance->id),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'ok'      => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function run()
    {
        $user  = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        DB::beginTransaction();

        try {
            // 1) Crear sesión de cuadre
            $balance = InventoryBalance::create([
                'executed_at'     => now(),
                'user_id'         => $user->id,
                'total_materials' => 0,
                'total_entries'   => 0,
                'total_outputs'   => 0,
            ]);

            $rows           = []; // para el Excel
            $totalEntries   = 0;
            $totalOutputs   = 0;
            $totalMaterials = 0;

            // Tipo de cambio del día
            $tipoCambio = $this->tipoCambioService->obtenerPorFecha($today);

            // Para pruebas
            //$materials = Material::whereIn('code', ['P-00012','P-00018','P-00024', 'P-00047'])->get();
            $materials = Material::whereNotNull('inventory')->get();

            foreach ($materials as $material) {
                $totalMaterials++;

                // Stock en sistema
                $stockSystem = $material->stock_current ?? 0;

                /**
                 * Si inventory es NULL → este material NO fue contado.
                 * No hacemos ingreso/salida, solo mostramos en el Excel.
                 */
                if (is_null($material->inventory)) {

                    // Ubicaciones actuales (items en estado entered)
                    $locations = Item::where('material_id', $material->id)
                        ->where('state_item', 'entered')
                        ->whereNotNull('location_id')
                        ->with('location')
                        ->get()
                        ->pluck('location.full_location')
                        ->unique()
                        ->implode(' | ');

                    $rows[] = [
                        'codigo'        => $material->code,
                        'material'      => $material->description,
                        'stock_sistema' => $stockSystem,
                        'stock_fisico'  => null, // SIN DATO
                        'ubicaciones'   => $locations,
                        'accion'        => 'No se realizó ajuste (sin stock físico)',
                    ];

                    continue; // seguimos con el siguiente material
                }

                // Stock físico REAL (incluye 0 como valor válido)
                $stockPhysical = $material->inventory;

                // Si es retacería, usar solo parte entera
                if (!is_null($material->typescrap_id)) {
                    $stockSystem   = (int) floor($stockSystem);
                    $stockPhysical = (int) floor($stockPhysical);
                }

                $diff   = $stockPhysical - $stockSystem;
                $action = 'Nada';
                $qty    = 0;

                // ------------------ INGRESO POR INVENTARIO ------------------
                if ($diff > 0) {
                    $qty = $diff;

                    // Crear ingreso
                    $entry = Entry::create([
                        'referral_guide'   => null,
                        'purchase_order'   => null,
                        'invoice'          => null,
                        'deferred_invoice' => 'off',
                        'supplier_id'      => null,
                        'entry_type'       => 'Inventario',
                        'date_entry'       => now(),
                        'finance'          => false,
                        'currency_invoice' => 'USD',
                        'currency_compra'  => (float) $tipoCambio->precioCompra,
                        'currency_venta'   => (float) $tipoCambio->precioVenta,
                        'observation'      => 'INGRESO DE MATERIAL '.$material->full_name.' POR INVENTARIO',
                    ]);

                    // Actualizar stock del material
                    $material->stock_current = $material->stock_current + $qty;
                    $material->save();

                    $detailEntry = DetailEntry::create([
                        'entry_id'         => $entry->id,
                        'material_id'      => $material->id,
                        'ordered_quantity' => $qty,
                        'entered_quantity' => $qty,
                    ]);

                    // Localización del último item ingresado de ese material
                    $lastItem = Item::where('material_id', $material->id)
                        ->where('state_item', 'entered')
                        ->latest('id')
                        ->first();

                    $locationId = 1; // valor por defecto
                    if ($lastItem && $lastItem->location_id) {
                        $locationId = $lastItem->location_id;
                    }

                    $price = (float) $material->unit_price;

                    // Crear N items
                    for ($i = 0; $i < $qty; $i++) {
                        $baseItem = [
                            'detail_entry_id' => $detailEntry->id,
                            'material_id'     => $material->id,
                            'code'            => 'INV-'.$material->code.'-'.($i + 1),
                            'weight'          => 0,
                            'price'           => $price,
                            'percentage'      => 1,
                            'location_id'     => $locationId,
                            'state'           => 'good',
                            'state_item'      => 'entered',
                        ];

                        if ($material->typescrap_id) {
                            $baseItem = array_merge($baseItem, [
                                'length'       => (float) $material->typeScrap->length,
                                'width'        => (float) $material->typeScrap->width,
                                'typescrap_id' => $material->typescrap_id,
                            ]);
                        } else {
                            $baseItem = array_merge($baseItem, [
                                'length' => 0,
                                'width'  => 0,
                            ]);
                        }

                        Item::create($baseItem);
                    }

                    $totalEntries++;
                    $action = "Se realizó un ingreso de {$qty} unidades";

                    // ------------------ SALIDA POR INVENTARIO ------------------
                } elseif ($diff < 0) {
                    $qty = abs($diff);

                    // Crear salida
                    $output = Output::create([
                        'execution_order' => 'SALIDA DE MATERIAL '.$material->full_name.' POR INVENTARIO',
                        'request_date'    => today(),
                        'requesting_user' => $user->id,
                        'responsible_user'=> $user->id,
                        'state'           => 'created',
                        'indicator'       => 'or',
                    ]);

                    // Items que salen (los primeros "entered")
                    $itemsToOutput = Item::where('material_id', $material->id)
                        ->where('state_item', 'entered')
                        ->orderBy('id')
                        ->limit($qty)
                        ->get();

                    $countItems = $itemsToOutput->count();
                    if ($countItems < $qty) {
                        $qty = $countItems;
                    }

                    foreach ($itemsToOutput as $item) {
                        $item->state_item = 'reserved'; // o el estado que corresponda
                        $item->save();

                        OutputDetail::create([
                            'output_id'    => $output->id,
                            'item_id'      => $item->id,
                            'length'       => $item->length,
                            'width'        => $item->width,
                            'price'        => $item->price,
                            'percentage'   => $item->percentage,
                            'material_id'  => $material->id,
                            'equipment_id' => null,
                            'quote_id'     => null,
                            'custom'       => 0,
                        ]);
                    }

                    // Actualizar stock del material
                    $material->stock_current = $material->stock_current - $qty;
                    $material->save();

                    $totalOutputs++;
                    $action = "Se realizó una salida de {$qty} unidades";
                }

                // --- ubicaciones (después del ajuste) ---
                $locations = Item::where('material_id', $material->id)
                    ->where('state_item', 'entered')
                    ->whereNotNull('location_id')
                    ->with('location')
                    ->get()
                    ->pluck('location.full_location')
                    ->unique()
                    ->implode(' | ');

                // Agregar fila al resumen (para el Excel)
                $rows[] = [
                    'codigo'        => $material->code,
                    'material'      => $material->description,
                    'stock_sistema' => $material->stock_current,
                    'stock_fisico'  => $material->inventory,
                    'ubicaciones'   => $locations,
                    'accion'        => $action,
                ];
            }

            // Actualizar totales en la sesión de cuadre
            $balance->update([
                'total_materials' => $totalMaterials,
                'total_entries'   => $totalEntries,
                'total_outputs'   => $totalOutputs,
            ]);

            // Generar y guardar Excel
            $fileName = 'cuadre_inventario_'.$balance->id.'.xlsx';
            $filePath = 'inventory_balances/'.$fileName;

            Excel::store(new InventoryBalanceExport($rows), $filePath, 'public');

            $balance->update(['excel_path' => $filePath]);

            DB::commit();

            return response()->json([
                'ok'           => true,
                'message'      => 'Cuadre realizado con éxito',
                'download_url' => route('inventory.balance.download', $balance->id),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'ok'      => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function download($id)
    {
        $balance = InventoryBalance::findOrFail($id);

        if (!$balance->excel_path) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $balance->excel_path,
            'cuadre_inventario_'.$balance->id.'.xlsx'
        );
    }
}
