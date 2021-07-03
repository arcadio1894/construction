<?php

namespace App\Http\Controllers;

use App\Area;
use App\Container;
use App\Http\Requests\StoreTransferRequest;
use App\Item;
use App\Level;
use App\Location;
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
                $location_origin = $item_selected->location;

                TransferDetail::create([
                    'transfer_id' => $transfer->id,
                    'item_id' => $item->item,
                    'origin_location' => $location_origin->id
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

    public function show(Transfer $transfer)
    {
        //
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
