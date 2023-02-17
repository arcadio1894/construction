<?php

namespace App\Http\Controllers;

use App\Area;
use App\Container;

use App\Http\Requests\StoreTransferRequest;
use App\Item;
use App\Level;
use App\Location;
use App\Material;
use App\Position;
use App\Shelf;
use App\Transfer;
use App\TransferDetail;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function index()
    {
        return view('transfer.index');
    }

    public function create()
    {
        $areas = Area::all();
        return view('transfer.create', compact('areas'));
    }

    public function store(StoreTransferRequest $request)
    {
        //dd($request);
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $area_id = $request->get('area_id');
            $warehouse_id = $request->get('warehouse_id');
            $shelf_id = $request->get('shelf_id');
            $level_id = $request->get('level_id');
            $container_id = $request->get('container_id');
            $position_id = $request->get('position_id');

            $location = Location::where('area_id', $area_id)
                ->where('warehouse_id', $warehouse_id)
                ->where('shelf_id', $shelf_id)
                ->where('level_id', $level_id)
                ->where('container_id', $container_id)
                ->where('position_id', $position_id)->first();

            $transfer = Transfer::create([
                'code' => 'nn',
                'destination_location' => $location->id,
                'state' => 'created',
            ]);

            $length = 5;
            $string = $transfer->id;
            $codeTransfer = 'T-'.str_pad($string,$length,"0", STR_PAD_LEFT);
            $transfer->code = $codeTransfer;
            $transfer->save();

            $items = json_decode($request->get('items'));

            foreach ( $items as $item )
            {
                $item_selected = Item::find($item->item);

                TransferDetail::create([
                    'transfer_id' => $transfer->id,
                    'item_id' => $item->item,
                    'origin_location' => $item_selected->location_id
                ]);

                // MOdificar la localización
                $item_selected->location_id = $location->id;
                $item_selected->save();

            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Transferencia guardada con éxito.'], 200);
    }

    public function show($transfer_id)
    {
        $transfer = Transfer::find($transfer_id);

        return view('transfer.show', compact('transfer'));

    }

    public function getShowTransfer($transfer_id)
    {
        $transfer = Transfer::find($transfer_id);

        $details = TransferDetail::where('transfer_id', $transfer_id)->get();

        $lD = 'AR:'.$transfer->destinationLocation->area->name.'|AL:'.$transfer->destinationLocation->warehouse->name.'|AN:'.$transfer->destinationLocation->shelf->name.'|NIV:'.$transfer->destinationLocation->level->name.'|CON:'.$transfer->destinationLocation->container->name.'|POS:'.$transfer->destinationLocation->position->name;

        $array = [];

        foreach ( $details as $detail )
        {
            $l = 'AR:'.$detail->originLocation->area->name.'|AL:'.$detail->originLocation->warehouse->name.'|AN:'.$detail->originLocation->shelf->name.'|NIV:'.$detail->originLocation->level->name.'|CON:'.$detail->originLocation->container->name.'|POS:'.$detail->originLocation->position->name;

            $item = Item::find($detail->item_id);
            $material = Material::find($item->material_id);
            array_push($array,
                [
                    'id'=> $detail->id,
                    'locationOrigin' => substr($l,0,30).'...',
                    'locationDestination' => substr($lD,0,30).'...',
                    'material_id' => $material->id,
                    'material' => $material->full_description,
                    'code' => $item->code,
                    'length' => $item->length,
                    'width' => $item->width,
                    'state_item' => $item->state_item,
                    'percentage' => (float)$item->percentage,
                ]);
        }

        return datatables($array)->toJson();
    }

    public function edit(Transfer $transfer)
    {
        //
    }

    public function update(Request $request, Transfer $transfer)
    {
        //
    }

    public function destroy(Transfer $transfer)
    {
        //
    }

    public function getWarehouse($id)
    {
        $warehouses = Warehouse::where('area_id', $id)->get();
        $array = [];
        foreach ( $warehouses as $warehouse )
        {
            array_push($array, ['id'=> $warehouse->id, 'warehouse' => $warehouse->name]);
        }

        return $array;
    }

    public function getShelf($id)
    {
        $shelves = Shelf::where('warehouse_id', $id)->get();
        $array = [];
        foreach ( $shelves as $shelf )
        {
            array_push($array, ['id'=> $shelf->id, 'shelf' => $shelf->name]);
        }

        return $array;
    }

    public function getLevel($id)
    {
        $levels = Level::where('shelf_id', $id)->get();
        $array = [];
        foreach ( $levels as $level )
        {
            array_push($array, ['id'=> $level->id, 'level' => $level->name]);
        }

        return $array;
    }

    public function getContainer($id)
    {
        $containers = Container::where('level_id', $id)->get();
        $array = [];
        foreach ( $containers as $container )
        {
            array_push($array, ['id'=> $container->id, 'container' => $container->name]);
        }

        return $array;
    }

    public function getPosition($id)
    {
        $positions = Position::where('container_id', $id)->get();
        $array = [];
        foreach ( $positions as $position )
        {
            array_push($array, ['id'=> $position->id, 'position' => $position->name]);
        }

        return $array;
    }


    public function getTransfers()
    {
        $transfers = Transfer::with(['destinationLocation' => function ($query) {
            $query->with(['area', 'warehouse', 'shelf', 'level', 'container', 'position']);
        }])->get();

        //dd(datatables($transfers)->toJson());

        return datatables($transfers)->toJson();
    }

}
