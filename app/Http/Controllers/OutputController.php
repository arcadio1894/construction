<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestOutputRequest;
use App\Item;
use App\Material;
use App\Output;
use App\OutputDetail;
use App\Quote;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutputController extends Controller
{
    public function indexOutputRequest()
    {
        return view('output.index_output_request');
    }

    public function indexOutputs()
    {
        return view('output.index_output');
    }

    public function createOutputRequest()
    {
        return view('output.create_output_request');
    }

    public function createOutputRequestOrder($id_quote)
    {
        $quote = Quote::with('equipments')->find($id_quote);

        $materials = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->materials as $material )
            {
                array_push($materials, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity));

            }

        }

        /*foreach ( $materials as $key => $material )
        {
            dump($key);
            dump($material['quantity']);
            dump($material['material_complete']->id);
        }*/

        return view('output.create_output_request_order', compact('materials', 'quote'));
    }

    public function createOutputRequestOrderExtra($id_quote)
    {
        $quote = Quote::with('equipments')->find($id_quote);

        $materials = [];

        foreach ( $quote->equipments as $equipment )
        {
            foreach ( $equipment->materials as $material )
            {
                array_push($materials, array('material_id'=>$material->material_id, 'material'=>$material->material->full_description, 'material_complete'=>$material->material, 'quantity'=> (float)$material->quantity));

            }

        }

        return view('output.create_output_request_order_extra', compact('materials', 'quote'));
    }

    public function getOutputRequest()
    {
        $outputs = Output::with('requestingUser')
            ->with('responsibleUser')
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
                    'price' => $item->price,
                    'location' => $l,
                    'state' => $item->state,
                ]);

        }

        //dd($array);
        return json_encode($array);
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
            $responsible_user = User::where('name', $request->get('responsible_user'))->first();
            $output = Output::create([
                'execution_order' => $request->get('execution_order'),
                'request_date' => Carbon::createFromFormat( 'd/m/Y', ($request->get('request_date')) ),
                'requesting_user' => $requesting_user->id,
                'responsible_user' => $responsible_user->id,
                'state' => 'created',
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
        return response()->json(['message' => 'Solicitud de salida guardada con éxito.'], 200);

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
                    $item->state_item = 'entered';
                    $item->save();
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
                    $item->state_item = 'entered';
                    $item->save();

                    // TODO: Dismunir el stock del material
                    $material = Material::find($item->material_id);
                    $material->stock_current = $material->stock_current + $item->percentage;
                    $material->save();

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
                $item->state_item = 'entered';
                $item->save();
                $outputDetail->delete();
            }

            if ($output->state !== 'created')
            {
                $outputDetail = OutputDetail::where('output_id', $output->id)
                    ->where('item_id', $id_item)->first();

                $item = Item::find($outputDetail->item_id);
                $item->state_item = 'entered';
                $item->save();

                // TODO: Dismunir el stock del material
                $material = Material::find($item->material_id);
                $material->stock_current = $material->stock_current + $item->percentage;
                $material->save();

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
