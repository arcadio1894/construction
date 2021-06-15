<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestOutputRequest;
use App\Item;
use App\Output;
use App\OutputDetail;
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
                    'material' => $item->material->description,
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
                // TODO: Dismunir el stock del producto
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
                $item_selected->state_item = 'reserved';
                $item_selected->save();

                $detail_output = OutputDetail::create([
                    'output_id' => $output->id,
                    'item_id' => $item->item,
                ]);

            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Solicitud de salida guardada con éxito.'], 200);

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
