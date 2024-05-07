<?php

namespace App\Http\Controllers;

use App\DataGeneral;
use App\Item;
use App\Material;
use App\OutputDetail;
use App\RotationMaterial;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RotationMaterialController extends Controller
{
    public function storeRotationMaterial()
    {
        DB::beginTransaction();
        try {

            $date = Carbon::now("America/Lima");

            $limite_bajo = DataGeneral::where()->first();

            // TODO: Actualizacion de rotacion de materiales
            $lastRotation = RotationMaterial::latest()->first();

            $totalOutputs = 0;

            $materialsQuantity = [];
            $quantityMaterials = [];

            if ( !isset($lastRotation) )
            {
                // TODO: Significa que no hay ultima rotacion tomamos todas las salidas desde el 2023
                $output_details = OutputDetail::whereYear('created_at', '>=', 2023)->get();

                foreach ( $output_details as $output_detail )
                {
                    if ( $output_detail->material_id == null )
                    {
                        // TODO: Entrar al item y tomar su porcentaje
                        $item_original = Item::find($output_detail->item_id);
                        if ($item_original) {
                            $material = $item_original->material;

                            // Verifica si el material existe y está activo
                            if ($material && $material->enable_status == 1) {
                                // El material está activo
                                $totalOutputs += $item_original->percentage;
                                // Guardamos el material en un array y su porcentaje
                                array_push($materialsQuantity, [
                                    "material_id" => $material->id,
                                    "percentage" => $item_original->percentage
                                ]);
                            }
                        }

                    } else {
                        $material = $output_detail->material;
                        if ($material && $material->enable_status == 1) {
                            // El material está activo
                            $totalOutputs += $output_detail->percentage;
                            // Guardamos el material en un array y su porcentaje
                            array_push($materialsQuantity, [
                                "material_id" => $material->id,
                                "percentage" => $output_detail->percentage
                            ]);
                        }

                    }
                }


            } else {
                // TODO: Significa que si hay ultima rotacion tomamos todas las salidas entre ambas fechas
                $output_details = OutputDetail::where('created_at', '>=', $lastRotation->date_rotation);
                foreach ( $output_details as $output_detail )
                {
                    if ( $output_detail->material_id == null )
                    {
                        // TODO: Entrar al item y tomar su porcentaje
                        $item_original = Item::find($output_detail->item_id);
                        if ($item_original) {
                            $material = $item_original->material;

                            // Verifica si el material existe y está activo
                            if ($material && ($material->enable_status == 1) && ($material->category_id != 8) ) {
                                // El material está activo
                                $totalOutputs += $item_original->percentage;
                                // Guardamos el material en un array y su porcentaje
                                array_push($materialsQuantity, [
                                    "material_id" => $material->id,
                                    "percentage" => $item_original->percentage
                                ]);
                            }
                        }

                    } else {
                        $material = $output_detail->material;
                        if ($material && ($material->enable_status == 1) && ($material->category_id != 8) ) {
                            // El material está activo
                            $totalOutputs += $output_detail->percentage;
                            // Guardamos el material en un array y su porcentaje
                            array_push($materialsQuantity, [
                                "material_id" => $material->id,
                                "percentage" => $output_detail->percentage
                            ]);
                        }

                    }
                }
            }

            $new_arr2 = array();
            foreach($materialsQuantity as $item) {
                if(isset($new_arr2[$item['material_id']])) {
                    $new_arr2[ $item['material_id']]['percentage'] += (float)$item['percentage'];
                    continue;
                }

                $new_arr2[$item['material_id']] = $item;
            }

            $quantityMaterials = array_values($new_arr2);

            $finalMaterialsQuantity = [];

            for ( $i=0; $i<count($quantityMaterials); $i++ )
            {
                $percentage = $quantityMaterials[$i]['percentage'];

                $rotation_value = round(($percentage/$totalOutputs)*100, 2);



                array_push($finalMaterialsQuantity, [
                    'material_id' => $quantityMaterials[$i]['material_id'],
                    'percentage' => $quantityMaterials[$i]['percentage'],
                    'rotation_value' => $rotation_value,
                    'rotation_state' => $output->request_date
                ]);
            }

            dump($totalOutputs);

            dump($quantityMaterials);

            dd();

            /*$rotation = RotationMaterial::create([
                'date_rotation' => Carbon::now("America/Lima"),
                'user_id' => Auth::id(),
            ]);*/

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Corte de Rotación'. $date->format("d/m/Y") .' guardado con éxito.'], 200);
    }

    public function destroy(RotationMaterial $rotationMaterial)
    {
        //
    }
}
