<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestOutputRequest;
use App\Item;
use App\Material;
use App\MaterialTaken;
use App\Output;
use App\OutputDetail;
use App\Quote;
use App\User;
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
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quote = Quote::with('equipments')->find($id_quote);

        $materials_quantity = [];
        $materials = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->materials as $material )
            {
                array_push($materials_quantity, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));

            }
            foreach ( $equipment->consumables as $consumable )
            {
                if (isset( $consumable->material->subcategory ))
                {
                    if ( $consumable->material->category_id == 2 && trim($consumable->material->subcategory->name) === 'MIXTO' )
                    {
                        array_push($materials_quantity, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

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

        /*foreach ( $materials as $key => $material )
        {
            dump($key);
            dump($material['quantity']);
            dump($material['material_complete']->id);
        }*/

        //dump($materials);
        //dump($consumables);
        $users = User::all();

        return view('output.create_output_request_order', compact('users','permissions', 'consumables', 'materials', 'quote'));
    }

    public function createOutputRequestOrderExtra($id_quote)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quote = Quote::with('equipments')->find($id_quote);

        $materials = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->materials as $material )
            {
                array_push($materials, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity*(float)$equipment->quantity));

            }
            foreach ( $equipment->consumables as $consumable )
            {
                if (isset( $consumable->material->subcategory ))
                {
                    if ( $consumable->material->category_id == 2 && trim($consumable->material->subcategory->name) === 'MIXTO' )
                    {
                        array_push($materials, array('material_id'=>$consumable->material_id, 'material'=>$consumable->material->full_description, 'material_complete'=>$consumable->material, 'quantity'=> (float)$consumable->quantity*(float)$equipment->quantity));

                    }
                }

            }

        }

        $users = User::all();

        return view('output.create_output_request_order_extra', compact('users','permissions', 'materials', 'quote'));
    }

    public function getOutputRequest()
    {
        $outputs = Output::with('requestingUser')
            ->with('responsibleUser')
            ->with('quote')
            ->orderBy('created_at', 'desc')
            ->get();

        //dd($outputs);
        return datatables($outputs)->toJson();
    }

    public function getJsonItemsOutputRequest( $output_id )
    {
        $array = [];
        $outputDetails = OutputDetail::where('output_id', $output_id)->get();
        foreach ( $outputDetails as $key => $outputDetail )
        {
            $item = Item::with(['location', 'material'])
                ->find($outputDetail->item_id);

            $l = 'AR:'.$item->location->area->name.'|AL:'.$item->location->warehouse->name.'|AN:'.$item->location->shelf->name.'|NIV:'.$item->location->level->name.'|CON:'.$item->location->container->name;
            array_push($array,
                [
                    'id'=> $key+1,
                    'material' => $item->material->full_description,
                    'id_item' => $item->id,
                    'code' => $item->code,
                    'length' => $item->length,
                    'width' => $item->width,
                    'weight' => $item->weight,
                    'price' => $item->material->unit_price,
                    'location' => $l,
                    'state' => $item->state,
                ]);

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


        //dd($array);
        return json_encode(['array'=>$array, 'consumables'=>$consumables]);
    }

    public function attendOutputRequest(Request $request)
    {
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

                if (isset($quote))
                {
                    MaterialTaken::create([
                        'material_id' => $material->id,
                        'quantity_request' => $item->percentage,
                        'quote_id' => $quote->id,
                        'output_id' => $output->id
                    ]);
                }

            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Solicitud de salida atendida con éxito.'], 200);

    }

    public function confirmOutputRequest(Request $request)
    {
        //dd($request);
        $output = Output::find($request->get('output_id'));
        DB::beginTransaction();
        try {
            $output->state = 'confirmed';
            $output->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Salida confirmada con éxito.'], 200);

    }

    public function storeOutputRequest(StoreRequestOutputRequest $request)
    {
        //dd($request);
        $validated = $request->validated();
        DB::beginTransaction();
        try {
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

            $items = json_decode($request->get('items'));

            foreach ( $items as $item )
            {
                $item_selected = Item::find($item->item);
                if ( $item_selected->state_item === 'reserved' )
                {
                    return response()->json(['message' => 'Lo sentimos, un item seleccionado ya estaba reservado para otra solicitud.'], 422);
                } else {
                    $item_selected->state_item = 'reserved';
                    $item_selected->save();

                    $detail_output = OutputDetail::create([
                        'output_id' => $output->id,
                        'item_id' => $item->item,
                    ]);
                }


            }
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

        DB::beginTransaction();
        try {

            $output = Output::find($request->get('output_id'));

            if ($output->state === 'created')
            {
                $outputDetails = OutputDetail::where('output_id', $output->id)->get();
                foreach ( $outputDetails as $outputDetail )
                {
                    $item = Item::find($outputDetail->item_id);
                    $items = Item::where('code',$item->code)->get();
                    $count_items = count($items);
                    $last_item = Item::where('code',$item->code)
                        ->orderBy('created_at', 'desc')->first();
                    if ( $last_item->state_item === 'scraped' ) {
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
                }
                $output->delete();
            }

            if ($output->state !== 'created')
            {
                $outputDetails = OutputDetail::where('output_id', $output->id)->get();
                foreach ( $outputDetails as $outputDetail )
                {
                    $item = Item::find($outputDetail->item_id);
                    $items = Item::where('code',$item->code)->get();
                    $count_items = count($items);
                    $last_item = Item::where('code',$item->code)
                        ->orderBy('created_at', 'desc')->first();
                    if ( $last_item->state_item === 'scraped' ) {
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

                    $outputDetail->delete();

                }
                $output->delete();
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Eliminación total con éxito.'], 200);

    }

    public function destroyPartialOutputRequest(Request $request, $id_output, $id_item)
    {
        //dump($request);

        DB::beginTransaction();
        try {

            $output = Output::find($id_output);

            if ($output->state === 'created')
            {
                $outputDetail = OutputDetail::where('output_id', $output->id)
                    ->where('item_id', $id_item)->first();

                $item = Item::find($outputDetail->item_id);
                $items = Item::where('code',$item->code)->get();
                $count_items = count($items);
                $last_item = Item::where('code',$item->code)
                    ->orderBy('created_at', 'desc')->first();
                if ( $last_item->state_item === 'scraped' ) {
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
            }

            if ($output->state !== 'created')
            {
                $outputDetail = OutputDetail::where('output_id', $output->id)
                    ->where('item_id', $id_item)->first();

                $item = Item::find($outputDetail->item_id);
                $items = Item::where('code',$item->code)->get();
                $count_items = count($items);
                $last_item = Item::where('code',$item->code)
                    ->orderBy('created_at', 'desc')->first();
                if ( $last_item->state_item === 'scraped' ) {
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

                $outputDetail->delete();

            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Eliminación del item con éxito.'], 200);

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
}
