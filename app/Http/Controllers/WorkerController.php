<?php

namespace App\Http\Controllers;

use App\CivilStatus;
use App\Contract;
use App\PensionSystem;
use App\User;
use App\Work;
use App\Worker;
use App\WorkFunction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('worker.index', compact('permissions'));
    }

    public function getWorkers()
    {
        $workers = Worker::where('enable', 1)->get();
        $arrayWorkers = [];

        foreach ( $workers as $worker )
        {
            array_push( $arrayWorkers, [
                'id' => $worker->id,
                'first_name' => $worker->first_name,
                'last_name' => $worker->last_name,
                'personal_address' => $worker->personal_address,
                'birthplace' => ($worker->birthplace == null) ? '':$worker->birthplace->format('d/m/Y'),
                'age' => ($worker->birthplace == null) ? '': Carbon::parse($worker->birthplace)->age,
                'phone' => $worker->phone,
                'email' => $worker->email,
                'level_school' => $worker->level_school,
                'image' => $worker->image,
                'dni' => $worker->dni,
                'admission_date' => ($worker->admission_date == null) ? '': $worker->admission_date->format('d/m/Y'),
                'num_children' => $worker->num_children,
                'daily_salary' => $worker->daily_salary,
                'monthly_salary' => $worker->monthly_salary,
                'pension' => $worker->pension,
                'gender' => $worker->gender,
                'essalud' => $worker->essalud,
                'assign_family' => $worker->assign_family,
                'five_category' => $worker->five_category,
                'termination_date' => ($worker->termination_date == null) ? '':$worker->termination_date->format('d/m/Y'),
                'observation' => $worker->observation,
                'contract' => ($worker->contract_id == null) ? '': $worker->contract->code,
                'civil_status' => ($worker->civil_status_id == null) ? '': $worker->civil_status->description,
                'work_function' => ($worker->work_function_id == null) ? '': $worker->work_function->description,
                'pension_system' => ($worker->pension_system_id == null) ? '': $worker->pension_system->description,
            ] );
        }

        return datatables($arrayWorkers)->toJson();
    }

    public function create()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $value_assign_family = 102.50;
        $value_essalud = 9;

        $civil_statuses = CivilStatus::select('id', 'description')->get();
        $work_functions = WorkFunction::select('id', 'description')->get();
        $pension_systems = PensionSystem::select('id', 'description', 'percentage')->get();

        return view('worker.create', compact('value_essalud','value_assign_family','permissions','civil_statuses', 'work_functions', 'pension_systems'));
    }

    public function store(Request $request)
    {
        //dd($request);

        DB::beginTransaction();
        try {

            // Creamos el email con el formato mapellido@sermeind.com
            $nombres = $request->get('first_name');
            $apellidos = $request->get('last_name');

            $primeraLetraNombres = strtolower(substr($nombres,0,1));
            $pos = strpos($apellidos, ' ');

            $primerApellido = '';

            if ( $pos !== false )
            {
                $primerApellido = strtolower(substr($apellidos,0,$pos));
            }

            // Creamos al usuario
            $user = User::create([
                'name' => $request->get('first_name').' '.$request->get('last_name'),
                'email' => $primeraLetraNombres.$primerApellido.'@sermeind.com.pe',
                'password' => bcrypt('$ermeind2021'),
                'image' => 'no_image.png'
            ]);

            $user->assignRole('worker');

            // Creamos el trabajador
            $worker = Worker::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'personal_address' => $request->get('personal_address'),
                'birthplace' => ($request->get('birthplace') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('birthplace')) : null,
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'level_school' => $request->get('level_school'),
                'image' => 'no_image.png',
                'dni' => $request->get('dni'),
                'admission_date' => ($request->get('admission_date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('admission_date')) : null,
                'num_children'=> $request->get('num_children'),
                'daily_salary' => $request->get('daily_salary'),
                'monthly_salary' => $request->get('monthly_salary'),
                'pension' => $request->get('pension'),
                'gender' => $request->get('gender'),
                'essalud' => $request->get('essalud'),
                'assign_family' => $request->get('assign_family'),
                'five_category' => $request->get('five_category'),
                'termination_date' => ($request->get('termination_date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('termination_date')) : null,
                'observation' => $request->get('observation'),
                'contract_id' => ($request->get('contract') == 0) ? null: $request->get('contract'),
                'user_id' => $user->id,
                'civil_status_id' => ($request->get('civil_status') == 0) ? null: $request->get('civil_status'),
                'work_function_id' => ($request->get('work_function') == 0) ? null: $request->get('work_function'),
                'pension_system_id' => ($request->get('pension_system') == 0) ? null: $request->get('pension_system'),
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Colaborador registrado con éxito.'], 200);

    }

    public function show(Worker $worker)
    {
        //
    }

    public function edit($id)
    {
        $worker = Worker::find($id);

        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $value_assign_family = 102.50;
        $value_essalud = 9;

        $civil_statuses = CivilStatus::select('id', 'description')->get();
        $work_functions = WorkFunction::select('id', 'description')->get();
        $pension_systems = PensionSystem::select('id', 'description', 'percentage')->get();
        $contracts = Contract::select('id', 'code')->get();

        return view('worker.edit', compact('value_essalud','value_assign_family','permissions','civil_statuses', 'work_functions', 'pension_systems', 'contracts', 'worker'));

    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            // Modificamos el trabajador
            $worker = Worker::find($id);
            $worker->first_name = $request->get('first_name');
            $worker->last_name = $request->get('last_name');
            $worker->personal_address = $request->get('personal_address');
            $worker->birthplace = ($request->get('birthplace') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('birthplace')) : null;
            $worker->phone = $request->get('phone');
            $worker->email = $request->get('email');
            $worker->level_school = $request->get('level_school');
            $worker->dni = $request->get('dni');
            $worker->admission_date = ($request->get('admission_date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('admission_date')) : null;
            $worker->num_children = $request->get('num_children');
            $worker->daily_salary = $request->get('daily_salary');
            $worker->monthly_salary = $request->get('monthly_salary');
            $worker->pension = $request->get('pension');
            $worker->gender = $request->get('gender');
            $worker->essalud = $request->get('essalud');
            $worker->assign_family = $request->get('assign_family');
            $worker->five_category = $request->get('five_category');
            $worker->termination_date = ($request->get('termination_date') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('termination_date')) : null;
            $worker->observation = $request->get('observation');
            $worker->contract_id = ($request->get('contract') == 0) ? null: $request->get('contract');
            $worker->civil_status_id = ($request->get('civil_status') == 0) ? null: $request->get('civil_status');
            $worker->work_function_id = ($request->get('work_function') == 0) ? null: $request->get('work_function');
            $worker->pension_system_id = ($request->get('pension_system') == 0) ? null: $request->get('pension_system');
            $worker->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Colaborador actualizado con éxito.'], 200);

    }

    public function destroy($worker_id)
    {
        DB::beginTransaction();
        try {

            $worker = Worker::find($worker_id);

            $user = User::where('id',$worker->user_id )->first();

            if ( !is_null($user) )
            {
                $user->enable = false;
                $user->save();
            }

            $worker->enable = false;
            $worker->save();
            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Colaborador inhabilitado con éxito.'], 200);

    }

    public function enable($worker_id)
    {
        DB::beginTransaction();
        try {

            $worker = Worker::find($worker_id);

            $user = User::where('id',$worker->user_id )->first();

            if ( !is_null($user) )
            {
                $user->enable = true;
                $user->save();
            }

            $worker->enable = true;
            $worker->save();
            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Colaborador habilitado con éxito.'], 200);

    }

    public function indexEnable()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('worker.indexEnable', compact('permissions'));
    }

    public function getWorkersEnable()
    {
        $workers = Worker::where('enable', 0)->get();
        $arrayWorkers = [];

        foreach ( $workers as $worker )
        {
            array_push( $arrayWorkers, [
                'id' => $worker->id,
                'first_name' => $worker->first_name,
                'last_name' => $worker->last_name,
                'personal_address' => $worker->personal_address,
                'birthplace' => ($worker->birthplace == null) ? '':$worker->birthplace->format('d/m/Y'),
                'age' => ($worker->birthplace == null) ? '': Carbon::parse($worker->birthplace)->age,
                'phone' => $worker->phone,
                'email' => $worker->email,
                'level_school' => $worker->level_school,
                'image' => $worker->image,
                'dni' => $worker->dni,
                'admission_date' => ($worker->admission_date == null) ? '': $worker->admission_date->format('d/m/Y'),
                'num_children' => $worker->num_children,
                'daily_salary' => $worker->daily_salary,
                'monthly_salary' => $worker->monthly_salary,
                'pension' => $worker->pension,
                'gender' => $worker->gender,
                'essalud' => $worker->essalud,
                'assign_family' => $worker->assign_family,
                'five_category' => $worker->five_category,
                'termination_date' => ($worker->termination_date == null) ? '':$worker->termination_date->format('d/m/Y'),
                'observation' => $worker->observation,
                'contract' => ($worker->contract_id == null) ? '': $worker->contract->code,
                'civil_status' => ($worker->civil_status_id == null) ? '': $worker->civil_status->description,
                'work_function' => ($worker->work_function_id == null) ? '': $worker->work_function->description,
                'pension_system' => ($worker->pension_system_id == null) ? '': $worker->pension_system->description,
            ] );
        }

        return datatables($arrayWorkers)->toJson();
    }
}
