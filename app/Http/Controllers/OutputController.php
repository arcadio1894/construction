<?php

namespace App\Http\Controllers;

use App\Area;
use App\AreaWorker;
use App\Audit;
use App\Category;
use App\Equipment;
use App\EquipmentConsumable;
use App\EquipmentMaterial;
use App\Http\Requests\StoreRequestOutputRequest;
use App\Http\Requests\StoreSimpleOutputRequest;
use App\Item;
use App\Material;
use App\MaterialTaken;
use App\Output;
use App\OutputDetail;
use App\Quote;
use App\Subcategory;
use App\Typescrap;
use App\User;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutputController extends Controller
{
    public function indexOutputRequest()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('output.index_output_request', compact('permissions'));
    }

    public function indexOutputs()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        return view('output.index_output', compact('permissions'));
    }

    public function createOutputRequest()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $users = User::all();
        return view('output.create_output_request', compact('permissions', 'users'));
    }

    public function createOutputRequestOrder($id_quote)
    {
        $begin = microtime(true);
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quote = Quote::with(['equipments' => function ($query) {
                $query->with(['materials']);
            }])
            ->find($id_quote);

        /*$outputs = Output::where('execution_order', $quote->order_execution)
            //->where('indicator', 'orn')
            ->get();*/

        $items_quantity = [];

        /*foreach ( $outputs as $output )
        {
            $details = $output->details;
            //dd($details);
            foreach ( $details as $detail )
            {
                $item = Item::find($detail->item_id);
                // TODO:Logica para traer el verdadero quantity del item
                $after_item = Item::where('code', $item->code)
                    ->where('id', '<>', $item->id)
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ( $after_item )
                {
                    $quantity = ($item->percentage == 0) ? (1-(float)$after_item->percentage) : (float)$item->percentage-(float)$after_item->percentage;
                } else {
                    $quantity = (float)$item->percentage;
                }
                array_push($items_quantity, array('material_id'=>$item->material_id, 'material'=>$item->material->full_description, 'quantity'=> $quantity));

            }

        }*/

        /*$new_arr3 = array();
        foreach($items_quantity as $item) {
            if(isset($new_arr3[$item['material_id']])) {
                $new_arr3[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr3[$item['material_id']] = $item;
        }*/

        /*$items = array_values($new_arr3);*/
        $items = [];

        $materials_quantity = [];
        $materials = [];

        $turnstiles = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->turnstiles as $turnstile )
            {
                array_push($turnstiles, array('material'=>$turnstile->description, 'quantity'=> (float)$turnstile->quantity*(float)$equipment->quantity));

            }
            foreach ( $equipment->materials as $material )
            {
                if ( $material->replacement == 0 )
                {
                    array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));

                }

            }
            foreach ( $equipment->consumables as $consumable )
            {
                $subcategory = Subcategory::find($consumable->material->subcategory_id);
                if (isset( $subcategory ))
                {
                    $category = Category::find($consumable->material->category_id);
                    if ( $category->id == 2 && trim($subcategory->name) === 'MIXTO' )
                    {
                        array_push($materials_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                }

            }

        }


        $new_arr = array();
        foreach($materials_quantity as $item) {
            if(isset($new_arr[$item['material_id']])) {
                $new_arr[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr[$item['material_id']] = $item;
        }

        $materials = array_values($new_arr);

        $consumables = [];
        $consumables_quantity = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->consumables as $consumable )
            {
                $subcategory = Subcategory::find($consumable->material->subcategory_id);
                if (isset( $subcategory ))
                {
                    $category = Category::find($consumable->material->category_id);
                    if ( $category->id == 2 && trim($subcategory->name) <> 'MIXTO' )
                    {
                        array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                } else {
                    array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                }


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

        $consumables = array_values($new_arr2);
        $users = User::all();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Crear solicitud de salida normal',
            'time' => $end
        ]);

        return view('output.create_output_request_order', compact('users','permissions', 'consumables', 'materials', 'quote', 'items', 'turnstiles'));
    }

    public function createOutputRequestOrderExtra($id_quote)
    {
        $begin = microtime(true);
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quote = Quote::with(['equipments' => function ($query) {
            $query->with(['materials']);
        }])
            ->find($id_quote);

        $materials_quantity = [];
        $materials = [];

        $turnstiles = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->turnstiles as $turnstile )
            {
                array_push($turnstiles, array('material'=>$turnstile->description, 'quantity'=> (float)$turnstile->quantity*(float)$equipment->quantity));

            }
            foreach ( $equipment->materials as $material )
            {
                if ( $material->replacement == 0 )
                {
                    array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));

                }

            }
            foreach ( $equipment->consumables as $consumable )
            {
                $subcategory = Subcategory::find($consumable->material->subcategory_id);

                if (isset( $subcategory ))
                {
                    $category = Category::find($consumable->material->category_id);
                    if ( $category->id == 2 && trim($subcategory->name) === 'MIXTO' )
                    {
                        array_push($materials_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                }

            }

        }

        $new_arr = array();
        foreach($materials_quantity as $item) {
            if(isset($new_arr[$item['material_id']])) {
                $new_arr[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr[$item['material_id']] = $item;
        }

        $materials = array_values($new_arr);

        /*$outputs = Output::where('execution_order', $quote->order_execution)
            //->where('indicator', 'ore')
            ->get();

        $items_quantity = [];*/

        /*foreach ( $outputs as $output )
        {
            $details = $output->details;
            //dd($details);
            foreach ( $details as $detail )
            {
                $item = Item::find($detail->item_id);
                // TODO:Logica para traer el verdadero quantity del item
                $after_item = Item::where('code', $item->code)
                    ->where('id', '<>', $item->id)
                    ->orderBy('created_at', 'asc')
                    ->first();

                if ( $after_item )
                {
                    $quantity = ($item->percentage == 0) ? (1-(float)$after_item->percentage) : (float)$item->percentage-(float)$after_item->percentage;
                } else {
                    $quantity = (float)$item->percentage;
                }
                array_push($items_quantity, array('material_id'=>$item->material_id, 'material'=>$item->material->full_description, 'quantity'=> $quantity));
                //array_push($items_quantity, array('material_id'=>$item->material_id, 'material'=>$item->material->full_description, 'material_complete'=>$item->material, 'quantity'=> $item->percentage));

            }

        }*/

        /*$new_arr3 = array();
        foreach($items_quantity as $item) {
            if(isset($new_arr3[$item['material_id']])) {
                $new_arr3[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr3[$item['material_id']] = $item;
        }*/

        /*$items = array_values($new_arr3);*/
        $consumables = [];
        $consumables_quantity = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->consumables as $consumable )
            {
                $subcategory = Subcategory::find($consumable->material->subcategory_id);
                if (isset( $subcategory ))
                {
                    $category = Category::find($consumable->material->category_id);
                    if ( $category->id == 2 && trim($subcategory->name) <> 'MIXTO' )
                    {
                        array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                } else {
                    array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                }


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

        $consumables = array_values($new_arr2);

        $items = [];

        $users = User::all();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Crear Solicitud de salida extra',
            'time' => $end
        ]);

        return view('output.create_output_request_order_extra', compact('users','permissions', 'materials', 'consumables', 'quote', 'items', 'turnstiles'));
    }

    public function getOutputRequest()
    {
        $begin = microtime(true);
        $outputs = Output::with('requestingUser')
            ->with('responsibleUser')
            ->with('quote')
            ->where('indicator', '<>', 'ors')
            ->orderBy('created_at', 'desc')
            ->get();

        $array = [];

        foreach ( $outputs as $output )
        {
            $itemsNull = OutputDetail::where('output_id', $output->id)
                ->whereNull('item_id')->count();
            array_push($array, [
                'id' => $output->id,
                'execution_order' => $output->execution_order,
                'description_quote' => ($output->quote == null) ? 'No hay datos': $output->quote->description_quote,
                'request_date' => $output->request_date,
                'requesting_user' => $output->requestingUser->name,
                'responsible_user' => $output->responsibleUser->name,
                'state' => $output->state,
                'custom' => ($itemsNull > 0) ? true: false,
            ]);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Solicitudes de salida',
            'time' => $end
        ]);
        //dd($outputs);
        return datatables($array)->toJson();
    }

    public function getJsonItemsOutputRequest( $output_id )
    {
        $begin = microtime(true);
        $array = [];
        $materials = [];
        $materials_quantity = [];
        $outputDetails = OutputDetail::where('output_id', $output_id)->get();
        foreach ( $outputDetails as $key => $outputDetail )
        {
            $item = Item::with(['location', 'material'])
                ->find($outputDetail->item_id);

            $material = Material::find($outputDetail->material_id);
            if ( $material->typescrap_id != null || $material->typescrap_id != '' )
            {
                if (isset($item)) {
                    $l = 'AR:' . $item->location->area->name . '|AL:' . $item->location->warehouse->name . '|AN:' . $item->location->shelf->name . '|NIV:' . $item->location->level->name . '|CON:' . $item->location->container->name;
                    array_push($array,
                        [
                            'id' => $key + 1,
                            'material_id' => $item->material->id,
                            'material' => $item->material->full_description,
                            'id_item' => $item->id,
                            'code' => $item->code,
                            'length' => $item->length,
                            'width' => $item->width,
                            'weight' => $item->weight,
                            'price' => $item->price,
                            'percentage' => $item->percentage,
                            'location' => $l,
                            'state' => $item->state,
                            'detail_id' => $outputDetail->id
                        ]);

                } else {
                    array_push($array,
                        [
                            'id' => $key + 1,
                            'material_id' => $outputDetail->material->id,
                            'material' => $outputDetail->material->full_description,
                            'id_item' => 'Personalizado',
                            'code' => 'Personalizado',
                            'length' => $outputDetail->length,
                            'width' => $outputDetail->width,
                            'weight' => null,
                            'price' => $outputDetail->price,
                            'location' => 'Personalizado',
                            'percentage' => $outputDetail->percentage,
                            'state' => 'Personalizado',
                            'detail_id' => $outputDetail->id
                        ]);
                }
            } else {
                array_push($materials_quantity, array('code'=>$material->code, 'material_id'=>$material->id, 'material'=>$material->full_description, 'material_complete'=>$material, 'quantity'=> (float)$item->percentage));

            }
        }

        $new_arr3 = array();
        foreach($materials_quantity as $item) {
            if(isset($new_arr3[$item['material_id']])) {
                $new_arr3[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr3[$item['material_id']] = $item;
        }

        $materials = array_values($new_arr3);

        $output = Output::find($output_id);
        $quote = Quote::where('order_execution', $output->execution_order)->first();

        $consumables = [];
        $consumables_quantity = [];
        if ( isset( $quote ) )
        {
            foreach ( $quote->equipments as $equipment )
            {
                foreach ( $equipment->consumables as $key => $consumable )
                {
                    if (isset( $consumable->material->subcategory ))
                    {
                        if ( $consumable->material->category_id == 2 && trim($consumable->material->subcategory->name) <> 'MIXTO' )
                        {
                            array_push($consumables_quantity, array('id'=>$key+1, 'material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                        }
                    } else {
                        array_push($consumables_quantity, array('id'=>$key+1, 'material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                }

            }

            foreach ( $quote->equipments as $equipment )
            {
                foreach ( $equipment->consumables as $consumable )
                {
                    if (isset( $consumable->material->subcategory ))
                    {
                        if ( $consumable->material->category_id == 2 && trim($consumable->material->subcategory->name) <> 'MIXTO' )
                        {
                            array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                        }
                    } else {
                        array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }


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

            $consumables = array_values($new_arr2);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Materiales de las salidas',
            'time' => $end
        ]);
        //dd($array);
        return json_encode(['array'=>$array, 'consumables'=>$consumables, 'materials'=>$materials]);
    }

    public function getJsonItemsOutputRequestDevolver( $output_id )
    {
        $begin = microtime(true);
        $array = [];
        $materials = [];
        $materials_quantity = [];
        $outputDetails = OutputDetail::where('output_id', $output_id)->get();
        foreach ( $outputDetails as $key => $outputDetail )
        {
            $item = Item::with(['location', 'material'])
                ->find($outputDetail->item_id);

            $material = Material::find($outputDetail->material_id);

                if (isset($item)) {
                    $l = 'AR:' . $item->location->area->name . '|AL:' . $item->location->warehouse->name . '|AN:' . $item->location->shelf->name . '|NIV:' . $item->location->level->name . '|CON:' . $item->location->container->name;
                    array_push($array,
                        [
                            'id' => $key + 1,
                            'material' => $item->material->full_description,
                            'id_item' => $item->id,
                            'code' => $item->code,
                            'length' => $item->length,
                            'width' => $item->width,
                            'weight' => $item->weight,
                            'price' => $item->price,
                            'percentage' => $item->percentage,
                            'location' => $l,
                            'state' => $item->state,
                            'detail_id' => $outputDetail->id
                        ]);

                } else {
                    array_push($array,
                        [
                            'id' => $key + 1,
                            'material' => $outputDetail->material->full_description,
                            'id_item' => 'Personalizado',
                            'code' => 'Personalizado',
                            'length' => $outputDetail->length,
                            'width' => $outputDetail->width,
                            'weight' => null,
                            'price' => $outputDetail->price,
                            'location' => 'Personalizado',
                            'percentage' => $outputDetail->percentage,
                            'state' => 'Personalizado',
                            'detail_id' => $outputDetail->id
                        ]);
                }

        }

        $output = Output::find($output_id);
        $quote = Quote::where('order_execution', $output->execution_order)->first();

        $consumables = [];
        $consumables_quantity = [];
        if ( isset( $quote ) )
        {
            foreach ( $quote->equipments as $equipment )
            {
                foreach ( $equipment->consumables as $key => $consumable )
                {
                    if (isset( $consumable->material->subcategory ))
                    {
                        if ( $consumable->material->category_id == 2 && trim($consumable->material->subcategory->name) <> 'MIXTO' )
                        {
                            array_push($consumables_quantity, array('id'=>$key+1, 'material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                        }
                    } else {
                        array_push($consumables_quantity, array('id'=>$key+1, 'material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                }

            }

            foreach ( $quote->equipments as $equipment )
            {
                foreach ( $equipment->consumables as $consumable )
                {
                    if (isset( $consumable->material->subcategory ))
                    {
                        if ( $consumable->material->category_id == 2 && trim($consumable->material->subcategory->name) <> 'MIXTO' )
                        {
                            array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                        }
                    } else {
                        array_push($consumables_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }


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

            $consumables = array_values($new_arr2);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener materiales para devolver',
            'time' => $end
        ]);
        //dd($array);
        return json_encode(['array'=>$array, 'consumables'=>$consumables]);
    }

    public function attendOutputRequest(Request $request)
    {
        $begin = microtime(true);
        //dd($request);
        $output = Output::find($request->get('output_id'));
        DB::beginTransaction();
        try {
            $output->state = 'attended';
            $output->save();

            $outputDetails = OutputDetail::where('output_id', $output->id)->get();
            foreach ( $outputDetails as $outputDetail )
            {
                $item = Item::find($outputDetail->item_id);
                $item->state_item = 'exited';
                $item->save();
                // TODO: Dismunir el stock del material
                $material = Material::find($item->material_id);
                $material->stock_current = $material->stock_current - $item->percentage;
                $material->save();

                $quote = Quote::where('order_execution', $output->execution_order)->first();

                // TODO: Verificar si la salida es normal o extra
                // TODO: Si es normal se coloca en material_taken
                if (isset($quote))
                {
                    MaterialTaken::create([
                        'material_id' => $material->id,
                        'quantity_request' => $item->percentage,
                        'quote_id' => $quote->id,
                        'output_id' => $output->id,
                        'equipment_id' => $outputDetail->equipment_id,
                        'output_detail_id' => $outputDetail->id,
                        'type_output' => $output->indicator
                    ]);
                }

            }
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Atender Salidas',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Solicitud de salida atendida con éxito.'], 200);

    }

    public function confirmOutputRequest(Request $request)
    {
        $begin = microtime(true);
        //dd($request);
        $output = Output::find($request->get('output_id'));
        DB::beginTransaction();
        try {
            $output->state = 'confirmed';
            $output->save();
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Confirmar Salida',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Salida confirmada con éxito.'], 200);

    }

    public function editOutputExecution(Request $request)
    {
        //dd($request);
        $output = Output::find($request->get('output_id'));
        DB::beginTransaction();
        try {
            $output->execution_order = $request->get('execution_order');
            $output->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Cambios guardados con éxito.'], 200);

    }

    public function storeOutputRequest(StoreRequestOutputRequest $request)
    {
        $begin = microtime(true);
        //dd($request);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // TODO: Hacer la validacion de la cotizacion si ya cumplio la cantidad
            // Obtener la cotizacion con esa orden de ejecucion
            $quote = Quote::where('order_execution', $request->get('execution_order'))
                ->first();
            //dd($quote->id);

            $requesting_user = User::where('name', $request->get('requesting_user'))->first();
            $responsible_user = User::where('id', $request->get('responsible_user'))->first();
            $output = Output::create([
                'execution_order' => $request->get('execution_order'),
                'request_date' => Carbon::createFromFormat( 'd/m/Y', ($request->get('request_date')) ),
                'requesting_user' => $requesting_user->id,
                'responsible_user' => $responsible_user->id,
                'state' => 'created',
                'indicator' => $request->get('indicator'),
            ]);
            //dump($output->id);
            $items = json_decode($request->get('items'));
            //dd('asdasd');

            // TODO: 1° Sacamos los equipos y materiales
            //$arregloEquipMaterials = [];
            /*foreach ( $items as $item )
            {
                array_push($arregloEquipMaterials, ['equipment'=>$item->equipment_id, 'material'=>$item->material_id]);
            }*/

            //$arregloEquipoMateriales = array_unique($arregloEquipMaterials, SORT_REGULAR);

            /*foreach ( $arregloEquipoMateriales as $item )
            {
                // TODO: 2° Sacar los equipment_materials de ese equipo
                $equipment_id = $item->equipment;
                $material_id = $item->material;
                $cant_equipment = EquipmentMaterial::where('equipment_id', $equipment_id)
                    ->where('material_id', $material_id)->sum('quantity');
                if( $cant_equipment == 0 )
                {
                    $cant_equipment = EquipmentConsumable::where('equipment_id', $equipment_id)
                        ->where('material_id', $material_id)->sum('quantity');
                } else {
                    $cant_equipment = 0;
                }
                $equipment = Equipment::find($equipment_id);

                $cant_equipment_material = (float)$cant_equipment * (float)$equipment->quantity;

                // TODO: 3° Sacar las salidas de ese equipment y material
                $cant_equipment_material_salidas = OutputDetail::where('equipment_id', $equipment_id)
                    ->where('material_id', $material_id)->sum('percentage');

                // TODO: 4° Recorremos los item sumando los porcentajes de equip mat
                $cant_equipment_material_solicitado = 0;
                foreach ( $items as $item2 )
                {
                    if ( $item2->equipment_id == $equipment_id && $item2->material_id == $material_id )
                    {
                        $cant_equipment_material_solicitado += $item2->percentage;
                    }
                }

                // TODO: 5°
                if ( ($cant_equipment_material_solicitado + $cant_equipment_material_salidas) > $cant_equipment_material )
                {
                    return response()->json(['message' => 'Lo sentimos, un material ya sobrepasó la cantidad pedida en cotización.'], 422);
                }

            }*/

            /*foreach ( $arregloResumen as $item )
            {
                // Obtener la suma de las cantidades de ese material (L)
                // Obtener las salidas de esa cotizacion
                // Sumar las salidas de ese material
                // Luego sumar la cantidad de los items de ese material
                // Por ultimo sumar ambas cantidades
                // Finalmente comparar las salidas de ese material con la cant de cot
                if ( isset($quote) )
                {
                    $quantity = 0;
                    foreach ( $quote->equipments as $equipment )
                    {
                        if ( !$equipment->finished && $equipment->id == $item->equipment_id)
                        {
                            foreach ( $equipment->materials as $material )
                            {
                                // TODO: Reemplazo de materiales
                                if ( $material->replacement == 0 && $material->material_id == $item->material_id )
                                {
                                    $quantity += (float)$material->quantity*(float)$equipment->quantity;
                                }

                            }
                        }

                    }

                    $outputs_details = OutputDetail::where('quote_id', $quote->id)
                        ->where('equipment_id', $item->equipment_id)
                        ->where()
                }
            }*/

            foreach ( $items as $item )
            {
                $mystring = $item->item;
                $findme   = '_';
                $pos = strpos($mystring, $findme);
                $custom = substr($mystring, 0, $pos);
                if ( $custom == 'Personalizado' ) {
                    // TODO: tenemos que guardar el largo y ancho
                    $detail_output = OutputDetail::create([
                        'output_id' => $output->id,
                        'item_id' => null,
                        'length' => $item->length,
                        'width' => $item->width,
                        'price' => $item->price,
                        'percentage' => $item->percentage,
                        'material_id' => $item->material_id,
                        'equipment_id' => ( $item->equipment_id == '' ) ? null:$item->equipment_id,
                        'quote_id' => ($quote == null) ? null: $quote->id,
                        'custom' => 1

                    ]);
                } else {
                    $item_selected = Item::find($item->item);
                    if ( $item_selected->state_item === 'reserved' )
                    {
                        return response()->json(['message' => 'Lo sentimos, un item seleccionado ya estaba reservado para otra solicitud.'], 422);
                    } else {
                        $item_selected->state_item = 'reserved';
                        $item_selected->save();

                        // TODO: Esto va a cambiar
                        $detail_output = OutputDetail::create([
                            'output_id' => $output->id,
                            'item_id' => $item->item,
                            'length' => $item->length,
                            'width' => $item->width,
                            'price' => $item->price,
                            'percentage' => $item->percentage,
                            'material_id' => $item->material_id,
                            'equipment_id' => ( $item->equipment_id == '' ) ? null:$item->equipment_id,
                            'quote_id' => ($quote == null) ? null: $quote->id,
                            'custom' => 0
                        ]);
                    }
                }

            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar solicitud de salida POST',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Solicitud de salida guardada con éxito.', 'url'=>route('output.request.index')], 200);

    }

    public function destroyTotalOutputRequest(Request $request)
    {
        //dump($request);
        $begin = microtime(true);
        DB::beginTransaction();
        try {

            $output = Output::find($request->get('output_id'));

            if ($output->state === 'created')
            {
                $outputDetails = OutputDetail::where('output_id', $output->id)->get();
                foreach ( $outputDetails as $outputDetail )
                {
                    if ( $outputDetail->item_id != null )
                    {
                        $item = Item::find($outputDetail->item_id);

                        $items = Item::where('code',$item->code)->get();
                        $count_items = count($items);
                        $last_item = Item::where('code',$item->code)
                            ->orderBy('created_at', 'desc')->first();
                        if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                            return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
                        } else {
                            if ($count_items>1){
                                $item->state_item = 'scraped';
                                $item->save();
                            } else {
                                $item->state_item = 'entered';
                                $item->save();
                            }
                        }
                    }

                    $outputDetail->delete();
                }
                $output->delete();
            }

            if ($output->state !== 'created')
            {
                $outputDetails = OutputDetail::where('output_id', $output->id)->get();
                foreach ( $outputDetails as $outputDetail )
                {
                    if ( $outputDetail->item_id == null )
                    {
                        $item = Item::find($outputDetail->item_id);
                        $items = Item::where('code',$item->code)->get();
                        $count_items = count($items);
                        $last_item = Item::where('code',$item->code)
                            ->orderBy('created_at', 'desc')->first();
                        if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                            return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
                        } else {
                            if ($count_items>1){
                                $item->state_item = 'scraped';
                                $item->save();
                                $material = Material::find($item->material_id);
                                $material->stock_current = $material->stock_current + $item->percentage;
                                $material->save();
                            } else {
                                $item->state_item = 'entered';
                                $item->save();
                                $material = Material::find($item->material_id);
                                $material->stock_current = $material->stock_current + $item->percentage;
                                $material->save();
                            }
                        }

                        // TODO: Dismunir el stock del material
                    }
                    $material_taken = MaterialTaken::where('output_detail_id', $outputDetail->id)->first();
                    if (isset( $material_taken->id ))
                    {
                        $material_taken->delete();
                    }
                    $outputDetail->delete();

                }
                $output->delete();
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar total Salida',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Eliminación total con éxito.'], 200);

    }

    public function returnItemOutputDetail(Request $request, $id_output, $id_item)
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {
            $outputDetail = OutputDetail::find($id_output);

            $item = Item::find($id_item);
            $items = Item::where('code',$item->code)->get();
            $count_items = count($items);
            $last_item = Item::where('code',$item->code)
                ->orderBy('created_at', 'desc')->first();
            if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
            } else {
                if ($count_items>1){
                    $item->state_item = 'scraped';
                    $item->save();
                    $material = Material::find($item->material_id);
                    $material->stock_current = $material->stock_current + $item->percentage;
                    $material->save();
                } else {
                    if ( $this->esCompleto($item->id) )
                    {
                        $item->state_item = 'entered';
                        $item->save();
                        $material = Material::find($item->material_id);
                        $material->stock_current = $material->stock_current + $item->percentage;
                        $material->save();
                    } else {
                        $item->state_item = 'scraped';
                        $item->save();
                        $material = Material::find($item->material_id);
                        $material->stock_current = $material->stock_current + $item->percentage;
                        $material->save();
                    }

                }
            }
            $material_taken = MaterialTaken::where('output_detail_id', $outputDetail->id)->first();
            if (isset( $material_taken->id ))
            {
                $material_taken->delete();
            }

            $outputDetail->delete();
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Retornar item de salida',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Item devuelto.'], 200);

    }

    public function esCompleto($id_item)
    {
        $item = Item::find($id_item);
        if ( $item->typescrap_id == null )
        {
            return true;
        } else {
            $typescrap = Typescrap::find($item->typescrap_id);
            if ( $item->typescrap_id == 1 || $item->typescrap_id == 2 )
            {
                // Planchas
                if ( $item->length == $typescrap->length && $item->width == $typescrap->width )
                {
                    return true;
                }
            } elseif ( $item->typescrap_id == 3 || $item->typescrap_id == 4 ) {
                // Tubos
                if ( $item->length == $typescrap->length )
                {
                    return true;
                }
            }
            return false;
        }

    }

    public function destroyPartialOutputRequest(Request $request, $id_output, $id_item)
    {
        //dump($request);
        $begin = microtime(true);
        DB::beginTransaction();
        try {

            $outputDetailG =  OutputDetail::find($id_output);
            $output = Output::find($outputDetailG->output_id);

            if ($output->state === 'created')
            {
                if ( $id_item != 'Personalizado' )
                {
                    $outputDetail = OutputDetail::where('output_id', $output->id)
                        ->where('item_id', $id_item)->first();

                    $item = Item::find($outputDetail->item_id);
                    $items = Item::where('code',$item->code)->get();
                    $count_items = count($items);
                    $last_item = Item::where('code',$item->code)
                        ->orderBy('created_at', 'desc')->first();
                    if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                        return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
                    } else {
                        if ($count_items>1){
                            $item->state_item = 'scraped';
                            $item->save();
                        } else {
                            $item->state_item = 'entered';
                            $item->save();
                        }
                    }
                    $outputDetail->delete();
                } else {
                    $outputDetailG->delete();
                }

            }

            if ($output->state !== 'created')
            {
                if ( $id_item != 'Personalizado' )
                {
                    $outputDetail = OutputDetail::where('output_id', $output->id)
                        ->where('item_id', $id_item)->first();

                    $item = Item::find($outputDetail->item_id);
                    $items = Item::where('code',$item->code)->get();
                    $count_items = count($items);
                    $last_item = Item::where('code',$item->code)
                        ->orderBy('created_at', 'desc')->first();
                    if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                        return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
                    } else {
                        if ($count_items>1){
                            $item->state_item = 'scraped';
                            $item->save();
                            $material = Material::find($item->material_id);
                            $material->stock_current = $material->stock_current + $item->percentage;
                            $material->save();
                        } else {
                            $item->state_item = 'entered';
                            $item->save();
                            $material = Material::find($item->material_id);
                            $material->stock_current = $material->stock_current + $item->percentage;
                            $material->save();
                        }
                    }
                    $material_taken = MaterialTaken::where('output_detail_id', $outputDetail->id)->first();
                    if (isset( $material_taken->id ))
                    {
                        $material_taken->delete();
                    }
                    $outputDetail->delete();
                } else {
                    $material_taken = MaterialTaken::where('output_detail_id', $outputDetail->id)->first();
                    if (isset( $material_taken->id ))
                    {
                        $material_taken->delete();
                    }
                    $outputDetailG->delete();
                }

            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar parcial salida',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Eliminación del item con éxito.'], 200);

    }

    public function createItemCustom( $id_detail )
    {
        $outputDetail = OutputDetail::find($id_detail);
        $material = Material::find($outputDetail->material_id);
        //dd($outputDetail);
        return view('output.create_item_custom', compact('outputDetail', 'material'));
    }

    public function assignItemToOutputDetail($id_item, $id_detail)
    {
        $outputDetail = OutputDetail::find($id_detail);
        $item = Item::find($id_item);

        DB::beginTransaction();
        try {
            $outputDetail->item_id = $item->id;
            $outputDetail->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Item asignado.'], 200);

    }

    public function reportMaterialOutputs()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('output.reportMaterialOutputs', compact('permissions'));
    }

    public function getJsonMaterialsInOutput()
    {
        $begin = microtime(true);
        $materials = Material::where('enable_status', 1)->get();

        $array = [];
        foreach ( $materials as $material )
        {
            array_push($array, ['id'=> $material->id, 'material' => $material->full_description, 'code' => $material->code]);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Materiales que tienen salida',
            'time' => $end
        ]);
        return $array;
        /*$outputDetails = OutputDetail::with('items')->get();
        $materials = [];
        foreach ($outputDetails as $outputDetail) {
            if ( $outputDetail->items != null ) {
                $mate = Material::find($outputDetail->items->material_id);

                array_push($materials, [
                    'id' => $mate->id,
                    'full_description' => $mate->full_description
                ]);
            }

        }
        $result = array_values( array_unique($materials, SORT_REGULAR) );
        return $result;*/
    }

    public function getJsonOutputsOfMaterial( $id_material )
    {
        $begin = microtime(true);
        $dateCurrent = Carbon::now('America/Lima');
        $date4MonthAgo = $dateCurrent->subMonths(4);

        /*Esto se puede usar para reportes muy antiguos pero demora mucho
         * $outputDetails = OutputDetail::with(
            ['items' => function ($query) use ($id_material) {
                $query->where('material_id', '=', $id_material);
            }])
            ->where('created_at', '>=', $date6MonthAgo)
            ->where('material_id', $id_material)
            ->get();*/

        $outputDetails = OutputDetail::where('created_at', '>=', $date4MonthAgo)
            ->where('material_id', $id_material)
            ->get();

        $outputs = [];
        foreach ($outputDetails as $outputDetail) {
            if ( $outputDetail->item_id != null )
            {

                /*if ( $outputDetail->items->material_id == $id_material )
                {*/

                    $output = Output::with(['quote', 'responsibleUser', 'requestingUser'])->find($outputDetail->output_id);
                    //dump($output);
                    $item_original = Item::find($outputDetail->item_id);
                    $after_item = Item::where('code', $item_original->code)
                        ->where('id', '<>', $item_original->id)
                        ->orderBy('created_at', 'asc')
                        ->first();

                    if ( $after_item )
                    {
                        $quantity = ($item_original->percentage == 0) ? (1-(float)$after_item->percentage) : (float)$item_original->percentage-(float)$after_item->percentage;
                    } else {
                        $quantity = (float)$item_original->percentage;
                    }
                    array_push($outputs, [
                        'output' => $output->id,
                        'order_execution' => $output->execution_order,
                        'description' => ($output->quote == null) ? 'Sin datos':$output->quote->description_quote,
                        'date' => $output->request_date,
                        'user_responsible' => ($output->responsibleUser == null) ? 'Sin responsable':$output->responsibleUser->name,
                        'user_request' => ($output->requestingUser == null) ? 'Sin solicitante':$output->requestingUser->name,
                        'quantity' => $quantity
                    ]);
                /*}*/

            }

        }

        $new_arr2 = array();
        foreach($outputs as $item) {
            if(isset($new_arr2[$item['output']])) {
                $new_arr2[ $item['output']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr2[$item['output']] = $item;
        }

        $coutputs_final = array_values($new_arr2);
        //dump($outputs);
        //$result = array_values( array_unique($outputs) );

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Reporte de Materiales en salida',
            'time' => $end
        ]);
        return $coutputs_final;
    }

    public function getQuantityMaterialOutputs($quote_id, $material_id)
    {
        $begin = microtime(true);
        $quote = Quote::find($quote_id);
        $equipments = Equipment::where('quote_id', $quote_id)->get();

        $materials_quantity = [];
        $materials = [];

        foreach ( $equipments as $equipment )
        {
            $equipment_materials = EquipmentMaterial::where('equipment_id', $equipment->id)
                ->where('material_id', $material_id)
                ->get();
            foreach ( $equipment_materials as $material )
            {
                if ( $material->replacement == 0 )
                {
                    array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));

                }

            }
            $equipment_consumables = EquipmentConsumable::where('equipment_id', $equipment->id)
                ->where('material_id', $material_id)
                ->get();
            foreach ( $equipment_consumables as $consumable )
            {
                $subcategory = Subcategory::find($consumable->material->subcategory_id);

                if (isset( $subcategory ))
                {
                    $category = Category::find($consumable->material->category_id);
                    if ( $category->id == 2 && trim($subcategory->name) === 'MIXTO' )
                    {
                        array_push($materials_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                }

            }

        }

        $new_arr = array();
        foreach($materials_quantity as $item) {
            if(isset($new_arr[$item['material_id']])) {
                $new_arr[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr[$item['material_id']] = $item;
        }

        $materials = array_values($new_arr);

        $outputs = Output::where('execution_order', $quote->order_execution)
            //->where('indicator', 'ore')
            ->get();

        $items_quantity = [];
        $items = [];

        foreach ( $outputs as $output )
        {
            $details = $output->details;
            foreach ( $details as $detail )
            {
                if ( $detail->material_id == $material_id )
                {
                    if ( $detail->item_id == null )
                    {
                        $material = Material::find($detail->material_id);
                        array_push($items_quantity, array('material_id'=>$material->id, 'material'=>$material->full_description, 'quantity'=> $detail->percentage));

                    } else {
                        $item = Item::find($detail->item_id);
                        // TODO:Logica para traer el verdadero quantity del item
                        $after_item = Item::where('code', $item->code)
                            ->where('id', '<>', $item->id)
                            ->orderBy('created_at', 'asc')
                            ->first();

                        if ( $after_item )
                        {
                            $quantity = ($item->percentage == 0) ? (1-(float)$after_item->percentage) : (float)$item->percentage-(float)$after_item->percentage;
                        } else {
                            $quantity = (float)$item->percentage;
                        }
                        if ( $item->material_id == $material_id )
                        {
                            array_push($items_quantity, array('material_id'=>$item->material_id, 'material'=>$item->material->full_description, 'quantity'=> $quantity));

                        }
                    }
                }


                //array_push($items_quantity, array('material_id'=>$item->material_id, 'material'=>$item->material->full_description, 'material_complete'=>$item->material, 'quantity'=> $item->percentage));

            }

        }

        $new_arr3 = array();
        foreach($items_quantity as $item) {
            if(isset($new_arr3[$item['material_id']])) {
                $new_arr3[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr3[$item['material_id']] = $item;
        }

        $items = array_values($new_arr3);

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener cantidad solicitada y faltante de materiales',
            'time' => $end
        ]);

        return response()->json(
            [   'material' => (float)$materials[0]['material_id'],
                'quantity' => ($materials[0]['quantity'] == null) ? 0 : (float)$materials[0]['quantity'],
                'request' => ( count($items) == 0) ? 0 : (float)$items[0]['quantity'],
                'missing' => ( count($items) == 0) ? (float)$materials[0]['quantity'] : (float)$materials[0]['quantity'] - (float)$items[0]['quantity']
            ]
            , 200);

        //dump($materials);
        //dump($items);

    }

    public function show(Output $output)
    {
        //
    }

    public function edit(Output $output)
    {
        //
    }

    public function update(Request $request, Output $output)
    {
        //
    }

    public function destroy(Output $output)
    {
        //
    }

    public function modificandoMaterialesTomados()
    {
        $material_takens = MaterialTaken::whereNotNull('output_detail_id')->get();
        //dump('Cantidad de registros materiales tomados');
        dump(count($material_takens));
        foreach ( $material_takens as $material_taken )
        {
            $output_detail = OutputDetail::find($material_taken->output_detail_id);
            $material_taken->equipment_id = $output_detail->equipment_id;
            $material_taken->save();
        }
    }

    public function confirmAllOutputsAttend( Request $request )
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {
            $outputs = Output::where('state', 'attended')->get();

            //dd($outputs);
            foreach ( $outputs as $output )
            {
                $output->state = 'confirmed';
                $output->save();
            }
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Confirmar todas las salidas',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Salidas confirmadas con éxito.'], 200);

    }

    public function indexOutputSimple()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('output.index_output_simple', compact('permissions'));
    }

    public function getOutputSimple()
    {
        $begin = microtime(true);
        $outputs = Output::with('requestingUser')
            ->with('responsibleUser')
            ->with('quote')
            ->where('indicator', 'ors')
            ->orderBy('created_at', 'desc')
            ->get();

        $array = [];

        foreach ( $outputs as $output )
        {
            $user_id = $output->responsibleUser->id;
            $worker = Worker::where('user_id', $user_id)->first();
            $area = AreaWorker::find($worker->area_worker_id);

            $itemsNull = OutputDetail::where('output_id', $output->id)
                ->whereNull('item_id')->count();
            array_push($array, [
                'id' => $output->id,
                'description' => $output->execution_order,
                'request_date' => $output->request_date,
                'requesting_user' => $output->requestingUser->name,
                'responsible_user' => $output->responsibleUser->name,
                'state' => $output->state,
                'area' => ($area == null)  ? 'Sin área':$area->name,
                'custom' => ($itemsNull > 0) ? true: false,
            ]);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener salidas simples',
            'time' => $end
        ]);
        //dd($outputs);
        return datatables($array)->toJson();
    }

    public function createOutputSimple()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $users = User::where('enable', 1)->get();
        return view('output.create_output_simple', compact('permissions', 'users'));
    }

    public function storeOutputSimple(StoreSimpleOutputRequest $request)
    {
        $begin = microtime(true);
        //dd($request);
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // TODO: Hacer la validacion de la cotizacion si ya cumplio la cantidad

            $requesting_user = User::where('name', $request->get('requesting_user'))->first();
            $responsible_user = User::where('id', $request->get('responsible_user'))->first();
            $output = Output::create([
                'execution_order' => ($request->get('execution_order') == '' || $request->get('execution_order') == null ) ? '': $request->get('execution_order'),
                'request_date' => Carbon::createFromFormat( 'd/m/Y', ($request->get('request_date')) ),
                'requesting_user' => $requesting_user->id,
                'responsible_user' => $responsible_user->id,
                'state' => 'created',
                'indicator' => $request->get('indicator'),
            ]);
            //dump($output->id);
            $items = json_decode($request->get('items'));
            //dd('asdasd');

            // TODO: 1° Sacamos los equipos y materiales
            //$arregloEquipMaterials = [];
            /*foreach ( $items as $item )
            {
                array_push($arregloEquipMaterials, ['equipment'=>$item->equipment_id, 'material'=>$item->material_id]);
            }*/

            //$arregloEquipoMateriales = array_unique($arregloEquipMaterials, SORT_REGULAR);

            /*foreach ( $arregloEquipoMateriales as $item )
            {
                // TODO: 2° Sacar los equipment_materials de ese equipo
                $equipment_id = $item->equipment;
                $material_id = $item->material;
                $cant_equipment = EquipmentMaterial::where('equipment_id', $equipment_id)
                    ->where('material_id', $material_id)->sum('quantity');
                if( $cant_equipment == 0 )
                {
                    $cant_equipment = EquipmentConsumable::where('equipment_id', $equipment_id)
                        ->where('material_id', $material_id)->sum('quantity');
                } else {
                    $cant_equipment = 0;
                }
                $equipment = Equipment::find($equipment_id);

                $cant_equipment_material = (float)$cant_equipment * (float)$equipment->quantity;

                // TODO: 3° Sacar las salidas de ese equipment y material
                $cant_equipment_material_salidas = OutputDetail::where('equipment_id', $equipment_id)
                    ->where('material_id', $material_id)->sum('percentage');

                // TODO: 4° Recorremos los item sumando los porcentajes de equip mat
                $cant_equipment_material_solicitado = 0;
                foreach ( $items as $item2 )
                {
                    if ( $item2->equipment_id == $equipment_id && $item2->material_id == $material_id )
                    {
                        $cant_equipment_material_solicitado += $item2->percentage;
                    }
                }

                // TODO: 5°
                if ( ($cant_equipment_material_solicitado + $cant_equipment_material_salidas) > $cant_equipment_material )
                {
                    return response()->json(['message' => 'Lo sentimos, un material ya sobrepasó la cantidad pedida en cotización.'], 422);
                }

            }*/

            /*foreach ( $arregloResumen as $item )
            {
                // Obtener la suma de las cantidades de ese material (L)
                // Obtener las salidas de esa cotizacion
                // Sumar las salidas de ese material
                // Luego sumar la cantidad de los items de ese material
                // Por ultimo sumar ambas cantidades
                // Finalmente comparar las salidas de ese material con la cant de cot
                if ( isset($quote) )
                {
                    $quantity = 0;
                    foreach ( $quote->equipments as $equipment )
                    {
                        if ( !$equipment->finished && $equipment->id == $item->equipment_id)
                        {
                            foreach ( $equipment->materials as $material )
                            {
                                // TODO: Reemplazo de materiales
                                if ( $material->replacement == 0 && $material->material_id == $item->material_id )
                                {
                                    $quantity += (float)$material->quantity*(float)$equipment->quantity;
                                }

                            }
                        }

                    }

                    $outputs_details = OutputDetail::where('quote_id', $quote->id)
                        ->where('equipment_id', $item->equipment_id)
                        ->where()
                }
            }*/

            foreach ( $items as $item )
            {
                $item_selected = Item::find($item->item);
                if ( $item_selected->state_item === 'reserved' )
                {
                    return response()->json(['message' => 'Lo sentimos, un item seleccionado ya estaba reservado para otra solicitud.'], 422);
                } else {
                    $item_selected->state_item = 'reserved';
                    $item_selected->save();

                    // TODO: Esto va a cambiar
                    $detail_output = OutputDetail::create([
                        'output_id' => $output->id,
                        'item_id' => $item_selected->id,
                        'length' => $item_selected->length,
                        'width' => $item_selected->width,
                        'price' => $item_selected->price,
                        'percentage' => $item_selected->percentage,
                        'material_id' => $item->material_id,
                        'equipment_id' => null,
                        'quote_id' => null,
                        'custom' => 0
                    ]);
                }
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar solicitud simple de area',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Solicitud de área guardada con éxito.'], 200);

    }

    public function getJsonItemsOutputSimple($output_id)
    {
        $begin = microtime(true);
        $materials = [];
        $materials_quantity = [];
        $outputDetails = OutputDetail::where('output_id', $output_id)->get();
        foreach ( $outputDetails as $key => $outputDetail )
        {
            $item = Item::with(['location', 'material'])
                ->find($outputDetail->item_id);

            $material = Material::find($outputDetail->material_id);
            if ( $material->typescrap_id != null || $material->typescrap_id != '' )
            {
                if (isset($item)) {
                    $l = 'AR:' . $item->location->area->name . '|AL:' . $item->location->warehouse->name . '|AN:' . $item->location->shelf->name . '|NIV:' . $item->location->level->name . '|CON:' . $item->location->container->name;
                    array_push($array,
                        [
                            'id' => $key + 1,
                            'material' => $item->material->full_description,
                            'id_item' => $item->id,
                            'code' => $item->code,
                            'length' => $item->length,
                            'width' => $item->width,
                            'weight' => $item->weight,
                            'price' => $item->price,
                            'percentage' => $item->percentage,
                            'location' => $l,
                            'state' => $item->state,
                            'detail_id' => $outputDetail->id
                        ]);

                } else {
                    array_push($array,
                        [
                            'id' => $key + 1,
                            'material' => $outputDetail->material->full_description,
                            'id_item' => 'Personalizado',
                            'code' => 'Personalizado',
                            'length' => $outputDetail->length,
                            'width' => $outputDetail->width,
                            'weight' => null,
                            'price' => $outputDetail->price,
                            'location' => 'Personalizado',
                            'percentage' => $outputDetail->percentage,
                            'state' => 'Personalizado',
                            'detail_id' => $outputDetail->id
                        ]);
                }
            } else {
                array_push($materials_quantity, array('material_id'=>$material->id, 'material'=>$material->full_description, 'material_complete'=>$material, 'quantity'=> (float)$item->percentage));

            }
        }

        $new_arr3 = array();
        foreach($materials_quantity as $item) {
            if(isset($new_arr3[$item['material_id']])) {
                $new_arr3[ $item['material_id']]['quantity'] += (float)$item['quantity'];
                continue;
            }

            $new_arr3[$item['material_id']] = $item;
        }

        $materials = array_values($new_arr3);

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Items de salida simple',
            'time' => $end
        ]);

        return json_encode(['materials'=>$materials]);

    }

    public function getJsonItemsOutputSimpleDevolver( $output_id )
    {
        $begin = microtime(true);
        $array = [];
        $materials = [];
        $materials_quantity = [];
        $outputDetails = OutputDetail::where('output_id', $output_id)->get();
        foreach ( $outputDetails as $key => $outputDetail )
        {
            $item = Item::with(['location', 'material'])
                ->find($outputDetail->item_id);

            $material = Material::find($outputDetail->material_id);

            if (isset($item)) {
                array_push($array,
                    [
                        'id' => $key + 1,
                        'material' => $item->material->full_description,
                        'id_item' => $item->id,
                        'code' => $item->code,
                        'state' => $item->state,
                        'detail_id' => $outputDetail->id
                    ]);

            }

        }
        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener items de salidas simple para devolver',
            'time' => $end
        ]);
        //dd($array);
        return json_encode(['array'=>$array]);
    }

    public function destroyTotalOutputSimple(Request $request)
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {

            $output = Output::find($request->get('output_id'));

            if ($output->state === 'created')
            {
                $outputDetails = OutputDetail::where('output_id', $output->id)->get();
                foreach ( $outputDetails as $outputDetail )
                {
                    if ( $outputDetail->item_id != null )
                    {
                        $item = Item::find($outputDetail->item_id);

                        $items = Item::where('code',$item->code)->get();
                        $count_items = count($items);
                        $last_item = Item::where('code',$item->code)
                            ->orderBy('created_at', 'desc')->first();
                        if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                            return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
                        } else {
                            if ($count_items>1){
                                $item->state_item = 'scraped';
                                $item->save();
                            } else {
                                $item->state_item = 'entered';
                                $item->save();
                            }
                        }
                    }

                    $outputDetail->delete();
                }
                $output->delete();
            }

            if ($output->state !== 'created')
            {
                $outputDetails = OutputDetail::where('output_id', $output->id)->get();
                foreach ( $outputDetails as $outputDetail )
                {
                    if ( $outputDetail->item_id == null )
                    {
                        $item = Item::find($outputDetail->item_id);
                        $items = Item::where('code',$item->code)->get();
                        $count_items = count($items);
                        $last_item = Item::where('code',$item->code)
                            ->orderBy('created_at', 'desc')->first();
                        if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                            return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
                        } else {
                            if ($count_items>1){
                                $item->state_item = 'scraped';
                                $item->save();
                                $material = Material::find($item->material_id);
                                $material->stock_current = $material->stock_current + $item->percentage;
                                $material->save();
                            } else {
                                $item->state_item = 'entered';
                                $item->save();
                                $material = Material::find($item->material_id);
                                $material->stock_current = $material->stock_current + $item->percentage;
                                $material->save();
                            }
                        }

                        // TODO: Dismunir el stock del material
                    }
                    /*$material_taken = MaterialTaken::where('output_detail_id', $outputDetail->id)->first();
                    if (isset( $material_taken->id ))
                    {
                        $material_taken->delete();
                    }*/
                    $outputDetail->delete();

                }
                $output->delete();
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Eliminar total Salida simple',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Eliminación total con éxito.'], 200);

    }

    public function attendOutputSimple(Request $request)
    {
        $begin = microtime(true);
        //dd($request);
        $output = Output::find($request->get('output_id'));
        DB::beginTransaction();
        try {
            $output->state = 'attended';
            $output->save();

            $outputDetails = OutputDetail::where('output_id', $output->id)->get();
            foreach ( $outputDetails as $outputDetail )
            {
                $item = Item::find($outputDetail->item_id);
                $item->state_item = 'exited';
                $item->save();
                // TODO: Dismunir el stock del material
                $material = Material::find($item->material_id);
                $material->stock_current = $material->stock_current - $item->percentage;
                $material->save();
            }

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Atender salida simple',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Solicitud de área atendida con éxito.'], 200);

    }

    public function confirmOutputSimple(Request $request)
    {
        $begin = microtime(true);
        //dd($request);
        $output = Output::find($request->get('output_id'));
        DB::beginTransaction();
        try {
            $output->state = 'confirmed';
            $output->save();

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Confirmar Salida simple',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Salida de área confirmada con éxito.'], 200);

    }

    public function confirmAllOutputSimpleAttend( Request $request )
    {
        $begin = microtime(true);
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $outputs = Output::where('state', 'attended')
                ->where('responsible_user', $user->id)
                ->get();

            //dd($outputs);
            foreach ( $outputs as $output )
            {
                $output->state = 'confirmed';
                $output->save();
            }
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Confirmar todas las solicitudes',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Salidas de área confirmadas con éxito.'], 200);

    }

    public function returnItemOutputSimpleDetail(Request $request, $id_output, $id_item)
    {
        $begin = microtime(true);
        DB::beginTransaction();
        try {
            $outputDetail = OutputDetail::find($id_output);

            $item = Item::find($id_item);
            $items = Item::where('code',$item->code)->get();
            $count_items = count($items);
            $last_item = Item::where('code',$item->code)
                ->orderBy('created_at', 'desc')->first();
            if ( $last_item->state_item === 'scraped' && $count_items>1 ) {
                return response()->json(['message' => 'No se puede eliminar. Contacte con soporte técnico.'], 422);
            } else {
                if ($count_items>1){
                    $item->state_item = 'scraped';
                    $item->save();
                    $material = Material::find($item->material_id);
                    $material->stock_current = $material->stock_current + $item->percentage;
                    $material->save();
                } else {
                    if ( $this->esCompleto($item->id) )
                    {
                        $item->state_item = 'entered';
                        $item->save();
                        $material = Material::find($item->material_id);
                        $material->stock_current = $material->stock_current + $item->percentage;
                        $material->save();
                    } else {
                        $item->state_item = 'scraped';
                        $item->save();
                        $material = Material::find($item->material_id);
                        $material->stock_current = $material->stock_current + $item->percentage;
                        $material->save();
                    }

                }
            }
            /*$material_taken = MaterialTaken::where('output_detail_id', $outputDetail->id)->first();
            if (isset( $material_taken->id ))
            {
                $material_taken->delete();
            }*/

            $outputDetail->delete();

            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Retornar item en salidas simples',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Item devuelto.'], 200);

    }

    public function getMyOutputSimple()
    {
        $begin = microtime(true);
        $user = Auth::user();
        $outputs = Output::with('requestingUser')
            ->with('responsibleUser')
            ->with('quote')
            ->where('indicator', 'ors')
            ->where('responsible_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $array = [];

        foreach ( $outputs as $output )
        {
            $user_id = $output->responsibleUser->id;
            $worker = Worker::where('user_id', $user_id)->first();
            $area = AreaWorker::find($worker->area_worker_id);

            $itemsNull = OutputDetail::where('output_id', $output->id)
                ->whereNull('item_id')->count();
            array_push($array, [
                'id' => $output->id,
                'description' => $output->execution_order,
                'request_date' => $output->request_date,
                'requesting_user' => $output->requestingUser->name,
                'responsible_user' => $output->responsibleUser->name,
                'state' => $output->state,
                'area' => ($area == null) ? 'Sin área': $area->name,
                'custom' => ($itemsNull > 0) ? true: false,
            ]);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener mis solicitudes simples',
            'time' => $end
        ]);
        //dd($outputs);
        return datatables($array)->toJson();
    }

    public function indexMyOutputSimple()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('output.index_my_output_simple', compact('permissions'));
    }

    public function editOutputSimpleDescription(Request $request)
    {
        //dd($request);
        $output = Output::find($request->get('output_id'));
        DB::beginTransaction();
        try {
            $output->execution_order = $request->get('description');
            $output->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Descripción guardada con éxito.'], 200);

    }


    public function getOutputRequestServerSide()
    {
        $begin = microtime(true);
        $outputs = Output::with('requestingUser')
            ->with('responsibleUser')
            ->with('quote')
            ->orderBy('created_at', 'desc')
            ->get();

        $array = [];

        foreach ( $outputs as $output )
        {
            $itemsNull = OutputDetail::where('output_id', $output->id)
                ->whereNull('item_id')->count();
            array_push($array, [
                'id' => $output->id,
                'execution_order' => $output->execution_order,
                'description_quote' => ($output->quote == null) ? 'No hay datos': $output->quote->description_quote,
                'request_date' => $output->request_date,
                'requesting_user' => $output->requestingUser->name,
                'responsible_user' => $output->responsibleUser->name,
                'state' => $output->state,
                'custom' => ($itemsNull > 0) ? true: false,
            ]);
        }

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Obtener Solicitudes de salida',
            'time' => $end
        ]);
        //dd($outputs);
        return datatables($array)->toJson();
    }

    public function indexOutputRequestServerside()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('output.index_output_request_server_side', compact('permissions'));
    }

    public function deleteOutputMaterialQuantity( $output, $material, $quantity )
    {

    }
}
