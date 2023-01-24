<?php

namespace App\Http\Controllers;

use App\Assistance;
use App\AssistanceDetail;
use App\Exports\AssistanceExcelMultipleSheets;
use App\Holiday;
use App\MedicalRest;
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
        $holidays = Holiday::select('id', 'date_complete', 'description')->get();

        $events = [];
        foreach ( $assistances as $assistance )
        {
            array_push($events, [
                'title' => 'Asistencia '.$assistance->date_assistance->format('d/m/Y'),
                'start' => $assistance->date_assistance->format('Y-m-d'),
                'backgroundColor' => '#0A23F7', //red
                'borderColor' => '#0A23F7', //red
                'allDay' => true
            ]);

        }

        foreach ( $holidays as $holiday )
        {
            array_push($events, [
                'title' => $holiday->description,
                'start' => $holiday->date_complete->format('Y-m-d'),
                'backgroundColor' => '#117811', //red
                'borderColor' => '#117811', //red
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

            // Creamos los detalles de la asistencia
            $workers = Worker::where('enable', true)
                ->get();

            $workingDay = WorkingDay::where('enable', true)->first();

            foreach ( $workers as $worker) {
                // TODO: Revisamos si hay Descansos Medicos
                //dump($worker->first_name .' '. $worker->last_name);
                $medicalRests = MedicalRest::whereDate('date_start', '<=',$assistance2->date_assistance)
                    ->whereDate('date_end', '>=',$assistance2->date_assistance)
                    ->where('worker_id', $worker->id)
                    ->get();
                //dump($medicalRests);
                if ( count($medicalRests) > 0 )
                {
                    AssistanceDetail::create([
                        'date_assistance' => $assistance2->date_assistance,
                        'hour_entry' => ($worker->working_day_id != null) ? $worker->working_day->time_start:$workingDay->time_start,
                        'hour_out' => ($worker->working_day_id != null) ? $worker->working_day->time_fin:$workingDay->time_fin,
                        'worker_id' => $worker->id,
                        'assistance_id' => $assistance2->id,
                        'working_day_id' => $workingDay->id,
                        'status' => 'M'
                    ]);
                } else {
                    AssistanceDetail::create([
                        'date_assistance' => $assistance2->date_assistance,
                        'hour_entry' => ($worker->working_day_id != null) ? $worker->working_day->time_start:$workingDay->time_start,
                        'hour_out' => ($worker->working_day_id != null) ? $worker->working_day->time_fin:$workingDay->time_fin,
                        'worker_id' => $worker->id,
                        'assistance_id' => $assistance2->id,
                        'working_day_id' => $workingDay->id
                    ]);
                }

            }

            //dd();
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

        $arraySummary = [];
        $cantA = 0;
        $cantF = 0;
        $cantS = 0;
        $cantM = 0;
        $cantJ = 0;
        $cantV = 0;
        $cantP = 0;
        $cantT = 0;
        foreach ( $workers as $worker)
        {
            $arrayDayAssistances = [];
            $cantA = 0;
            $cantF = 0;
            $cantS = 0;
            $cantM = 0;
            $cantJ = 0;
            $cantV = 0;
            $cantP = 0;
            $cantT = 0;

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
                    $backgroundColor = ($fecha->isSunday()) ? '#F18938': '#fff';

                    if ( $assistance_detail->status == 'A' )
                    {
                        $color = '#28a745';
                        $estado = 'A';
                        $cantA = $cantA + 1;
                    } elseif ( $assistance_detail->status == 'F' ) {
                        $color = '#dc3545';
                        $estado = 'F';
                        $cantF = $cantF + 1;
                    } elseif ( $assistance_detail->status == 'S' ){
                        $color = '#52585d';
                        $estado = 'S';
                        $cantS = $cantS + 1;
                    } elseif ( $assistance_detail->status == 'M' ){
                        $color = '#17a2b8';
                        $estado = 'M';
                        $cantM = $cantM + 1;
                    } elseif ( $assistance_detail->status == 'J' ){
                        $color = '#ffc107';
                        $estado = 'J';
                        $cantJ = $cantJ + 1;
                    } elseif ( $assistance_detail->status == 'V' ){
                        $color = '#f012be';
                        $estado = 'V';
                        $cantV = $cantV + 1;
                    } elseif ( $assistance_detail->status == 'P' ){
                        $color = '#007bff';
                        $estado = 'P';
                        $cantP = $cantP + 1;
                    } elseif ( $assistance_detail->status == 'T' ){
                        $color = '#6610f2';
                        $estado = 'T';
                        $cantT = $cantT + 1;
                    } else {
                        $color = '#fff';
                        $estado = 'N';
                    }

                    array_push($arrayDayAssistances, [
                        'day' => $assistance_detail->date_assistance,
                        'number_day' => $i,
                        'status' => $estado,
                        'color' => $color,
                        'bg_color' => $backgroundColor
                    ]);
                } else {
                    $backgroundColor = ($fecha->isSunday()) ? '#F18938': '#fff';

                    array_push($arrayDayAssistances, [
                        'day' => $fecha->format('d/m/Y'),
                        'number_day' => $i,
                        'status' => 'N',
                        'color' => '#fff',
                        'bg_color' => $backgroundColor
                    ]);
                }

            }

            array_push($arrayAssistances, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'assistances' => $arrayDayAssistances
            ]);

            array_push($arraySummary, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'cantA' => $cantA,
                'cantF' => $cantF,
                'cantS' => $cantS,
                'cantM' => $cantM,
                'cantJ' => $cantJ,
                'cantV' => $cantV,
                'cantP' => $cantP,
                'cantT' => $cantT
            ]);
        }



        //dump($arrayAssistances);

        return view('assistance.show', compact( 'permissions', 'yearCurrent', 'monthCurrent', 'nameMonth', 'weekOfYear', 'arrayWeekWithDays', 'arrayAssistances', 'arraySummary'));

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
        $arraySummary = [];
        $cantA = 0;
        $cantF = 0;
        $cantS = 0;
        $cantM = 0;
        $cantJ = 0;
        $cantV = 0;
        $cantP = 0;
        $cantT = 0;

        foreach ( $workers as $worker)
        {
            $arrayDayAssistances = [];
            $cantA = 0;
            $cantF = 0;
            $cantS = 0;
            $cantM = 0;
            $cantJ = 0;
            $cantV = 0;
            $cantP = 0;
            $cantT = 0;
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
                    $backgroundColor = ($fecha->isSunday()) ? '#F18938': '#fff';

                    if ( $assistance_detail->status == 'A' )
                    {
                        $color = '#28a745';
                        $estado = 'A';
                        $cantA = $cantA + 1;
                    } elseif ( $assistance_detail->status == 'F' ) {
                        $color = '#dc3545';
                        $estado = 'F';
                        $cantF = $cantF + 1;
                    } elseif ( $assistance_detail->status == 'S' ){
                        $color = '#52585d';
                        $estado = 'S';
                        $cantS = $cantS + 1;
                    } elseif ( $assistance_detail->status == 'M' ){
                        $color = '#17a2b8';
                        $estado = 'M';
                        $cantM = $cantM + 1;
                    } elseif ( $assistance_detail->status == 'J' ){
                        $color = '#ffc107';
                        $estado = 'J';
                        $cantJ = $cantJ + 1;
                    } elseif ( $assistance_detail->status == 'V' ){
                        $color = '#f012be';
                        $estado = 'V';
                        $cantV = $cantV + 1;
                    } elseif ( $assistance_detail->status == 'P' ){
                        $color = '#007bff';
                        $estado = 'P';
                        $cantP = $cantP + 1;
                    } elseif ( $assistance_detail->status == 'T' ){
                        $color = '#6610f2';
                        $estado = 'T';
                        $cantT = $cantT + 1;
                    } else {
                        $color = '#fff';
                        $estado = 'N';
                    }

                    array_push($arrayDayAssistances, [
                        'day' => $assistance_detail->date_assistance,
                        'number_day' => $i,
                        'status' => $estado,
                        'color' => $color,
                        'bg_color' => $backgroundColor
                    ]);
                } else {
                    $backgroundColor = ($fecha->isSunday()) ? '#F18938': '#fff';

                    array_push($arrayDayAssistances, [
                        'day' => $fecha->format('d/m/Y'),
                        'number_day' => $i,
                        'status' => 'N',
                        'color' => '#fff',
                        'bg_color' => $backgroundColor
                    ]);
                }

            }

            array_push($arrayAssistances, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'assistances' => $arrayDayAssistances
            ]);

            array_push($arraySummary, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'cantA' => $cantA,
                'cantF' => $cantF,
                'cantS' => $cantS,
                'cantM' => $cantM,
                'cantJ' => $cantJ,
                'cantV' => $cantV,
                'cantP' => $cantP,
                'cantT' => $cantT
            ]);
        }

        return response()->json([
            'arrayAssistances' => $arrayAssistances,
            'arrayWeekWithDays' => $arrayWeekWithDays,
            'arraySummary' => $arraySummary
        ], 200);

    }

    public function exportAssistancesMonthYear()
    {
        $year = $_GET['year'];
        $month = $_GET['month'];;

        $workers = Worker::where('enable', true)->get();

        $date = Carbon::create($year,$month,1);
        $yearCurrent = $date->year;
        $monthCurrent = $date->month;
        $nameMonth = $date->monthName;

        $dates = 'Reporte de Asistencias del mes de ' .$nameMonth.' del año '.$yearCurrent;

        $arrayAssistances = [];

        $arraySummary = [];
        $cantA = 0;
        $cantF = 0;
        $cantS = 0;
        $cantM = 0;
        $cantJ = 0;
        $cantV = 0;
        $cantP = 0;
        $cantT = 0;

        foreach ( $workers as $worker)
        {
            $arrayDayAssistances = [];
            $cantA = 0;
            $cantF = 0;
            $cantS = 0;
            $cantM = 0;
            $cantJ = 0;
            $cantV = 0;
            $cantP = 0;
            $cantT = 0;
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
                    $backgroundColor = ($fecha->isSunday()) ? '#F18938': '#fff';

                    if ( $assistance_detail->status == 'A' )
                    {
                        $color = '#28a745';
                        $estado = 'A';
                        $cantA = $cantA + 1;
                    } elseif ( $assistance_detail->status == 'F' ) {
                        $color = '#dc3545';
                        $estado = 'F';
                        $cantF = $cantF + 1;
                    } elseif ( $assistance_detail->status == 'S' ){
                        $color = '#52585d';
                        $estado = 'S';
                        $cantS = $cantS + 1;
                    } elseif ( $assistance_detail->status == 'M' ){
                        $color = '#17a2b8';
                        $estado = 'M';
                        $cantM = $cantM + 1;
                    } elseif ( $assistance_detail->status == 'J' ){
                        $color = '#ffc107';
                        $estado = 'J';
                        $cantJ = $cantJ + 1;
                    } elseif ( $assistance_detail->status == 'V' ){
                        $color = '#f012be';
                        $estado = 'V';
                        $cantV = $cantV + 1;
                    } elseif ( $assistance_detail->status == 'P' ){
                        $color = '#007bff';
                        $estado = 'P';
                        $cantP = $cantP + 1;
                    } elseif ( $assistance_detail->status == 'T' ){
                        $color = '#6610f2';
                        $estado = 'T';
                        $cantT = $cantT + 1;
                    } else {
                        $color = '#fff';
                        $estado = 'N';
                    }

                    array_push($arrayDayAssistances, [
                        'day' => $assistance_detail->date_assistance,
                        'number_day' => $i,
                        'status' => $estado,
                        'color' => $color,
                        'bg_color' => $backgroundColor
                    ]);
                } else {
                    $backgroundColor = ($fecha->isSunday()) ? '#F18938': '#fff';

                    array_push($arrayDayAssistances, [
                        'day' => $fecha->format('d/m/Y'),
                        'number_day' => $i,
                        'status' => 'N',
                        'color' => '#fff',
                        'bg_color' => $backgroundColor
                    ]);
                }

            }

            array_push($arrayAssistances, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'assistances' => $arrayDayAssistances
            ]);

            array_push($arraySummary, [
                'worker' => $worker->first_name . ' ' . $worker->last_name,
                'cantA' => $cantA,
                'cantF' => $cantF,
                'cantS' => $cantS,
                'cantM' => $cantM,
                'cantJ' => $cantJ,
                'cantV' => $cantV,
                'cantP' => $cantP,
                'cantT' => $cantT
            ]);
        }

        return (new AssistanceExcelMultipleSheets($arrayAssistances, $arraySummary, $dates))->download('reporteAsistencias.xlsx');


        /*return response()->json([
            'arrayAssistances' => $arrayAssistances,
            'arrayWeekWithDays' => $arrayWeekWithDays,
            'arraySummary' => $arraySummary
        ], 200);*/

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
