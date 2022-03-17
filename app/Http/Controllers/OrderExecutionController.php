<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\EquipmentConsumable;
use App\EquipmentMaterial;
use App\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderExecutionController extends Controller
{
    public function indexOrderExecution()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderExecution.index', compact('permissions'));

    }

    public function getAllOrderExecution()
    {
        $quotes = Quote::with('customer')
            ->where('raise_status', 1)
            ->where('state_active', 'open')
            ->whereNotIn('state', ['canceled', 'expired'])
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($quotes)->toJson();
    }

    public function indexOrderExecutionFinished()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderExecution.finish', compact('permissions'));

    }

    public function getAllOrderExecutionFinished()
    {
        $quotes = Quote::with('customer')
            ->where('raise_status', 1)
            ->where('state_active', 'close')
            ->whereNotIn('state', ['canceled', 'expired'])
            ->orderBy('created_at', 'desc')
            ->get();
        return datatables($quotes)->toJson();
    }

    public function indexExecutionAlmacen()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('orderExecution.indexAlmacen', compact('permissions'));

    }

    public function getJsonMaterialsQuoteForAlmacen( $quote_id )
    {
        $arrayMaterials = [];
        $arrayConsumables = [];
        $consumables_quantity = [];
        $quote = Quote::find($quote_id);
        //TODO: Obtencion de los materiales de la cotizacion
        $equipments = Equipment::where('quote_id', $quote->id)->get();
        if ( isset($equipments) )
        {
            foreach ( $equipments as $equipment) {
                $materials = EquipmentMaterial::with('material')
                    ->where('equipment_id', $equipment->id)
                    ->get();
                foreach ( $materials as $key => $material )
                {
                    array_push($arrayMaterials,
                        [
                            'id'=> $key+1,
                            'code' => $material->material->code,
                            'material' => $material->material->full_description,
                            'length' => $material->length,
                            'width' => $material->width,
                            'percentage' => $material->percentage,
                            'quantity' => $equipment->quantity
                        ]);
                }
            }

            foreach ( $equipments as $equipment) {
                $consumables = EquipmentConsumable::with('material')
                    ->where('equipment_id', $equipment->id)
                    ->get();
                foreach ( $consumables as $key => $consumable )
                {
                    array_push($consumables_quantity,
                        [
                            'id'=> $key+1,
                            'material_id' => $consumable->material->id,
                            'code' => $consumable->material->code,
                            'material' => $consumable->material->full_description,
                            'quantity' => $consumable->quantity*$equipment->quantity,
                        ]);
                }
            }

            $new_arr2 = array();
            foreach($consumables_quantity as $item) {
                if(isset($new_arr2[$item['material_id']])) {
                    $new_arr2[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                    continue;
                }

                $new_arr2[$item['material_id']] = $item;
            }

            $arrayConsumables = array_values($new_arr2);
        }

        return json_encode(['arrayMaterials'=>$arrayMaterials, 'arrayConsumables'=>$arrayConsumables]);
    }

    public function getJsonMaterialsByQuoteExecutionForAlmacen( $code_execution )
    {
        $arrayMaterials = [];
        $arrayConsumables = [];
        $consumables_quantity = [];
        $quote = Quote::where('order_execution', $code_execution)
            ->where('state_active', 'open')->first();
        //TODO: Obtencion de los materiales de la cotizacion
        $equipments = Equipment::where('quote_id', $quote->id)->get();
        if ( isset($equipments) )
        {
            foreach ( $equipments as $equipment) {
                $materials = EquipmentMaterial::with('material')
                    ->where('equipment_id', $equipment->id)
                    ->get();
                foreach ( $materials as $key => $material )
                {
                    array_push($arrayMaterials,
                        [
                            'id'=> $key+1,
                            'code' => $material->material->code,
                            'material' => $material->material->full_description,
                            'length' => $material->length,
                            'width' => $material->width,
                            'percentage' => $material->percentage,
                            'quantity' => $equipment->quantity
                        ]);
                }
            }

            foreach ( $equipments as $equipment) {
                $consumables = EquipmentConsumable::with('material')
                    ->where('equipment_id', $equipment->id)
                    ->get();
                foreach ( $consumables as $key => $consumable )
                {
                    array_push($consumables_quantity,
                        [
                            'id'=> $key+1,
                            'material_id' => $consumable->material->id,
                            'code' => $consumable->material->code,
                            'material' => $consumable->material->full_description,
                            'quantity' => $consumable->quantity*$equipment->quantity,
                        ]);
                }
            }

            $new_arr2 = array();
            foreach($consumables_quantity as $item) {
                if(isset($new_arr2[$item['material_id']])) {
                    $new_arr2[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                    continue;
                }

                $new_arr2[$item['material_id']] = $item;
            }

            $arrayConsumables = array_values($new_arr2);
        }

        return json_encode(['quote'=>$quote,'arrayMaterials'=>$arrayMaterials, 'arrayConsumables'=>$arrayConsumables]);
    }
}
