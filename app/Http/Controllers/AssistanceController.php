<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $workers = Worker::where('enable', true)
            ->get();
        //dump($workers);

        $workingDays = WorkingDay::where('enable', true)
            ->get();
        //dump($workingDays);
        $assistance = Assistance::find($assistance_id);
        //dump($assistance);
        $arrayAssistances = [];
        foreach ( $workers as $worker) {
            $assistancesDetail = AssistanceDetail::where('assistance_id', $assistance_id)
                ->where('worker_id', $worker->id)->first();
            //dump($assistancesDetail);
            if ( isset( $assistancesDetail ) )
            {
                array_push($arrayAssistances, [
                    'worker' => $worker->first_name.' '.$worker->last_name,
                    'worker_id' => $worker->id,
                    'working_day' => $assistancesDetail->working_day_id,
                    'hour_entry' => $assistancesDetail->hour_entry,
                    'hour_out' => $assistancesDetail->hour_out,
                    'status' => $assistancesDetail->status,
                    'obs_justification' => $assistancesDetail->obs_justification,
                    'assistance_detail_id' => $assistancesDetail->id
                ]);
            } else {
                array_push($arrayAssistances, [
                    'worker' => $worker->first_name.' '.$worker->last_name,
                    'worker_id' => $worker->id,
                    'working_day' => '',
                    'hour_entry' => '',
                    'hour_out' => '',
                    'status' => '',
                    'obs_justification' => '',
                    'assistance_detail_id' => ''
                ]);
            }
        }

        //dump($arrayAssistances);
        //dd();

        return view('assistance.register', compact( 'permissions', 'assistance', 'workingDays', 'arrayAssistances'));

    }

    public function showAssistance()
    {
        Carbon::setLocale(config('app.locale'));
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $workers = Worker::where('enable', true)->get();

        $date = Carbon::now('America/Lima')->locale('es');
        $yearCurrent = $date->year;
        $monthCurrent = $date->month;
        $nameMonth = $date->monthName;
        $weekOfYear = $date->weekOfYear;

        $arrayAssistances = [];
        $arrayWeeksDays = [];
        $arrayWeeks = [];
        $arrayWeekWithDays = [];

        for ( $i = 1; $i<=$date->daysInMonth; $i++ )
        {
            $fecha = Carbon::create($yearCurrent,$monthCurrent,$i);
            array_push($arrayWeeks, $fecha->weekOfYear);

        }
        $arrayWeeksDays = array_values(array_unique($arrayWeeks));
        //dump($arrayWeeksDays);


        for ( $i = 0; $i<count($arrayWeeksDays); $i++ )
        {
            $days = [];
            for ( $j = 1; $j<=$date->daysInMonth; $j++ ) {
                $fecha = Carbon::create($yearCurrent, $monthCurrent, $j);
                if ( $fecha->weekOfYear == $arrayWeeksDays[$i] )
                {
                    array_push($days, $fecha->format('jS \d\e M'));
                }

            }
            array_push($arrayWeekWithDays, [
                'week' => 'Semana ' . $arrayWeeksDays[$i],
                'days' => $days
            ]);
        }

        //dump($arrayWeekWithDays);
        /*for ( $k=0 ; $k<count($arrayWeekWithDays) ; $k++)
        {
            dump($arrayWeekWithDays[$k]['week']);
        }*/


        foreach ( $workers as $worker)
        {
            $arrayDayAssistances = [];
            for ( $i = 1; $i<=$date->daysInMonth; $i++ )
            {
                $fecha = Carbon::create($yearCurrent, $monthCurrent, $i);
                $assistance_detail = AssistanceDetail::whereDate('date_assistance',$fecha->format('Y-m-d'))
                    ->where('worker_id', $worker->id)
                    ->first();

                if ( !empty($assistance_detail) )
                {
                    $color = '';
                    $estado = '';
                    if ( $assistance_detail->status == 'A' )
                    {
                        $color = '#28a745';
                        $estado = 'A';
                    } elseif ( $assistance_detail->status == 'F' ) {
                        $color = '#dc3545';
                        $estado = 'F';
                    } elseif ( $assistance_detail->status == 'S' ){
                        $color = '#52585d';
                        $estado = 'S';
                    } elseif ( $assistance_detail->status == 'DM' ){
                        $color = '#17a2b8';
                        $estado = 'DM';
                    } elseif ( $assistance_detail->status == 'FJ' ){
                        $color = '#ffc107';
                        $estado = 'FJ';
                    } elseif ( $assistance_detail->status == 'V' ){
                        $color = '#f012be';
                        $estado = 'V';
                    } else {
                        $color = '#fff';
                        $estado = 'N';
                    }

                    array_push($arrayDayAssistances, [
                        'day' => $assistance_detail->date_assistance,
                        'number_day' => $i,
                        'status' => $estado,
                        'color' => $color
                    ]);
                } else {
                    array_push($arrayDayAssistances, [
                        'day' => $fecha->format('d/m/Y'),
                        'number_day' => $i,
                        'status' => 'N',
                        'color' => '#fff'
                    ]);
                }

            }

            array_push($arrayAssistances, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'assistances' => $arrayDayAssistances
            ]);
        }

        //dump($arrayAssistances);

        return view('assistance.show', compact( 'permissions', 'yearCurrent', 'monthCurrent', 'nameMonth', 'weekOfYear', 'arrayWeekWithDays', 'arrayAssistances'));

    }

    public function getAssistancesMonthYear( $month, $year )
    {
        $workers = Worker::where('enable', true)->get();

        $date = Carbon::create($year,$month,1);
        $yearCurrent = $date->year;
        $monthCurrent = $date->month;
        $nameMonth = $date->monthName;

        $arrayAssistances = [];
        $arrayWeeksDays = [];
        $arrayWeeks = [];
        $arrayWeekWithDays = [];

        for ( $i = 1; $i<=$date->daysInMonth; $i++ )
        {
            $fecha = Carbon::create($yearCurrent,$monthCurrent,$i);
            array_push($arrayWeeks, $fecha->weekOfYear);

        }
        $arrayWeeksDays = array_values(array_unique($arrayWeeks));
        //dump($arrayWeeksDays);


        for ( $i = 0; $i<count($arrayWeeksDays); $i++ )
        {
            $days = [];
            for ( $j = 1; $j<=$date->daysInMonth; $j++ ) {
                $fecha = Carbon::create($yearCurrent, $monthCurrent, $j);
                if ( $fecha->weekOfYear == $arrayWeeksDays[$i] )
                {
                    array_push($days, $fecha->format('jS \d\e M'));
                }

            }
            array_push($arrayWeekWithDays, [
                'week' => 'Semana ' . $arrayWeeksDays[$i],
                'days' => $days
            ]);
        }

        //dump($arrayWeekWithDays);
        /*for ( $k=0 ; $k<count($arrayWeekWithDays) ; $k++)
        {
            dump($arrayWeekWithDays[$k]['week']);
        }*/


        foreach ( $workers as $worker)
        {
            $arrayDayAssistances = [];
            for ( $i = 1; $i<=$date->daysInMonth; $i++ )
            {
                $fecha = Carbon::create($yearCurrent, $monthCurrent, $i);
                $assistance_detail = AssistanceDetail::whereDate('date_assistance',$fecha->format('Y-m-d'))
                    ->where('worker_id', $worker->id)
                    ->first();

                if ( !empty($assistance_detail) )
                {
                    $color = '';
                    $estado = '';
                    if ( $assistance_detail->status == 'A' )
                    {
                        $color = '#28a745';
                        $estado = 'A';
                    } elseif ( $assistance_detail->status == 'F' ) {
                        $color = '#dc3545';
                        $estado = 'F';
                    } elseif ( $assistance_detail->status == 'S' ){
                        $color = '#52585d';
                        $estado = 'S';
                    } elseif ( $assistance_detail->status == 'DM' ){
                        $color = '#17a2b8';
                        $estado = 'DM';
                    } elseif ( $assistance_detail->status == 'FJ' ){
                        $color = '#ffc107';
                        $estado = 'FJ';
                    } elseif ( $assistance_detail->status == 'V' ){
                        $color = '#f012be';
                        $estado = 'V';
                    } else {
                        $color = '#fff';
                        $estado = 'N';
                    }

                    array_push($arrayDayAssistances, [
                        'day' => $assistance_detail->date_assistance,
                        'number_day' => $i,
                        'status' => $estado,
                        'color' => $color
                    ]);
                } else {
                    array_push($arrayDayAssistances, [
                        'day' => $fecha->format('d/m/Y'),
                        'number_day' => $i,
                        'status' => 'N',
                        'color' => '#fff'
                    ]);
                }

            }

            array_push($arrayAssistances, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'assistances' => $arrayDayAssistances
            ]);
        }

        return response()->json([
            'arrayAssistances' => $arrayAssistances,
            'arrayWeekWithDays' => $arrayWeekWithDays
        ], 200);

    }

    public function store(Request $request, $id_assistance, $id_worker)
    {
        DB::beginTransaction();
        try {
            $assistance = Assistance::find($id_assistance);
            $assistanceDetail = AssistanceDetail::create([
                'date_assistance' => $assistance->date_assistance,
                'hour_entry' => ($request->get('time_entry') == '')? null: $request->get('time_entry'),
                'hour_out' => ($request->get('time_out') == '')? null: $request->get('time_out'),
                'status' => ($request->get('status') == '')? null: $request->get('status'),
                'justification' => ($request->get('status') == 'FJ')? 1: 0,
                'obs_justification' => ($request->get('obs_justification') == '')? null: $request->get('obs_justification'),
                'worker_id' => $id_worker,
                'assistance_id' => $id_assistance,
                'working_day_id' => ($request->get('working_day') == '')? null: $request->get('working_day'),
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Asistencia guardada con éxito.', 'assistanceDetail' => $assistanceDetail], 200);

    }

    public function update(Request $request, $assistanceDetail_id)
    {
        DB::beginTransaction();
        try {
            $assistanceDetail = AssistanceDetail::find($assistanceDetail_id);
            $assistanceDetail->hour_entry = ($request->get('time_entry') == '')? null: $request->get('time_entry');
            $assistanceDetail->hour_out = ($request->get('time_out') == '')? null: $request->get('time_out');
            $assistanceDetail->status = ($request->get('status') == '')? null: $request->get('status');
            $assistanceDetail->justification = ($request->get('status') == 'FJ')? 1: 0;
            $assistanceDetail->obs_justification = ($request->get('obs_justification') == '')? null: $request->get('obs_justification');
            $assistanceDetail->working_day_id = ($request->get('working_day') == '')? null: $request->get('working_day');
            $assistanceDetail->save();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Asistencia actualizada con éxito.', 'assistanceDetail' => $assistanceDetail], 200);

    }

    public function destroy(Assistance $assistance)
    {
        //
    }
}