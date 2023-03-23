<?php

namespace App\Http\Controllers;

use App\AssistanceDetail;
use App\Boleta;
use App\DateDimension;
use App\Holiday;
use App\License;
use App\MedicalRest;
use App\PercentageWorker;
use App\Vacation;
use App\Worker;
use App\WorkingDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BoletaController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Boleta $boleta)
    {
        //
    }

    public function edit(Boleta $boleta)
    {
        //
    }

    public function update(Request $request, Boleta $boleta)
    {
        //
    }

    public function destroy(Boleta $boleta)
    {
        //
    }

    public function createBoletaByWorker()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $workers = Worker::where('enable', true)
            ->get();

        $years = DateDimension::distinct()->get(['year']);

        $types = collect([
            [
                'id' => 1,
                'name' => 'Semanal'
            ],
            [
                'id' => 2,
                'name' => 'Mensual'
            ]
        ]);

        /*foreach ( $types as $type )
        {
            dump($type['id']);
        }*/
        /*dump($workers);
        dump($years);
        dump($types);*/
        return view('boleta.createByWorker', compact( 'permissions', 'workers', 'years', 'types'));

    }

    public function generateBoletaWorker()
    {
        $type = $_GET['type'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $week = $_GET['week'];
        $worker_id = $_GET['worker'];

        $worker = Worker::find($worker_id);

        $ruc = '20540001384';
        $empleador = 'SERMEIND FABRICACIONES INDUSTRIALES S.A.C.';
        $tipoDocumento = 'DNI';
        $dni = $worker->dni;
        $mombreApellidos = $worker->first_name . ' ' . $worker->last_name;
        $cargo = ( $worker->work_function_id == null ) ? '': $worker->work_function->description;
        $situacion = 'ACTIVO O SUBSIDIADO';
        $fechaIngreso = ($worker->admission_date == null) ? '': $worker->admission_date->format('d/m/Y');
        $tipoTrabajador = 'Empleado';
        $regimenPensionario = ($worker->pension_system == null) ? '':$worker->pension_system->description;
        $CUSPP = '';
        $diasLaborados = 0; // Dias Trabajados
        $diasNoLaborados = 0;
        $diasSubsidiados = 0;
        $condicion = 0;
        $totalHorasOrdinarias = 0;
        $totalHorasSobretiempo = 0;
        $suspensiones = [];

        $trabajoSobretiempo25 = 0;
        $trabajoSobretiempo35 = 0;
        $trabajoEnFeriadoODiaDescanso = 0;
        $remuneracionOJornalBasico = 0;
        $bonificacionExtraordinariaTemporal = 0;
        $gratificacion = 0;

        $comisionAfpPorcentual = 0;
        $rentaQuintaCategoria = 0;
        $primaDeSeguroAFP = 0;
        $SPPAportacionObligatoria = 0;

        $ESSALUD = 0;

        $pagoXDia = $worker->daily_salary;
        // TODO: Usar el porcentageWorker
        $asignacionFamiliar = $worker->assign_family;
        // TODO: Crear el porcentageWorker HoursDiary = 8
        $horasXDia = 8;
        $pagoXHora = $worker->daily_salary/$horasXDia;
        $dominical = 0;
        $vacaciones = 0;
        $reintegro = 0;

        $pensionAlimentos = 0;
        $prestamos = 0;



        if ( $type == 1 )
        {
            // Es semanal
            $dateFirst = DateDimension::where('year', $year)
                ->where('month', $month)
                ->where('week', $week)
                ->first();
            $dateLast = DateDimension::where('year', $year)
                ->where('month', $month)
                ->where('week', $week)
                ->orderBy('date', 'desc')
                ->first();

            $start = $dateFirst->day.'/'.$dateFirst->month.'/'.$dateFirst->year;
            $end = $dateLast->day.'/'.$dateLast->month.'/'.$dateLast->year;

            $periodo = $start .' al '.$end;

            $semana = $week;
        }
        else {
            // Es mensual
            $dateFirst = DateDimension::where('year', $year)
                ->where('month', $month)
                ->first();
            $dateLast = DateDimension::where('year', $year)
                ->where('month', $month)
                ->orderBy('date', 'desc')
                ->first();

            $start = $dateFirst->day.'/'.$dateFirst->month.'/'.$dateFirst->year;
            $end = $dateLast->day.'/'.$dateLast->month.'/'.$dateLast->year;

            $periodo = (($month < 10) ? '0'.$month : $month).'/'.$year;
            $semana = '';
        }

        $arrayByWeek = $this->getTotalHoursByWorker($worker_id, $start, $end);

        $h_ord = 0;
        $h_25 = 0;
        $h_35 = 0;
        $h_100 = 0;
        $h_esp = 0;

        for ($i=0; $i<count($arrayByWeek); $i++)
        {
            $h_ord += $arrayByWeek[$i]['h_ord'];
            $h_25 += $arrayByWeek[$i]['h_25'];
            $h_35 += $arrayByWeek[$i]['h_35'];
            $h_100 += $arrayByWeek[$i]['h_100'];
            $h_esp += $arrayByWeek[$i]['h_esp'];
        }

        dump($ruc);
        dump($empleador);
        dump($periodo);
        dump($tipoDocumento);
        dump($dni);
        dump($mombreApellidos);
        dump($situacion);
        dump($fechaIngreso);
        dump($tipoTrabajador);
        dump($regimenPensionario);
        dump($CUSPP);
        dump($h_ord);
        dump($h_25);
        dump($h_35);
        dump($h_100);
        dump($h_esp);

        dd();
    }

    public function getTotalHoursByWorker($worker_id, $start, $end)
    {
        $arrayByWeek = [];
        if ( $start != '' || $end != '' )
        {
            $date_start = Carbon::createFromFormat('d/m/Y', $start);
            $end_start = Carbon::createFromFormat('d/m/Y', $end);

            $worker = Worker::find($worker_id);
            $dateCurrent = Carbon::now();

            $arrayByDates = [];
            $arrayByWeek = [];
            $arrayByWeekMonth = [];

            // TODO: Array By Dates
            $yearCurrent = $dateCurrent->year;

            $dates = DateDimension::whereDate('date', '>=',$date_start)
                ->whereDate('date', '<=',$end_start)
                ->orderBy('date', 'ASC')
                ->get();

            //dump($dates);

            foreach ( $dates as $date )
            {
                $arrayDayAssistances = [];

                $fecha = Carbon::create($date->year, $date->month, $date->day);
                //dump($fecha);
                $assistance_detail = AssistanceDetail::whereDate('date_assistance',$fecha->format('Y-m-d'))
                    ->where('worker_id', $worker->id)
                    ->where('status', '<>', 'S')
                    ->first();
                //dump($assistance_detail);
                if ( !empty($assistance_detail) )
                {
                    //dump('Entre opr que si hay asistencia');
                    // TODO: Verificamos las horas especiales: DM, V, L
                    $medicalRests = MedicalRest::whereDate('date_start', '<=',$fecha->format('Y-m-d'))
                        ->whereDate('date_end', '>=',$fecha->format('Y-m-d'))
                        ->where('worker_id', $worker->id)
                        ->get();
                    //dump($medicalRests);
                    $vacations = Vacation::whereDate('date_start', '<=',$fecha->format('Y-m-d'))
                        ->whereDate('date_end', '>=',$fecha->format('Y-m-d'))
                        ->where('worker_id', $worker->id)
                        ->get();
                    //dump($vacations);
                    $licenses = License::whereDate('date_start', '<=',$fecha->format('Y-m-d'))
                        ->whereDate('date_end', '>=',$fecha->format('Y-m-d'))
                        ->where('worker_id', $worker->id)
                        ->get();
                    //dump($licenses);
                    $timeBreak = PercentageWorker::where('name', 'time_break')->first();
                    $time_break = (float)$timeBreak->value;
                    //dump($time_break);
                    $workingDay = WorkingDay::find($assistance_detail->working_day_id);
                    //dump($workingDay);
                    if ( !$this->isHoliday($fecha) && !$fecha->isSunday() ) {
                        //dump('Entré porque no es Feriado y es dia normal');
                        // TODO: No feriado - Dia Normal (L-S)
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            ///dump('Entré porque hay Horas especiales');
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            //dump('Horas Trabajadas: '. $hoursWorked);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            //dump('Horas Trabajadas: '. $hoursNeto);
                            array_push($arrayDayAssistances, [
                                0,
                                0,
                                0,
                                0,
                                $hoursNeto,
                            ]);
                            //dump($arrayDayAssistances);
                        } else {
                            //dump('Entré porque no hay Horas especiales');
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $wD = WorkingDay::where('enable', true)->skip(2)->take(1)->first();
                            if ( $workingDay->id == $wD->id )
                            {
                                $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount, 2);
                            } else {
                                $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            }
                            //$hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            //dump('Horas Totales: ' . $hoursTotals);
                            $hoursOrdinary = 0;
                            $hours25 = 0;
                            $hours35 = 0;
                            $hours100 = 0;
                            if ( $assistance_detail->hour_out > $workingDay->time_fin ){
                                //dump('Entre porqe detectamos horas extras');
                                // TODO: Detectamos horas extras
                                $wD = WorkingDay::where('enable', true)->skip(2)->take(1)->first();
                                if ( $workingDay->id == $wD->id )
                                {
                                    $hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $assistance_detail->hours_discount , 2);
                                } else {
                                    $hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount , 2);
                                }
                                //$hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount , 2);
                                //dump('$hoursOrdinary' . $hoursOrdinary);
                                $hoursExtrasTotals = $hoursTotals - $hoursOrdinary;
                                //dump('$hoursExtrasTotals' . $hoursExtrasTotals);
                                if ( $hoursExtrasTotals > 0 && $hoursExtrasTotals < 2 ) {
                                    $hours25 = $hoursExtrasTotals;
                                    //dump('$hours25' . $hours25);
                                } else {
                                    $hours25 = 2;
                                    //dump('$hours25' . $hours25);
                                    $hours35 = $hoursExtrasTotals-2;
                                    //dump('$hours35' . $hours35);
                                }
                            } else {
                                //dump('Entre porqe no detectamos horas extras');
                                $wD = WorkingDay::where('enable', true)->skip(2)->take(1)->first();
                                if ( $workingDay->id == $wD->id )
                                {
                                    $hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $assistance_detail->hours_discount ;
                                } else {
                                    $hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount ;
                                }
                                //$hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount ;
                                //dump('$hoursOrdinary' . $hoursOrdinary);
                            }

                            array_push($arrayDayAssistances, [
                                $hoursOrdinary,
                                $hours25,
                                $hours35,
                                $hours100,
                                0,
                            ]);
                            //dump($arrayDayAssistances);
                        }

                    } elseif ( !$this->isHoliday($fecha) && $fecha->isSunday() ) {
                        // TODO: No feriado - Domingo
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                0,
                                $hoursNeto,
                            ]);
                        } else {
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);

                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                $hoursTotals,
                                0,
                            ]);
                        }

                    } elseif ( $this->isHoliday($fecha) && !$fecha->isSunday() ) {
                        // TODO: Feriado - Dia Normal (L-S)
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            array_push($arrayDayAssistances, [
                                0,
                                0,
                                0,
                                0,
                                $hoursNeto,
                            ]);
                        } else {
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            $hoursOrdinary = 0;
                            $hours100 = 0;
                            if ( $assistance_detail->hour_out > $workingDay->time_fin ){
                                // TODO: Detectamos horas extras
                                $hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount , 2);

                                $hoursExtrasTotals = $hoursTotals - $hoursOrdinary;
                                $hours100 = $hoursExtrasTotals;
                            } else {
                                $hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount ;
                            }

                            array_push($arrayDayAssistances, [
                                $hoursOrdinary,
                                0,
                                0,
                                $hours100+$hoursOrdinary,
                                0,
                            ]);
                        }

                    } elseif ( $this->isHoliday($fecha) && $fecha->isSunday() ) {
                        // TODO: Feriado - Domingo
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                $hoursNeto,
                                $hoursNeto,
                            ]);
                        } else {
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);

                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                $hoursTotals,
                                0,
                            ]);
                        }
                    }

                }
                else {
                    if ( $fecha->isSunday() ) {
                        array_push($arrayDayAssistances, [
                            '',
                            '',
                            '',
                            0,
                            0,
                        ]);
                    } else {
                        array_push($arrayDayAssistances, [
                            0,
                            0,
                            0,
                            0,
                            0,
                        ]);
                    }
                }

                array_push($arrayByDates, [
                    'week' => $date->week,
                    'date' => $date->date->format('d/m/Y'),
                    'month' => $date->month_name_year,
                    'day' => $date->day,
                    'assistances' => $arrayDayAssistances
                ]);
            }

            //dump($arrayByDates);
            //dd();

            $first = true;

            $h_ord = 0;
            $h_25 = 0;
            $h_35 = 0;
            $h_100 = 0;
            $h_esp = 0;

            $fecha = '';
            $week = '';
            $month = '';

            //dump($arrayByDates);

            for ( $i=0; $i<count($arrayByDates); $i++ )
            {
                //dump($arrayByDates[$i]['date']);
                if ( $first ) {
                    //dump( 'Sem Act '.$arrayByDates[$i]['week'] .' - Sem Sig '. $arrayByDates[$i+1]['week'] );
                    $week2 = ($i == count($arrayByDates)-1) ? 0:$arrayByDates[$i+1]['week'] ;
                    //dump($week);
                    if ( $arrayByDates[$i]['week'] != $week2 )
                    {
                        $dayStart = ($arrayByDates[$i]['day'] < 10) ? '0'.$arrayByDates[$i]['day']: $arrayByDates[$i]['day'];

                        $fecha = $fecha . 'DEL '. $dayStart .' AL ' . $dayStart;
                        $week = $week . $arrayByDates[$i]['week'];
                        $month = $month . $arrayByDates[$i]['month'];

                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = true;
                        array_push($arrayByWeek, [
                            'week'  => $week,
                            'date'  => $fecha,
                            'month' => $month,
                            'h_ord' => $h_ord,
                            'h_25'  => $h_25,
                            'h_35'  => $h_35,
                            'h_100' => $h_100,
                            'h_esp' => $h_esp,
                        ]);
                        //dump($arrayByWeek);

                        $fecha = '';
                        $week = '';
                        $month = '';
                        $h_ord = 0;
                        $h_25 = 0;
                        $h_35 = 0;
                        $h_100 = 0;
                        $h_esp = 0;


                    } else {
                        $dayStart = ($arrayByDates[$i]['day'] < 10) ? '0'.$arrayByDates[$i]['day']: $arrayByDates[$i]['day'];

                        $fecha = $fecha . 'DEL '. $dayStart .' AL ';
                        $week = $week . $arrayByDates[$i]['week'];
                        $month = $month . $arrayByDates[$i]['month'];

                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = false;

                    }

                }
                else {
                    if ( ($i == count($arrayByDates)-1) || ( (isset($arrayByDates[$i+1])) && ($arrayByDates[$i]['week'] != $arrayByDates[$i+1]['week']) ) )
                    {
                        $dayEnd = ($arrayByDates[$i]['day'] < 10) ? '0'.$arrayByDates[$i]['day']: $arrayByDates[$i]['day'];

                        $fecha = $fecha . $dayEnd;
                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = true;
                        array_push($arrayByWeek, [
                            'week'  => $week,
                            'date'  => $fecha,
                            'month' => $month,
                            'h_ord' => $h_ord,
                            'h_25'  => $h_25,
                            'h_35'  => $h_35,
                            'h_100' => $h_100,
                            'h_esp' => $h_esp,
                        ]);
                        //dump($arrayByWeek);

                        $fecha = '';
                        $week = '';
                        $month = '';
                        $h_ord = 0;
                        $h_25 = 0;
                        $h_35 = 0;
                        $h_100 = 0;
                        $h_esp = 0;

                    } else {
                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = false;
                    }


                }

            }
        }
        /*else {

            $worker = Worker::find($worker_id);
            $dateCurrent = Carbon::now();

            $arrayByDates = [];
            $arrayByWeek = [];
            $arrayByWeekMonth = [];

            // TODO: Array By Dates
            $yearCurrent = $dateCurrent->year;

            $dates = DateDimension::where('year', $yearCurrent)
                ->orderBy('date', 'ASC')
                ->get();

            foreach ( $dates as $date )
            {
                $arrayDayAssistances = [];

                $fecha = Carbon::create($date->year, $date->month, $date->day);
                //dump($fecha);
                $assistance_detail = AssistanceDetail::whereDate('date_assistance',$fecha->format('Y-m-d'))
                    ->where('worker_id', $worker->id)
                    ->where('status', '<>', 'S')
                    ->first();
                //dump($assistance_detail);
                if ( !empty($assistance_detail) )
                {
                    //dump('Entre opr que si hay asistencia');
                    // TODO: Verificamos las horas especiales: DM, V, L
                    $medicalRests = MedicalRest::whereDate('date_start', '<=',$fecha->format('Y-m-d'))
                        ->whereDate('date_end', '>=',$fecha->format('Y-m-d'))
                        ->where('worker_id', $worker->id)
                        ->get();
                    //dump($medicalRests);
                    $vacations = Vacation::whereDate('date_start', '<=',$fecha->format('Y-m-d'))
                        ->whereDate('date_end', '>=',$fecha->format('Y-m-d'))
                        ->where('worker_id', $worker->id)
                        ->get();
                    //dump($vacations);
                    $licenses = License::whereDate('date_start', '<=',$fecha->format('Y-m-d'))
                        ->whereDate('date_end', '>=',$fecha->format('Y-m-d'))
                        ->where('worker_id', $worker->id)
                        ->get();
                    //dump($licenses);
                    $timeBreak = PercentageWorker::where('name', 'time_break')->first();
                    $time_break = (float)$timeBreak->value;
                    //dump($time_break);
                    $workingDay = WorkingDay::find($assistance_detail->working_day_id);
                    //dump($workingDay);
                    if ( !$this->isHoliday($fecha) && !$fecha->isSunday() ) {
                        //dump('Entré porque no es Feriado y es dia normal');
                        // TODO: No feriado - Dia Normal (L-S)
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            ///dump('Entré porque hay Horas especiales');
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            //dump('Horas Trabajadas: '. $hoursWorked);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            //dump('Horas Trabajadas: '. $hoursNeto);
                            array_push($arrayDayAssistances, [
                                0,
                                0,
                                0,
                                0,
                                $hoursNeto,
                            ]);
                            //dump($arrayDayAssistances);
                        } else {
                            //dump('Entré porque no hay Horas especiales');
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $wD = WorkingDay::where('enable', true)->skip(2)->take(1)->first();
                            if ( $workingDay->id == $wD->id )
                            {
                                $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount, 2);
                            } else {
                                $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            }
                            //$hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            //dump('Horas Totales: ' . $hoursTotals);
                            $hoursOrdinary = 0;
                            $hours25 = 0;
                            $hours35 = 0;
                            $hours100 = 0;
                            if ( $assistance_detail->hour_out > $workingDay->time_fin ){
                                //dump('Entre porqe detectamos horas extras');
                                // TODO: Detectamos horas extras
                                $wD = WorkingDay::where('enable', true)->skip(2)->take(1)->first();
                                if ( $workingDay->id == $wD->id )
                                {
                                    $hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $assistance_detail->hours_discount , 2);
                                } else {
                                    $hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount , 2);
                                }
                                //$hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount , 2);
                                //dump('$hoursOrdinary' . $hoursOrdinary);
                                $hoursExtrasTotals = $hoursTotals - $hoursOrdinary;
                                //dump('$hoursExtrasTotals' . $hoursExtrasTotals);
                                if ( $hoursExtrasTotals > 0 && $hoursExtrasTotals < 2 ) {
                                    $hours25 = $hoursExtrasTotals;
                                    //dump('$hours25' . $hours25);
                                } else {
                                    $hours25 = 2;
                                    //dump('$hours25' . $hours25);
                                    $hours35 = $hoursExtrasTotals-2;
                                    //dump('$hours35' . $hours35);
                                }
                            } else {
                                //dump('Entre porqe no detectamos horas extras');
                                $wD = WorkingDay::where('enable', true)->skip(2)->take(1)->first();
                                if ( $workingDay->id == $wD->id )
                                {
                                    $hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $assistance_detail->hours_discount ;
                                } else {
                                    $hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount ;
                                }
                                //$hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount ;
                                //dump('$hoursOrdinary' . $hoursOrdinary);
                            }

                            array_push($arrayDayAssistances, [
                                $hoursOrdinary,
                                $hours25,
                                $hours35,
                                $hours100,
                                0,
                            ]);
                            //dump($arrayDayAssistances);
                        }

                    } elseif ( !$this->isHoliday($fecha) && $fecha->isSunday() ) {
                        // TODO: No feriado - Domingo
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                0,
                                $hoursNeto,
                            ]);
                        } else {
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);

                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                $hoursTotals,
                                0,
                            ]);
                        }

                    } elseif ( $this->isHoliday($fecha) && !$fecha->isSunday() ) {
                        // TODO: Feriado - Dia Normal (L-S)
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            array_push($arrayDayAssistances, [
                                0,
                                0,
                                0,
                                0,
                                $hoursNeto,
                            ]);
                        } else {
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            $hoursOrdinary = 0;
                            $hours100 = 0;
                            if ( $assistance_detail->hour_out > $workingDay->time_fin ){
                                // TODO: Detectamos horas extras
                                $hoursOrdinary = round( (Carbon::parse($workingDay->time_fin)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount , 2);

                                $hoursExtrasTotals = $hoursTotals - $hoursOrdinary;
                                $hours100 = $hoursExtrasTotals;
                            } else {
                                $hoursOrdinary = (Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry)) - $time_break - $assistance_detail->hours_discount ;
                            }

                            array_push($arrayDayAssistances, [
                                $hoursOrdinary,
                                0,
                                0,
                                $hours100+$hoursOrdinary,
                                0,
                            ]);
                        }

                    } elseif ( $this->isHoliday($fecha) && $fecha->isSunday() ) {
                        // TODO: Feriado - Domingo
                        if ( count($medicalRests)>0 || count($vacations)>0 || count($licenses)>0 )
                        {
                            // TODO: Con H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursNeto = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);
                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                $hoursNeto,
                                $hoursNeto,
                            ]);
                        } else {
                            // TODO: Sin H-ESP
                            $hoursWorked = Carbon::parse($assistance_detail->hour_out)->floatDiffInHours($assistance_detail->hour_entry);
                            $hoursTotals = round($hoursWorked - $assistance_detail->hours_discount - $time_break, 2);

                            array_push($arrayDayAssistances, [
                                '',
                                '',
                                '',
                                $hoursTotals,
                                0,
                            ]);
                        }
                    }

                }
                else {
                    if ( $fecha->isSunday() ) {
                        array_push($arrayDayAssistances, [
                            '',
                            '',
                            '',
                            0,
                            0,
                        ]);
                    } else {
                        array_push($arrayDayAssistances, [
                            0,
                            0,
                            0,
                            0,
                            0,
                        ]);
                    }
                }

                array_push($arrayByDates, [
                    'week' => $date->week,
                    'date' => $date->date->format('d/m/Y'),
                    'month' => $date->month_name_year,
                    'day' => $date->day,
                    'assistances' => $arrayDayAssistances
                ]);
            }

            //dump($arrayByDates);
            //dd();

            $first = true;

            $h_ord = 0;
            $h_25 = 0;
            $h_35 = 0;
            $h_100 = 0;
            $h_esp = 0;

            $fecha = '';
            $week = '';
            $month = '';

            for ( $i=0; $i<count($arrayByDates); $i++ )
            {
                //dump($arrayByDates[$i]['date']);
                if ( $first ) {
                    $week2 = ($i == count($arrayByDates)-1) ? 0:$arrayByDates[$i+1]['week'] ;
                    //dump($week);
                    if ( $arrayByDates[$i]['week'] != $week2 )
                    {
                        $dayStart = ($arrayByDates[$i]['day'] < 10) ? '0'.$arrayByDates[$i]['day']: $arrayByDates[$i]['day'];

                        $fecha = $fecha . 'DEL '. $dayStart .' AL ' . $dayStart;
                        $week = $week . $arrayByDates[$i]['week'];
                        $month = $month . $arrayByDates[$i]['month'];

                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = true;
                        array_push($arrayByWeek, [
                            'week'  => $week,
                            'date'  => $fecha,
                            'month' => $month,
                            'h_ord' => $h_ord,
                            'h_25'  => $h_25,
                            'h_35'  => $h_35,
                            'h_100' => $h_100,
                            'h_esp' => $h_esp,
                        ]);

                        $fecha = '';
                        $week = '';
                        $month = '';
                        $h_ord = 0;
                        $h_25 = 0;
                        $h_35 = 0;
                        $h_100 = 0;
                        $h_esp = 0;


                    } else {
                        $dayStart = ($arrayByDates[$i]['day'] < 10) ? '0'.$arrayByDates[$i]['day']: $arrayByDates[$i]['day'];

                        $fecha = $fecha . 'DEL '. $dayStart .' AL ';
                        $week = $week . $arrayByDates[$i]['week'];
                        $month = $month . $arrayByDates[$i]['month'];

                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = false;

                    }
                } else {
                    if ( ($i == count($arrayByDates)-1) || ( (isset($arrayByDates[$i+1])) && ($arrayByDates[$i]['week'] != $arrayByDates[$i+1]['week']) ) )
                    {
                        $dayEnd = ($arrayByDates[$i]['day'] < 10) ? '0'.$arrayByDates[$i]['day']: $arrayByDates[$i]['day'];

                        $fecha = $fecha . $dayEnd;
                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = true;
                        array_push($arrayByWeek, [
                            'week'  => $week,
                            'date'  => $fecha,
                            'month' => $month,
                            'h_ord' => $h_ord,
                            'h_25'  => $h_25,
                            'h_35'  => $h_35,
                            'h_100' => $h_100,
                            'h_esp' => $h_esp,
                        ]);

                        $fecha = '';
                        $week = '';
                        $month = '';
                        $h_ord = 0;
                        $h_25 = 0;
                        $h_35 = 0;
                        $h_100 = 0;
                        $h_esp = 0;

                    } else {
                        $h_ord += ($arrayByDates[$i]['assistances'][0][0] == '') ? 0: $arrayByDates[$i]['assistances'][0][0];
                        $h_25  += ($arrayByDates[$i]['assistances'][0][1] == '') ? 0: $arrayByDates[$i]['assistances'][0][1];
                        $h_35  += ($arrayByDates[$i]['assistances'][0][2] == '') ? 0: $arrayByDates[$i]['assistances'][0][2];
                        $h_100 += ($arrayByDates[$i]['assistances'][0][3] == '') ? 0: $arrayByDates[$i]['assistances'][0][3];
                        $h_esp += ($arrayByDates[$i]['assistances'][0][4] == '') ? 0: $arrayByDates[$i]['assistances'][0][4];
                        $first = false;
                    }


                }


            }
        }*/

        //dump($arrayByWeek);
        //dump($arrayByDates);
        //dd();

        return $arrayByWeek;

    }

    private function isHoliday(Carbon $fecha)
    {
        $holiday = Holiday::whereDate('date_complete', '=',$fecha->format('Y-m-d'))->first();
        return ( !empty($holiday) ) ? true:false ;
    }

}
