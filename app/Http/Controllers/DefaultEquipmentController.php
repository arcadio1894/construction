<?php

namespace App\Http\Controllers;

use App\CategoryEquipment;
use App\DefaultEquipment;

use App\Http\Requests\StoreDefaultEquipmentRequest;

use App\DefaultEquipmentMaterial;
use App\DefaultEquipmentConsumable;
use App\DefaultEquipmentWorkforce;
use App\DefaultEquipmentTurnstile;
use App\DefaultEquipmentWorkday;

use App\Material;
use App\UnitMeasure;
use App\Workforce;
use App\Audit;
use App\PorcentageQuote;


use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefaultEquipmentController extends Controller
{
    public function index($category_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $category = CategoryEquipment::find($category_id);
        return view('defaultEquipment.index', compact('permissions', 'category'));
    }

    public function create($category_id)
    {   $begin = microtime(true);
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
        $category = CategoryEquipment::find($category_id);

        $defaultConsumable = '(*)';
        $consumables = Material::with('unitMeasure')->where('category_id', 2)->whereConsumable('description',$defaultConsumable)->get();

        $unitMeasures = UnitMeasure::all();

        $workforces = Workforce::with('unitMeasure')->get();

        $utility = PorcentageQuote::where('name', 'utility')->first();
        $rent = PorcentageQuote::where('name', 'rent')->first();
        $letter = PorcentageQuote::where('name', 'letter')->first();

        $end = microtime(true) - $begin;

        Audit::create([
            'user_id' => Auth::user()->id,
            'action' => 'Crear equipo por defecto VISTA',
            'time' => $end
        ]);

        return view('defaultEquipment.create', compact('permissions', 'category', 'consumables' ,'unitMeasures' ,'workforces', 'utility', 'rent', 'letter'));
    }

    public function store(StoreDefaultEquipmentRequest $request)
    {
        $begin = microtime(true);
        //dump($request);
        //dd();
        //dump($request->descplanos);
        //dump($request->planos);
        //dd();
        $validated = $request->validated();
   

        DB::beginTransaction();
        try {
            $equipments = json_decode($request->get('equipments'));
            //dump($equipments);
            //dd();

            for ( $i=0; $i<sizeof($equipments); $i++ )
            {
                $equipment = DefaultEquipment::create([
                    //'quote_id' => $quote->id,
                    'description' =>$equipments[$i]->nameequipment,
                    'large' => $equipments[$i]->largeequipment,
                    'width' => $equipments[$i]->widthequipment,
                    'high' => $equipments[$i]->highequipment,
                    'category_equipment_id' => $equipments[$i]->categoryequipmentid,
                    'details' => ($equipments[$i]->detail == "" || $equipments[$i]->detail == null) ? '':$equipments[$i]->detail,
                    //'quantity' => $equipments[$i]->quantity,
                    'utility' => $equipments[$i]->utility,
                    'letter' => $equipments[$i]->letter,
                    'rent' => $equipments[$i]->rent,
                    //'total' => $equipments[$i]->total
                ]);

                //$totalMaterial = 0;

                //$totalConsumable = 0;

                //$totalWorkforces = 0;

                //$totalTornos = 0;

                //$totalDias = 0;

                $materials = $equipments[$i]->materials;

                $consumables = $equipments[$i]->consumables;

                $workforces = $equipments[$i]->workforces;

                $tornos = $equipments[$i]->tornos;

                $dias = $equipments[$i]->dias;
                
                      

                for ( $j=0; $j<sizeof($materials); $j++ )
                {
                    $equipmentMaterial = DefaultEquipmentMaterial::create([
                        'default_equipment_id' => $equipment->id,
                        'material_id' => $materials[$j]->material->id,
                        'quantity' => (float) $materials[$j]->quantity,
                        'length' => (float) ($materials[$j]->length == '') ? 0: $materials[$j]->length,
                        'width' => (float) ($materials[$j]->width == '') ? 0: $materials[$j]->width,
                        'percentage' => (float) $materials[$j]->quantity,
                        'unit_price' => (float) $materials[$j]->material->unit_price,
                        'total_price' => (float) $materials[$j]->quantity*(float) $materials[$j]->material->unit_price,
                        //'state' => ($materials[$j]->quantity > $materials[$j]->material->stock_current) ? 'Falta comprar':'En compra',
                        //'availability' => ($materials[$j]->quantity > $materials[$j]->material->stock_current) ? 'Agotado':'Completo',
                    ]);

                    //$totalMaterial += $equipmentMaterial->total;
                }

                for ( $k=0; $k<sizeof($consumables); $k++ )
                {
                    $material = Material::find($consumables[$k]->id);

                    $equipmentConsumable = DefaultEquipmentConsumable::create([
                        'default_equipment_id' => $equipment->id,
                        'material_id' => $consumables[$k]->id,
                        'quantity' => (float) $consumables[$k]->quantity,
                        'unit_price' => (float) $consumables[$k]->price,
                        'total_price' => (float) $consumables[$k]->total,
                        //'state' => ((float) $consumables[$k]->quantity > $material->stock_current) ? 'Falta comprar':'En compra',
                        //'availability' => ((float) $consumables[$k]->quantity > $material->stock_current) ? 'Agotado':'Completo',
                    ]);

                    //$totalConsumable += $equipmentConsumable->total;
                }

                for ( $w=0; $w<sizeof($workforces); $w++ )
                {
                    $equipmentWorkforce = DefaultEquipmentWorkforce::create([
                        'default_equipment_id' => $equipment->id,
                        'description' => $workforces[$w]->description,
                        'quantity' => (float) $workforces[$w]->quantity,
                        'unit_price' => (float) $workforces[$w]->price,
                        'total_price' => (float) $workforces[$w]->total,
                        'unit' => $workforces[$w]->unit,
                    ]);

                    //$totalWorkforces += $equipmentWorkforce->total;
                }

                for ( $r=0; $r<sizeof($tornos); $r++ )
                {
                    $equipmenttornos = DefaultEquipmentTurnstile::create([
                        'default_equipment_id' => $equipment->id,
                        'description' => $tornos[$r]->description,
                        'quantity' => (float) $tornos[$r]->quantity,
                        'unit_price' => (float) $tornos[$r]->price,
                        'total_price' => (float) $tornos[$r]->total
                    ]);

                    //$totalTornos += $equipmenttornos->total;
                }

                for ( $d=0; $d<sizeof($dias); $d++ )
                {
                    $equipmentdias = DefaultEquipmentWorkday::create([
                        'default_equipment_id' => $equipment->id,
                        'description' => $dias[$d]->description,
                        'quantityPerson' => (float) $dias[$d]->quantity,
                        'hoursPerPerson' => (float) $dias[$d]->hours,
                        'pricePerHour' => (float) $dias[$d]->price,
                        'total_price' => (float) $dias[$d]->total
                    ]);
                    //dump($dias[$d]->description);
                    //dump($equipmentdias);
                    //dd($equipmentdias);

                    //$totalDias += $equipmentdias->total;
                }

                //$totalEquipo = (($totalMaterial + $totalConsumable + $totalWorkforces + $totalTornos) * (float)$equipment->quantity)+$totalDias;
                //$totalEquipmentU = $totalEquipo*(($equipment->utility/100)+1);
                //$totalEquipmentL = $totalEquipmentU*(($equipment->letter/100)+1);
                //$totalEquipmentR = $totalEquipmentL*(($equipment->rent/100)+1);

                //$totalQuote += $totalEquipmentR;

                //$equipment->total = $totalEquipo;

                $equipment->save();
            }

            // Crear notificacion
            /*
            $notification = Notification::create([
                'content' => $quote->code.' creada por '.Auth::user()->name,
                'reason_for_creation' => 'create_quote',
                'user_id' => Auth::user()->id,
                'url_go' => route('quote.edit', $quote->id)
            ]);

            // Roles adecuados para recibir esta notificación admin, logistica
            $users = User::role(['admin', 'principal' , 'logistic'])->get();
            foreach ( $users as $user )
            {
                if ( $user->id != Auth::user()->id )
                {
                    foreach ( $user->roles as $role )
                    {
                        NotificationUser::create([
                            'notification_id' => $notification->id,
                            'role_id' => $role->id,
                            'user_id' => $user->id,
                            'read' => false,
                            'date_read' => null,
                            'date_delete' => null
                        ]);
                    }
                }
            }
            */
            $end = microtime(true) - $begin;

            Audit::create([
                'user_id' => Auth::user()->id,
                'action' => 'Guardar equipo por defecto.',
                'time' => $end
            ]);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Equipo por defecto guardado con éxito.'], 200);
    }

    public function show(DefaultEquipment $defaultEquipment)
    {
        //
    }

    public function edit(DefaultEquipment $defaultEquipment)
    {
        //
    }

    public function update(Request $request, DefaultEquipment $defaultEquipment)
    {
        //
    }

    public function destroy(DefaultEquipment $defaultEquipment)
    {
        //
    }

    public function getDataDefaultEquipments(Request $request, $pageNumber = 1)
    {
        $perPage = 8;
        $categoryEquipmentid = $request -> input('category_Equipment_id');
        $largeDefaultEquipment = $request->input('large_Default_Equipment');
        $widthDefaultEquipment = $request->input('width_Default_Equipment');
        $highDefaultEquipment = $request->input('high_Default_Equipment');

        $query = DefaultEquipment::where('category_equipment_id',$categoryEquipmentid )
        ->orderBy('created_at', 'DESC');

        // Aplicar filtros si se proporcionan
        if ($largeDefaultEquipment) {
            $query->where('large', $largeDefaultEquipment);

        }

        if ($widthDefaultEquipment) {
            $query->where('width', $widthDefaultEquipment);

        }

        if ($highDefaultEquipment) {
            $query->where('high', $highDefaultEquipment);

        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $operations = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $arrayDefaultEquipments = [];

        foreach ( $operations as $operation )
        {

            array_push($arrayDefaultEquipments, [
                "id" => $operation->id,
                "description" => $operation->description,
                "large" => $operation->large,
                "width" => $operation->width,
                "high" => $operation->high,
                "details" => $operation->details,

            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $arrayDefaultEquipments, 'pagination' => $pagination];
    }
}