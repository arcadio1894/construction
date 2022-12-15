<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssistanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $assistances = Assistance::select('id', 'date_assistance')->get();

        $events = [];
        foreach ( $assistances as $assistance )
        {
            array_push($events, [
                'title' => 'Asistencia '.$assistance->date_assistance->format('d/m/Y'),
                'start' => $assistance->date_assistance->format('Y-m-d'),
                'backgroundColor' => '#f56954', //red
                'borderColor' => '#f56954', //red
                'allDay' => true
            ]);

        }

        return view('assistance.index', compact( 'permissions', 'events'));

    }

    public function checkAssitanceForCreate( $fecha )
    {
        $date_assistance = Carbon::createFromFormat('Y-m-d', $fecha);

        $assistance = Assistance::where('date_assistance', $fecha)->first();
        if ( isset($assistance) )
        {
            // Si existe cronograma, redireccionar al manage
            return response()->json([
                'message' => 'Redireccionando ...',
                'url' => route('assistance.register', $assistance->id),
                'res' => 1
            ], 200);

        } else {
            // Si no hay cronograma, creamos y redireccionamos
            $assistance2 = Assistance::create([
                'date_assistance' => $date_assistance
            ]);

            return response()->json([
                'message' => 'Se ha creado la asistencia y redireccionando ... ',
                'url' => route('assistance.register', $assistance2->id),
                'res' => 2
            ], 200);
        }
    }

    public function createAssistance($assistance_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $workers = Worker::select('id', 'first_name', 'last_name', 'enable')
            ->where('enable', true)
            ->get();

        $workingDays = WorkingDay::select('id', 'description', 'time_start', 'time_fin', 'enable')
            ->where('enable', true)
            ->get();

        $assistance = Assistance::with(['details' => function ($query) {
                $query->with('worker');
            }])
            ->find($assistance_id);

        return view('assistance.register', compact( 'permissions', 'workers', 'assistance', 'workingDays'));

    }

    public function showAssistance()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('assistance.show', compact( 'permissions'));

    }

    public function edit(Assistance $assistance)
    {
        //
    }

    public function update(Request $request, Assistance $assistance)
    {
        //
    }

    public function destroy(Assistance $assistance)
    {
        //
    }
}
