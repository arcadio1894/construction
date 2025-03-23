<?php

namespace App\Console\Commands;

use App\CheckStock;
use App\DetailEntry;
use App\Item;
use App\Material;
use App\OutputDetail;
use App\Mail\DesfaseStockMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckStockCommand extends Command
{
    protected $signature = 'check:stock';
    protected $description = 'Verifica el stock de materiales activos y guarda en la tabla check_stocks';

    public function handle()
    {
        $today = Carbon::now('America/Lima')->subDay()->toDateString();

        $desfases = [];

        // Obtener materiales activos
        $materials = Material::where('enable_status', 1)->get();

        foreach ($materials as $material) {
            // Calcular quantity_items (items activos)
            $quantity_items = Item::where('material_id', $material->id)
                ->where('state_item', '!=', 'exited')
                ->count();

            // Calcular quantity_entries (entradas de hoy)
            $quantity_entries = DetailEntry::where('material_id', $material->id)
                ->whereDate('created_at', $today)
                ->sum('entered_quantity');

            // Calcular quantity_outputs (salidas de hoy)
            $quantity_outputs = OutputDetail::where('material_id', $material->id)
                ->whereDate('created_at', $today)
                ->sum('percentage');

            // Determinar si hay desfase
            $isDesface = ($material->stock_current != $quantity_items) ? 1 : 0;

            // Insertar en check_stocks
            CheckStock::create([
                'material_id'       => $material->id,
                'stock_current'     => $material->stock_current,
                'quantity_items'    => $quantity_items,
                'quantity_entries'  => $quantity_entries,
                'quantity_outputs'  => $quantity_outputs,
                'full_name'         => $material->full_name,
                'date_check'        => $today,
                'isDesface'         => $isDesface,
            ]);

            // Si hay desfase, añadir a la lista
            if ($isDesface) {
                $desfases[] = (object) [
                    'material_id'    => $material->id,
                    'full_name'      => $material->full_name,
                    'stock_current'  => $material->stock_current,
                    'quantity_items' => $quantity_items,
                ];
            }
        }

        // Si hubo desfases, enviar el correo
        if (!empty($desfases)) {
            Mail::to('joryes1894@gmail.com')->send(new DesfaseStockMail($desfases));
            $this->info('Desfase de materiales.');
        }

        $this->info('Proceso de verificación de stock completado.');
    }
}
