<?php

namespace App\Http\Controllers;

use App\FollowMaterial;
use App\OrderPurchase;
use App\OrderPurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowMaterialController extends Controller
{
    public function getFollowMaterial($material_id)
    {
        $follow = FollowMaterial::where('material_id', $material_id)
            ->where('user_id', Auth::user()->id)->first();

        return json_encode($follow);
    }

    public function followMaterial($material_id)
    {
        DB::beginTransaction();
        try {
            $follow = FollowMaterial::create([
                'material_id' => $material_id,
                'user_id' => Auth::user()->id,
                'state' => 'stand_by'
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ahora esta siguiendo al material.'], 200);
    }

    public function unfollowMaterial($material_id)
    {
        DB::beginTransaction();
        try {
            $follow = FollowMaterial::where('material_id', $material_id)
                ->where('user_id', Auth::user()->id)
                ->first();
            $follow->delete();
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ahora ya no sigue al material.'], 200);
    }

    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('follow.index', compact('permissions'));

    }

    public function getJsonFollowMaterials()
    {
        $follows = FollowMaterial::with('material')
            ->where('user_id', Auth::user()->id)
            ->get();
        $array = [];

        foreach ( $follows as $follow )
        {
            $array_orders = [];
            $array_dates = [];
            $details = OrderPurchaseDetail::with('order_purchase')
                ->where('material_id', $follow->material_id)
                ->get();
            foreach ( $details as $detail )
            {
                array_push($array_orders, $detail->order_purchase->code);
                array_push($array_dates, $detail->order_purchase->date_arrival);
            }
            $codes = array_values(array_unique($array_orders));
            $dates = array_values(array_unique($array_dates));

            array_push($array, [
                'id' => $follow->id,
                'code' => $follow->material->code,
                'material' => $follow->material->full_description,
                'stock' => $follow->material->stock_current,
                'state' => $follow->state,
                'dates' => $dates,
                'orders' => $codes
            ]);
        }

        return datatables($array)->toJson();
    }

    public function unFollowMaterialUser($follow_id)
    {
        DB::beginTransaction();
        try {
            $follow = FollowMaterial::find($follow_id);
            $follow->delete();
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Ahora ya no sigue al material.'], 200);

    }
}