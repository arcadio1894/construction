<?php

namespace App\Http\Controllers;

use App\CivilStatus;
use App\Contract;
use App\PensionSystem;
use App\Worker;
use App\WorkFunction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $workers = Worker::all();
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
        $contracts = Contract::select('id', 'code')->get();

        return view('worker.create', compact('value_essalud','value_assign_family','permissions','civil_statuses', 'work_functions', 'pension_systems', 'contracts'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Worker $worker)
    {
        //
    }

    public function edit(Worker $worker)
    {
        //
    }

    public function update(Request $request, Worker $worker)
    {
        //
    }

    public function destroy(Worker $worker)
    {
        //
    }
}
