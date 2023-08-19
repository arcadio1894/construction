<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityWorker;
use App\Equipment;
use App\Exports\TimelinesExports;
use App\Phase;
use App\Quote;
use App\Task;
use App\TaskWorker;
use App\Timeline;
use App\TimelineArea;
use App\Work;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

class TimelineController extends Controller
{
    public function showTimelines()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $timelines = Timeline::select('id', 'date')->get();

        $events = [];
        foreach ( $timelines as $timeline )
        {
            array_push($events, [
                'title' => 'Cronograma '.$timeline->id,
                'start' => $timeline->date->format('Y-m-d'),
                'backgroundColor' => '#f56954', //red
                'borderColor' => '#f56954', //red
                'allDay' => true
            ]);

        }

        return view('timeline.index', compact( 'permissions', 'events'));

    }

    public function getTimelineCurrent()
    {
        $date_current = Carbon::now('America/Lima')->addDay()->format('Y-m-d');

        $lastTimeline = Timeline::where('date', $date_current)->first();

        $date = '';
        if ( isset($lastTimeline) )
        {
            $date = $lastTimeline->date->format('Y-m-d');
        }

        if ($date_current == $date)
        {
            return response()->json(['message' => 'Ya existe un cronograma para mañana.', 'error'=>1], 200);
        }
        //dd($date_current . ' -  ' . $date);
        $timeline = Timeline::create([
            'date' => $date_current,
        ]);

        return response()->json(['message' => 'Redireccionando ...', 'error'=>0, 'url' => route('create.timeline', $timeline->id)], 200);

        //dd($date_current . ' -  ' . $date);
        //
    }

    public function manageTimeline($timeline_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quotes = Quote::where('state_active','open')
            ->where('raise_status',1)
            ->orderBy('created_at', 'desc')
            ->get();

        $timeline_areas = TimelineArea::select('id', 'area')->get();

        $workers = Worker::select('id', 'first_name', 'last_name')->get();

        $timeline = Timeline::with('responsibleUser')
            ->with(['activities' => function ($query) {
                $query->with('quote')
                ->with(['activity_workers' => function ($query) {
                    $query->with('worker');
                }]);
            }])
            ->find($timeline_id);

        return view('timeline.manage', compact( 'permissions', 'workers', 'timeline', 'quotes', 'timeline_areas'));

    }

    public function createTimeline($timeline_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quotes = Quote::where('state_active','open')
            ->where('raise_status',1)
            ->orderBy('created_at', 'desc')
            ->get();

        $workers = Worker::select('id', 'first_name', 'last_name')
            ->where('area_worker_id', 4)
            ->where('enable', 1)
            ->get();

        $supervisors = Worker::select('id', 'first_name', 'last_name')
            ->whereIn('id', [2, 21])
            ->where('enable', 1)
            ->get();

        $timeline = Timeline::with(['works' => function ($query) {
                $query->with('quote')
                    ->with(['phases' => function ($query) {
                        $query->with('equipment')
                            ->with(['tasks' => function ($query) {
                            $query->with('performer');
                            $query->with(['task_workers' => function ($query) {
                                $query->with('worker');
                            }]);
                        }]);
                    }]);
            }])
            ->find($timeline_id);

        return view('timeline.create', compact( 'permissions', 'workers', 'timeline', 'quotes', 'supervisors'));

    }

    public function showTimeline($timeline_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quotes = Quote::where('state_active','open')
            ->where('raise_status',1)
            ->orderBy('created_at', 'desc')
            ->get();

        $timeline_areas = TimelineArea::select('id', 'area')->get();

        $workers = Worker::select('id', 'first_name', 'last_name')->get();

        $timeline = Timeline::with('responsibleUser')
            ->with(['activities' => function ($query) {
                $query->with('quote')->with('performer_worker')
                    ->with(['activity_workers' => function ($query) {
                        $query->with('worker');
                    }]);
            }])
            ->find($timeline_id);

        return view('timeline.show', compact( 'permissions', 'workers', 'timeline', 'quotes', 'timeline_areas'));

    }

    public function registerProgressTimeline($timeline_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quotes = Quote::where('state_active','open')
            ->where('raise_status',1)
            ->orderBy('created_at', 'desc')
            ->get();

        $timeline_areas = TimelineArea::select('id', 'area')->get();

        $workers = Worker::select('id', 'first_name', 'last_name')->get();

        $timeline = Timeline::with('responsibleUser')
            ->with(['activities' => function ($query) {
                $query->with('quote')->with('performer_worker')
                    ->with(['activity_workers' => function ($query) {
                        $query->with('worker');
                    }]);
            }])
            ->find($timeline_id);

        return view('timeline.progress', compact( 'permissions', 'workers', 'timeline', 'quotes', 'timeline_areas'));

    }

    public function createNewActivity( $id_timeline )
    {
        DB::beginTransaction();
        try {
            $activity = Activity::create([
                'timeline_id' => $id_timeline,
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Actividad creada con éxito.', 'activity' => $activity], 200);

    }

    public function createNewWork( $id_timeline )
    {
        DB::beginTransaction();
        try {
            $work = Work::create([
                'timeline_id' => $id_timeline,
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Trabajo creado con éxito.', 'work' => $work], 200);

    }

    public function editWork( Request $request, $work_id, $timeline_id )
    {
        //dump($request);
        //dump($request->input('quote_id'));
        //dd($request->input('quote_description'));
        DB::beginTransaction();
        try {

            if ( $request->get('quote_id') != 0 || $request->get('quote_id') != '' )
            {
                $works = Work::where('timeline_id', $timeline_id)
                    ->where('quote_id',$request->get('quote_id') )
                    ->where('id', '<>', $work_id )
                    ->get();

                if ( count($works) > 0 )
                {
                    return response()->json(['message' => 'Ya existe este trabajo registrado para este cronograma.'], 422);
                }
            }
            $work = Work::find($work_id);
            $work->quote_id = ($request->get('quote_id') == 0 || $request->get('quote_id') == '') ? null: $request->get('quote_id');
            $work->description_quote = ($request->get('quote_description') == '')? null: $request->get('quote_description');
            $work->supervisor_id = ($request->get('supervisor_id') == '' || $request->get('supervisor_id') == 0)? null: $request->get('supervisor_id');
            $work->save();

            $work_send = Work::find($work_id);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Trabajo editado con éxito.', 'work' => $work_send], 200);

    }

    public function createNewPhase( $work_id )
    {
        DB::beginTransaction();
        try {
            $work = Work::find($work_id);

            $phase = Phase::create([
                'timeline_id' => $work->timeline_id,
                'work_id' => $work->id,
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Etapa creada con éxito.', 'phase' => $phase], 200);

    }

    public function editPhase( Request $request, $phase_id, $timeline_id )
    {
        //dump($request);
        //dump($request->input('quote_id'));
        //dd($request->input('quote_description'));
        DB::beginTransaction();
        try {

            $phase = Phase::find($phase_id);
            $phase->description = ($request->get('phase_description') == '')? null: $request->get('phase_description');
            $phase->equipment_id = ($request->get('phase_equipment') == '' || $request->get('phase_equipment') == 0)? null: $request->get('phase_equipment');
            $phase->save();

            $work_send = Phase::with('equipment')->find($phase_id);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Etapa editada con éxito.', 'phase' => $work_send], 200);

    }

    public function createNewTask( $phase_id )
    {
        DB::beginTransaction();
        try {
            $phase = Phase::find($phase_id);

            $work = Work::find($phase->work_id);

            $timeline = Timeline::find($work->timeline_id);

            $task = Task::create([
                'timeline_id' => $timeline->id,
                'quote_id' => $timeline->quote_id,
                'work_id' => $work->id,
                'phase_id' => $phase->id,
            ]);

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tarea creada con éxito.', 'task' => $task], 200);

    }

    public function saveTask( Request $request, $id_task )
    {
        DB::beginTransaction();
        try {
            $task_id = $id_task;
            $activities = $request->input('task');

            foreach ( $activities as $activity )
            {
                $actividad = Task::find($activity['task_id']);
                $actividad->activity = $activity['activity'];
                $actividad->progress = ($activity['progress'] == '') ? 0: (int) $activity['progress'];
                $actividad->performer_id = ($activity['performer'] == '') ? null: (int) $activity['performer'];
                $actividad->save();

                // Borramos los trabajadores
                $task_workers = TaskWorker::where('task_id', $task_id)->get();

                foreach ( $task_workers as $worker )
                {
                    $worker->delete();
                }

                // Ahora creamos los trabajadores
                $workers = $activity['workers'];

                foreach ( $workers as $worker )
                {
                    $task_worker = TaskWorker::create([
                        'task_id' => $actividad->id,
                        'worker_id' => (int)$worker['worker'],
                        'hours_plan' => (float) $worker['hoursplan'],
                        'hours_real' => (float) $worker['hoursreal'],
                        'quantity_plan' => (float) $worker['quantityplan'],
                        'quantity_real' => (float) $worker['quantityreal'],
                    ]);
                }

            }

            $activity = Task::find($id_task);
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tarea guardada con éxito.', 'task' => $activity], 200);

    }

    public function deleteTask( $id_task )
    {
        DB::beginTransaction();
        try {
            $activity = Task::find($id_task);

            $acts = Task::where('parent_task_id', $activity->id)->get();

            foreach ( $acts as $act )
            {
                $act->parent_task_id = null;
                $act->save();
            }

            $activity_parent = Task::where('id', $activity->parent_task_id)
                ->first();

            if ( isset($activity_parent) )
            {
                $activity_parent->assign_status = false;
                $activity_parent->save();
            }

            $activity_workers = TaskWorker::where('task_id', $activity->id)->get();

            if ( count($activity_workers) > 0 )
            {
                foreach ( $activity_workers as $worker )
                {
                    $worker->delete();
                }
            }

            $activity->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Tarea eliminada con éxito.'], 200);

    }

    public function deletePhase( $id_phase )
    {
        DB::beginTransaction();
        try {

            $phase = Phase::find($id_phase);

            foreach ($phase->tasks as $task) {
                $activity = Task::find($task->id);

                $acts = Task::where('parent_task_id', $activity->id)->get();

                foreach ( $acts as $act )
                {
                    $act->parent_task_id = null;
                    $act->save();
                }

                $activity_parent = Task::where('id', $activity->parent_task_id)
                    ->first();

                if ( isset($activity_parent) )
                {
                    $activity_parent->assign_status = false;
                    $activity_parent->save();
                }

                $activity_workers = TaskWorker::where('task_id', $activity->id)->get();

                if ( count($activity_workers) > 0 )
                {
                    foreach ( $activity_workers as $worker )
                    {
                        $worker->delete();
                    }
                }

                $activity->delete();
            }

            $phase->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Etapa eliminada con éxito.'], 200);

    }

    public function deleteWork( $id_work )
    {
        DB::beginTransaction();
        try {

            $work = Work::find($id_work);

            foreach ( $work->phases as $phase ){
                $phase = Phase::find($phase->id);

                foreach ($phase->tasks as $task) {

                    $activity = Task::find($task->id);

                    $acts = Task::where('parent_task_id', $activity->id)->get();

                    foreach ( $acts as $act )
                    {
                        $act->parent_task_id = null;
                        $act->save();
                    }

                    $activity_parent = Task::where('id', $activity->parent_task_id)
                        ->first();

                    if ( isset($activity_parent) )
                    {
                        $activity_parent->assign_status = false;
                        $activity_parent->save();
                    }

                    $activity_workers = TaskWorker::where('task_id', $activity->id)->get();

                    if ( count($activity_workers) > 0 )
                    {
                        foreach ( $activity_workers as $worker )
                        {
                            $worker->delete();
                        }
                    }

                    $activity->delete();
                }

                $phase->delete();
            }

            $work->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Trabajo eliminada con éxito.'], 200);

    }

    public function reviewTimeline($timeline_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quotes = Quote::where('state_active','open')
            ->where('raise_status',1)
            ->orderBy('created_at', 'desc')
            ->get();

        $workers = Worker::select('id', 'first_name', 'last_name')->get();

        $timeline = Timeline::with(['works' => function ($query) {
            $query->with('quote')
                ->with(['phases' => function ($query) {
                    $query->with(['tasks' => function ($query) {
                        $query->with('performer');
                        $query->with(['task_workers' => function ($query) {
                            $query->with('worker');
                        }]);
                    }]);
                }]);
        }])
            ->find($timeline_id);

        $fecha_actual = Carbon::now('America/Lima');
        $fecha_max = $timeline->date->addHours(7);
        $fecha_min = $timeline->date->subHours(23);
        $active_edit = $fecha_actual->betweenIncluded($fecha_min, $fecha_max);
        //$active_edit = true;
        //dump('Actual -> '.$fecha_actual);
        //dump('Maxima -> '.$fecha_max);
        //dump('Minima -> '.$fecha_min);
        //dump('Real -> '.$timeline->date);
        //dump($active_edit);

        return view('timeline.review', compact( 'active_edit','permissions', 'workers', 'timeline', 'quotes'));

    }

    public function getEquipmentsWorkPhase(Request $request)
    {
        $phase_id = $request->get('phase_id');

        $phase = Phase::find($phase_id);

        $work = Work::find($phase->work_id);

        $equipments = null;

        $equipmentSelected = $phase->equipment_id;

        if (isset($work->quote_id))
        {
            $equipments = Equipment::where('quote_id', $work->quote_id)->get();
        }

        return response()->json([
            "equipments" => $equipments,
            "equipmentSelected" => $equipmentSelected
        ], 200);

    }

    public function checkProgressTimeline($timeline_id)
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        $quotes = Quote::where('state_active','open')
            ->where('raise_status',1)
            ->orderBy('created_at', 'desc')
            ->get();

        $workers = Worker::select('id', 'first_name', 'last_name')->get();

        $timeline = Timeline::with(['works' => function ($query) {
            $query->with('quote')
                ->with(['phases' => function ($query) {
                    $query->with(['tasks' => function ($query) {
                        $query->with('performer');
                        $query->with(['task_workers' => function ($query) {
                            $query->with('worker');
                        }]);
                    }]);
                }]);
        }])
            ->find($timeline_id);

        return view('timeline.saveProgress', compact( 'permissions', 'workers', 'timeline', 'quotes'));

    }

    public function saveProgressTask( Request $request, $id_task )
    {
        DB::beginTransaction();
        try {
            $task_id = $id_task;
            $tasks = $request->input('task');

            foreach ( $tasks as $task )
            {
                $tarea = Task::find($task['task_id']);
                $tarea->progress = ($task['progress'] == '') ? 0: (int) $task['progress'];
                $tarea->save();

                // Ahora creamos los trabajadores
                $workers = $task['workers'];

                $quantityPlan = 0;
                $quantityReal = 0;
                foreach ( $workers as $worker )
                {
                    $task_worker = TaskWorker::where('task_id', $tarea->id)
                        ->where('worker_id', $worker['worker'])->first();
                    $task_worker->hours_real = ($worker['hoursreal'] == '') ? 0: (float) $worker['hoursreal'];
                    $task_worker->quantity_real = ($worker['quantityreal'] == '') ? 0: (float) $worker['quantityreal'];
                    $task_worker->save();

                    $quantityPlan = $quantityPlan + (($worker['quantityplan'] == '') ? 0: (float) $worker['quantityplan']);
                    $quantityReal = $quantityReal + (($worker['quantityreal'] == '') ? 0: (float) $worker['quantityreal']);
                }

                $progress = round((($quantityReal / $quantityPlan) * 100), 2);

                if ( $progress > 100 )
                {
                    return response()->json(['message' => 'No puede registrar progresos mas de 100%'], 422);
                }

                $tarea->progress = $progress;
                $tarea->save();

            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Avance registrado con éxito.', 'progress' => $progress], 200);

    }

    public function assignTaskToTimeline( $id_task, $id_timeline )
    {
        DB::beginTransaction();
        try {

            $task = Task::find($id_task);
            $task->assign_status = true;
            $task->save();

            $timeline = Timeline::find($id_timeline);

            // Si el trabajo cotizacion pertenece a este timeline
            $work_repeat = Work::where('timeline_id', $id_timeline)
                ->where('quote_id', $task->work->quote_id)->first();

            if ( isset($work_repeat) )
            {
                // Verificar si ya existe una phase repetida
                $phase_repeat = Phase::where('timeline_id', $id_timeline)
                    ->where('work_id', $work_repeat->id)
                    ->where('description', $task->phase->description)
                    ->first();

                if ( isset( $phase_repeat ) )
                {
                    // Solo creamos la actividad
                    $act = Task::create([
                        'timeline_id' => $id_timeline,
                        'work_id' => $work_repeat->id,
                        'phase_id' => $phase_repeat->id,
                        'quote_id' => $work_repeat->quote_id,
                        'performer_id' => $task->performer_id,
                        'parent_task_id' => $id_task,
                        'activity' => $task->activity,
                        'progress' => $task->progress,

                    ]);

                    $activity_workers = TaskWorker::where('task_id', $task->id)->get();

                    if ( count($activity_workers) > 0 )
                    {
                        foreach ( $activity_workers as $worker )
                        {
                            $activity_worker = TaskWorker::create([
                                'task_id' => $act->id,
                                'worker_id' => $worker->worker_id,
                                'hours_plan' => $worker->hours_plan,
                                'hours_real' => $worker->hours_real,
                            ]);
                        }
                    }
                } else {
                    // Solo creamos la fase y la actividad
                    $phase = Phase::create([
                        'timeline_id' => $id_timeline,
                        'work_id' => $work_repeat->id,
                        'description' => $task->phase->description
                    ]);
                    $act = Task::create([
                        'timeline_id' => $id_timeline,
                        'work_id' => $work_repeat->id,
                        'phase_id' => $phase->id,
                        'quote_id' => $work_repeat->quote_id,
                        'performer_id' => $task->performer_id,
                        'parent_task_id' => $task->id,
                        'activity' => $task->activity,
                        'progress' => $task->progress,

                    ]);

                    $activity_workers = TaskWorker::where('task_id', $task->id)->get();

                    if ( count($activity_workers) > 0 )
                    {
                        foreach ( $activity_workers as $worker )
                        {
                            $activity_worker = TaskWorker::create([
                                'task_id' => $act->id,
                                'worker_id' => $worker->worker_id,
                                'hours_plan' => $worker->hours_plan,
                                'hours_real' => $worker->hours_real,
                            ]);
                        }
                    }
                }

            } else {
                // Creamos el work, phase y actividad
                $work = Work::create([
                    'timeline_id' => $id_timeline,
                    'quote_id' => $task->work->quote_id,
                    'description_quote' => $task->work->description_quote,
                ]);

                $phase = Phase::create([
                    'timeline_id' => $id_timeline,
                    'work_id' => $work->id,
                    'description' => $task->phase->description
                ]);

                $act = Task::create([
                    'timeline_id' => $id_timeline,
                    'work_id' => $work->id,
                    'phase_id' => $phase->id,
                    'quote_id' => $work->quote_id,
                    'performer_id' => $task->performer_id,
                    'parent_task_id' => $task->id,
                    'activity' => $task->activity,
                    'progress' => $task->progress,

                ]);

                $activity_workers = TaskWorker::where('task_id', $task->id)->get();

                if ( count($activity_workers) > 0 )
                {
                    foreach ( $activity_workers as $worker )
                    {
                        $activity_worker = TaskWorker::create([
                            'task_id' => $act->id,
                            'worker_id' => $worker->worker_id,
                            'hours_plan' => $worker->hours_plan,
                            'hours_real' => $worker->hours_real,
                        ]);
                    }
                }
            }

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Actividad asignada al cronograma con éxito.'], 200);

    }

    public function downloadTimelinePrincipal( $timeline_id )
    {
        $timeline = Timeline::with(['works' => function ($query) {
            $query->with('quote')
                ->with(['phases' => function ($query) {
                    $query->with(['tasks' => function ($query) {
                        $query->with('performer');
                        $query->with(['task_workers' => function ($query) {
                            $query->with('worker');
                        }]);
                    }]);
                }]);
        }])
            ->find($timeline_id);

        $arrayTasks = [];
        foreach ( $timeline->tasks as $task )
        {
            $workers = count($task->task_workers);
            if ( $workers > 0 )
            {
                $name_supervisor = 'No tiene';
                if ( $task->performer_id == null )
                {
                    // Revisar el supervisor general
                    if ( $task->work->supervisor_id != null )
                    {
                        $name_supervisor = $task->work->supervisor->first_name.' '.$task->work->supervisor->last_name;
                    }
                } else {
                    $name_supervisor = $task->performer->first_name.' '.$task->performer->last_name;
                }


                foreach ( $task->task_workers as $task_worker )
                {
                    array_push( $arrayTasks, [
                        'quote' => $task->work->description_quote,
                        'phase' => $task->phase->description,
                        'task' => $task->activity,
                        'performer' => $name_supervisor,
                        'progress' => ($task->progress==0 || $task->progress==null || $task->progress=='') ? '': $task->progress,
                        'worker' => $task_worker->worker->first_name.' '.$task_worker->worker->last_name,
                        'hours_plan' => ($task_worker->hours_plan==0 || $task_worker->hours_plan==null || $task_worker->hours_plan=='') ? '': $task_worker->hours_plan,
                        'hours_real' => ($task_worker->hours_real==0 || $task_worker->hours_real==null || $task_worker->hours_real=='') ? '': $task_worker->hours_real,
                        'quantity_plan' => ($task_worker->quantity_plan==0 || $task_worker->quantity_plan==null || $task_worker->quantity_plan=='') ? '': $task_worker->quantity_plan,
                        'quantity_real' => ($task_worker->quantity_real==0 || $task_worker->quantity_real==null || $task_worker->quantity_real=='') ? '': $task_worker->quantity_real,

                    ] );
                }
            } else {
                $name_supervisor = 'No tiene';
                if ( $task->performer_id == null )
                {
                    // Revisar el supervisor general
                    if ( $task->work->supervisor_id != null )
                    {
                        $name_supervisor = $task->work->supervisor->first_name.' '.$task->work->supervisor->last_name;
                    }
                } else {
                    $name_supervisor = $task->performer->first_name.' '.$task->performer->last_name;
                }
                array_push( $arrayTasks, [
                    'quote' => $task->work->description_quote,
                    'phase' => $task->phase->description,
                    'task' => $task->activity,
                    'performer' => $name_supervisor,
                    'progress' => ($task->progress==0 || $task->progress==null || $task->progress=='') ? '': $task->progress,
                    'worker' => '',
                    'hours_plan' => '',
                    'hours_real' => '',
                    'quantity_plan' => '',
                    'quantity_real' => '',
                ] );

            }


        }

        $tasks = [];

        $id = 1;

        for ( $i = 0; $i < count( $arrayTasks ); $i++ )
        {
            if ( $i == 0 )
            {
                array_push($tasks, [
                    'id' => $id,
                    'quote' => $arrayTasks[$i]['quote'],
                    'phase' => $arrayTasks[$i]['phase'],
                    'task' => $arrayTasks[$i]['task'],
                    'performer' => $arrayTasks[$i]['performer'],
                    'progress' => $arrayTasks[$i]['progress'],
                    'worker' => $arrayTasks[$i]['worker'],
                    'hours_plan' => $arrayTasks[$i]['hours_plan'],
                    'hours_real' => $arrayTasks[$i]['hours_real'],
                    'quantity_plan' => $arrayTasks[$i]['quantity_plan'],
                    'quantity_real' => $arrayTasks[$i]['quantity_real'],
                ]);
                $id = $id +1;
            } else {
                if ( $arrayTasks[$i]['quote'] == $arrayTasks[$i-1]['quote'] )
                {
                    array_push($tasks, [
                        'id' => '',
                        'quote' => $arrayTasks[$i]['quote'],
                        'phase' => $arrayTasks[$i]['phase'],
                        'task' => $arrayTasks[$i]['task'],
                        'performer' => $arrayTasks[$i]['performer'],
                        'progress' => $arrayTasks[$i]['progress'],
                        'worker' => $arrayTasks[$i]['worker'],
                        'hours_plan' => $arrayTasks[$i]['hours_plan'],
                        'hours_real' => $arrayTasks[$i]['hours_real'],
                        'quantity_plan' => $arrayTasks[$i]['quantity_plan'],
                        'quantity_real' => $arrayTasks[$i]['quantity_real'],
                    ]);

                } else {
                    array_push($tasks, [
                        'id' => $id,
                        'quote' => $arrayTasks[$i]['quote'],
                        'phase' => $arrayTasks[$i]['phase'],
                        'task' => $arrayTasks[$i]['task'],
                        'performer' => $arrayTasks[$i]['performer'],
                        'progress' => $arrayTasks[$i]['progress'],
                        'worker' => $arrayTasks[$i]['worker'],
                        'hours_plan' => $arrayTasks[$i]['hours_plan'],
                        'hours_real' => $arrayTasks[$i]['hours_real'],
                        'quantity_plan' => $arrayTasks[$i]['quantity_plan'],
                        'quantity_real' => $arrayTasks[$i]['quantity_real'],

                    ]);
                    $id = $id +1;
                }
            }

        }

        //dd($tasks);

        /*for ( $i = 0; $i < count( $tasks ); $i++ )
        {
            if ( $i != 0 )
            {
                dump($tasks[$i]['quote'] . ' ' . $tasks[$i-1]['quote']);
            } else {
                dump($tasks[$i]['quote']);
            }

        }
        dd($tasks);*/
        //dd($tasks);
        $title = 'PROGRAMACION DE ACTIVIDADES: '.$timeline->date->format('d-m-Y');

        //return Excel::download(new TimelinesExports($title, $tasks), 'cronogramas.xlsx');

        $view = view('exports.timelineExcel', compact('timeline', 'tasks', 'title'));

        $pdf = PDF::loadHTML($view);

        $name = 'Cronograma_' . $timeline->date->format('d-m-Y') . '.pdf';

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream($name);
    }

    public function downloadTimelineSupervisor( $timeline_id )
    {
        $timeline = Timeline::with(['works' => function ($query) {
            $query->with('quote')
                ->with(['phases' => function ($query) {
                    $query->with(['tasks' => function ($query) {
                        $query->with('performer');
                        $query->with(['task_workers' => function ($query) {
                            $query->with('worker');
                        }]);
                    }]);
                }]);
        }])
            ->find($timeline_id);

        $arrayTasks = [];
        foreach ( $timeline->tasks as $task )
        {
            $workers = count($task->task_workers);
            if ( $workers > 0 )
            {
                $name_supervisor = 'No tiene';
                if ( $task->performer_id == null )
                {
                    // Revisar el supervisor general
                    if ( $task->work->supervisor_id != null )
                    {
                        $name_supervisor = $task->work->supervisor->first_name.' '.$task->work->supervisor->last_name;
                    }
                } else {
                    $name_supervisor = $task->performer->first_name.' '.$task->performer->last_name;
                }


                foreach ( $task->task_workers as $task_worker )
                {
                    array_push( $arrayTasks, [
                        'quote' => $task->work->description_quote,
                        'phase' => $task->phase->description,
                        'task' => $task->activity,
                        'performer' => $name_supervisor,
                        'worker' => $task_worker->worker->first_name.' '.$task_worker->worker->last_name,
                        'hours_plan' => ($task_worker->hours_plan==0 || $task_worker->hours_plan==null || $task_worker->hours_plan=='') ? '': $task_worker->hours_plan,
                        'hours_real' => ($task_worker->hours_real==0 || $task_worker->hours_real==null || $task_worker->hours_real=='') ? '': $task_worker->hours_real,
                        'quantity_plan' => ($task_worker->quantity_plan==0 || $task_worker->quantity_plan==null || $task_worker->quantity_plan=='') ? '': $task_worker->quantity_plan,
                        'quantity_real' => ($task_worker->quantity_real==0 || $task_worker->quantity_real==null || $task_worker->quantity_real=='') ? '': $task_worker->quantity_real,

                    ] );
                }
            } else {
                $name_supervisor = 'No tiene';
                if ( $task->performer_id == null )
                {
                    // Revisar el supervisor general
                    if ( $task->work->supervisor_id != null )
                    {
                        $name_supervisor = $task->work->supervisor->first_name.' '.$task->work->supervisor->last_name;
                    }
                } else {
                    $name_supervisor = $task->performer->first_name.' '.$task->performer->last_name;
                }
                array_push( $arrayTasks, [
                    'quote' => $task->work->description_quote,
                    'phase' => $task->phase->description,
                    'task' => $task->activity,
                    'performer' => $name_supervisor,
                    'worker' => '',
                    'hours_plan' => '',
                    'hours_real' => '',
                    'quantity_plan' => '',
                    'quantity_real' => '',
                ] );

            }


        }

        $tasks = [];

        $id = 1;

        for ( $i = 0; $i < count( $arrayTasks ); $i++ )
        {
            if ( $i == 0 )
            {
                array_push($tasks, [
                    'id' => $id,
                    'quote' => $arrayTasks[$i]['quote'],
                    'phase' => $arrayTasks[$i]['phase'],
                    'task' => $arrayTasks[$i]['task'],
                    'performer' => $arrayTasks[$i]['performer'],
                    'worker' => $arrayTasks[$i]['worker'],
                    'hours_plan' => $arrayTasks[$i]['hours_plan'],
                    'hours_real' => $arrayTasks[$i]['hours_real'],
                    'quantity_plan' => $arrayTasks[$i]['quantity_plan'],
                    'quantity_real' => $arrayTasks[$i]['quantity_real'],
                ]);
                $id = $id +1;
            } else {
                if ( $arrayTasks[$i]['quote'] == $arrayTasks[$i-1]['quote'] )
                {
                    array_push($tasks, [
                        'id' => '',
                        'quote' => $arrayTasks[$i]['quote'],
                        'phase' => $arrayTasks[$i]['phase'],
                        'task' => $arrayTasks[$i]['task'],
                        'performer' => $arrayTasks[$i]['performer'],
                        'worker' => $arrayTasks[$i]['worker'],
                        'hours_plan' => $arrayTasks[$i]['hours_plan'],
                        'hours_real' => $arrayTasks[$i]['hours_real'],
                        'quantity_plan' => $arrayTasks[$i]['quantity_plan'],
                        'quantity_real' => $arrayTasks[$i]['quantity_real'],
                    ]);

                } else {
                    array_push($tasks, [
                        'id' => $id,
                        'quote' => $arrayTasks[$i]['quote'],
                        'phase' => $arrayTasks[$i]['phase'],
                        'task' => $arrayTasks[$i]['task'],
                        'performer' => $arrayTasks[$i]['performer'],
                        'worker' => $arrayTasks[$i]['worker'],
                        'hours_plan' => $arrayTasks[$i]['hours_plan'],
                        'hours_real' => $arrayTasks[$i]['hours_real'],
                        'quantity_plan' => $arrayTasks[$i]['quantity_plan'],
                        'quantity_real' => $arrayTasks[$i]['quantity_real'],

                    ]);
                    $id = $id +1;
                }
            }

        }

        //dd($tasks);

        /*for ( $i = 0; $i < count( $tasks ); $i++ )
        {
            if ( $i != 0 )
            {
                dump($tasks[$i]['quote'] . ' ' . $tasks[$i-1]['quote']);
            } else {
                dump($tasks[$i]['quote']);
            }

        }
        dd($tasks);*/
        //dd($tasks);
        $title = 'PROGRAMACION DE ACTIVIDADES: '.$timeline->date->format('d-m-Y');

        //return Excel::download(new TimelinesExports($title, $tasks), 'cronogramas.xlsx');

        $view = view('exports.timelineExcelSupervisor', compact('timeline', 'tasks', 'title'));

        $pdf = PDF::loadHTML($view);

        $name = 'Cronograma_' . $timeline->date->format('d-m-Y') . '.pdf';

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream($name);
    }

    public function downloadTimelineOperator( $timeline_id )
    {
        $timeline = Timeline::with(['works' => function ($query) {
            $query->with('quote')
                ->with(['phases' => function ($query) {
                    $query->with(['tasks' => function ($query) {
                        $query->with('performer');
                        $query->with(['task_workers' => function ($query) {
                            $query->with('worker');
                        }]);
                    }]);
                }]);
        }])
            ->find($timeline_id);

        $arrayTasks = [];
        foreach ( $timeline->tasks as $task )
        {
            $workers = count($task->task_workers);
            if ( $workers > 0 )
            {
                $name_supervisor = 'No tiene';
                if ( $task->performer_id == null )
                {
                    // Revisar el supervisor general
                    if ( $task->work->supervisor_id != null )
                    {
                        $name_supervisor = $task->work->supervisor->first_name.' '.$task->work->supervisor->last_name;
                    }
                } else {
                    $name_supervisor = $task->performer->first_name.' '.$task->performer->last_name;
                }


                foreach ( $task->task_workers as $task_worker )
                {
                    array_push( $arrayTasks, [
                        'quote' => $task->work->description_quote,
                        'phase' => $task->phase->description,
                        'task' => $task->activity,
                        'performer' => $name_supervisor,
                        'worker' => $task_worker->worker->first_name.' '.$task_worker->worker->last_name,
                        'hours_plan' => ($task_worker->hours_plan==0 || $task_worker->hours_plan==null || $task_worker->hours_plan=='') ? '': $task_worker->hours_plan,
                        'quantity_plan' => ($task_worker->quantity_plan==0 || $task_worker->quantity_plan==null || $task_worker->quantity_plan=='') ? '': $task_worker->quantity_plan,

                    ] );
                }
            } else {
                $name_supervisor = 'No tiene';
                if ( $task->performer_id == null )
                {
                    // Revisar el supervisor general
                    if ( $task->work->supervisor_id != null )
                    {
                        $name_supervisor = $task->work->supervisor->first_name.' '.$task->work->supervisor->last_name;
                    }
                } else {
                    $name_supervisor = $task->performer->first_name.' '.$task->performer->last_name;
                }
                array_push( $arrayTasks, [
                    'quote' => $task->work->description_quote,
                    'phase' => $task->phase->description,
                    'task' => $task->activity,
                    'performer' => $name_supervisor,
                    'worker' => '',
                    'hours_plan' => '',
                    'quantity_plan' => '',
                ] );

            }


        }

        $tasks = [];

        $id = 1;

        for ( $i = 0; $i < count( $arrayTasks ); $i++ )
        {
            if ( $i == 0 )
            {
                array_push($tasks, [
                    'id' => $id,
                    'quote' => $arrayTasks[$i]['quote'],
                    'phase' => $arrayTasks[$i]['phase'],
                    'task' => $arrayTasks[$i]['task'],
                    'performer' => $arrayTasks[$i]['performer'],
                    'worker' => $arrayTasks[$i]['worker'],
                    'hours_plan' => $arrayTasks[$i]['hours_plan'],
                    'quantity_plan' => $arrayTasks[$i]['quantity_plan'],

                ]);
                $id = $id +1;
            } else {
                if ( $arrayTasks[$i]['quote'] == $arrayTasks[$i-1]['quote'] )
                {
                    array_push($tasks, [
                        'id' => '',
                        'quote' => $arrayTasks[$i]['quote'],
                        'phase' => $arrayTasks[$i]['phase'],
                        'task' => $arrayTasks[$i]['task'],
                        'performer' => $arrayTasks[$i]['performer'],
                        'worker' => $arrayTasks[$i]['worker'],
                        'hours_plan' => $arrayTasks[$i]['hours_plan'],
                        'quantity_plan' => $arrayTasks[$i]['quantity_plan'],
                    ]);

                } else {
                    array_push($tasks, [
                        'id' => $id,
                        'quote' => $arrayTasks[$i]['quote'],
                        'phase' => $arrayTasks[$i]['phase'],
                        'task' => $arrayTasks[$i]['task'],
                        'performer' => $arrayTasks[$i]['performer'],
                        'worker' => $arrayTasks[$i]['worker'],
                        'hours_plan' => $arrayTasks[$i]['hours_plan'],
                        'quantity_plan' => $arrayTasks[$i]['quantity_plan'],

                    ]);
                    $id = $id +1;
                }
            }

        }

        //dd($tasks);

        /*for ( $i = 0; $i < count( $tasks ); $i++ )
        {
            if ( $i != 0 )
            {
                dump($tasks[$i]['quote'] . ' ' . $tasks[$i-1]['quote']);
            } else {
                dump($tasks[$i]['quote']);
            }

        }
        dd($tasks);*/
        //dd($tasks);
        $title = 'PROGRAMACION DE ACTIVIDADES: '.$timeline->date->format('d-m-Y');

        //return Excel::download(new TimelinesExports($title, $tasks), 'cronogramas.xlsx');

        $view = view('exports.timelineExcelOperator', compact('timeline', 'tasks', 'title'));

        $pdf = PDF::loadHTML($view);

        $name = 'Cronograma_' . $timeline->date->format('d-m-Y') . '.pdf';

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream($name);
    }

    public function checkTimelineForCreate($date)
    {
        $date_current = Carbon::now('America/Lima')->format('Y-m-d');

        $date_yesterday = Carbon::now('America/Lima')->subDay()->format('Y-m-d');

        $date_tomorrow = Carbon::now('America/Lima')->addDay()->format('Y-m-d');

        $date_show = Carbon::createFromFormat('Y-m-d', $date);

        if ( $date == $date_current )
        {
            // Si es hoy
            $timeline = Timeline::where('date', $date)->first();
            if ( isset($timeline) )
            {
                // Si existe cronograma, redireccionar al manage
                return response()->json([
                    'message' => 'Redireccionando ...',
                    'url' => route('review.timeline', $timeline->id),
                    'res' => 1
                ], 200);

            } else {
                // Si no hay cronograma, preguntar si desea crear
                return response()->json([
                    'message' => '¿Desea crear un cronograma con la fecha '.$date_show->format('d/m/Y').'?',
                    'url' => '',
                    'res' => 2
                ], 200);
            }
        } else {
            if ( $date <= $date_yesterday )
            {
                $timeline = Timeline::where('date', $date)->first();
                if ( isset($timeline) )
                {
                    // Si existe cronograma, redireccionar al show
                    return response()->json([
                        'message' => 'Redireccionando ...',
                        'url' => route('review.timeline', $timeline->id),
                        'res' => 3
                    ], 200);

                } else {
                    // Si no hay cronograma, preguntar si desea crear
                    return response()->json([
                        'message' => '¿Desea crear un cronograma con la fecha '.$date_show->format('d/m/Y').'?',
                        'url' => '',
                        'res' => 4
                    ], 200);
                }
            } else {
                if ( $date >= $date_tomorrow )
                {
                    $timeline = Timeline::where('date', $date)->first();
                    if ( isset($timeline) )
                    {
                        // Si existe cronograma, redireccionar al show
                        return response()->json([
                            'message' => 'Redireccionando ...',
                            'url' => route('review.timeline', $timeline->id),
                            'res' => 5
                        ], 200);

                    } else {
                        // NO se puede crear
                        return response()->json([
                            'message' => 'No se puede crear cronogramas futuros.',
                            'url' => '',
                            'res' => 6
                        ], 200);
                    }


                }
            }
        }
        return response()->json([
            'message' => 'Algo sucedio en el servidor.',
            'url' => '',
            'res' => 7
        ], 200);
    }

    public function getTimelineForget($date)
    {
        $timeline = Timeline::create([
            'date' => $date,
        ]);

        return response()->json(['message' => 'Redireccionando ...', 'error'=>0, 'url' => route('create.timeline', $timeline->id)], 200);

    }

    public function deleteActivity( $id_activity )
    {
        DB::beginTransaction();
        try {
            $activity = Activity::find($id_activity);

            $activity_parent = Activity::where('id', $activity->parent_activity)
                ->first();

            if ( isset($activity_parent) )
            {
                $activity_parent->assign_status = false;
                $activity_parent->save();
            }

            $activity_workers = ActivityWorker::where('activity_id', $activity->id)->get();

            if ( count($activity_workers) > 0 )
            {
                foreach ( $activity_workers as $worker )
                {
                    $worker->delete();
                }
            }

            $activity->delete();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Actividad eliminada con éxito.', 'activity' => $activity], 200);


    }

    public function saveActivity( Request $request, $id_activity )
    {
        DB::beginTransaction();
        try {
            $activity_id = $id_activity;
            $activities = $request->input('activity');

            foreach ( $activities as $activity )
            {
                $actividad = Activity::find($activity['activity_id']);
                $actividad->quote_id = ($activity['quote_id'] == '') ? null: (int) $activity['quote_id'];
                $actividad->description_quote = $activity['quote_description'];
                $actividad->activity = $activity['activity'];
                $actividad->progress = ($activity['progress'] == '') ? 0: (int) $activity['progress'];
                $actividad->phase = $activity['phase'];
                $actividad->performer = ($activity['performer'] == '') ? null: (int) $activity['performer'];
                $actividad->save();

                // Borramos los trabajadores
                $activity_workers = ActivityWorker::where('activity_id', $activity_id)->get();

                foreach ( $activity_workers as $worker )
                {
                    $worker->delete();
                }

                // Ahora creamos los trabajadores
                $workers = $activity['workers'];

                foreach ( $workers as $worker )
                {
                    $activity_worker = ActivityWorker::create([
                        'activity_id' => $actividad->id,
                        'worker_id' => (int)$worker['worker'],
                        'hours_plan' => (float) $worker['hoursplan'],
                        'hours_real' => (float) $worker['hoursreal'],
                    ]);
                }

            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Actividad guardada con éxito.', 'activity' => $activity], 200);


    }

    public function saveProgressActivity( Request $request, $id_activity )
    {
        DB::beginTransaction();
        try {
            $activity_id = $id_activity;
            $activities = $request->input('activity');

            foreach ( $activities as $activity )
            {
                $actividad = Activity::find($activity['activity_id']);
                $actividad->progress = ($activity['progress'] == '') ? 0: (int) $activity['progress'];
                $actividad->save();

                // Ahora creamos los trabajadores
                $workers = $activity['workers'];

                foreach ( $workers as $worker )
                {
                    $activity_worker = ActivityWorker::where('activity_id', $actividad->id)
                        ->where('id', $worker['worker'])->first();
                    $activity_worker->hours_real = ($worker['hoursreal'] == '') ? 0: (int) $worker['hoursreal'];
                    $activity_worker->save();
                }

            }
            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Avance registrado con éxito.'], 200);

    }

    public function getActivityForget($id_timeline)
    {
        $timeline = Timeline::find($id_timeline);

        $tasks = Task::where('progress', '<', 100)
            ->where('assign_status', 0)
            ->where('timeline_id', '<>',$timeline->id)
            ->get();

        $tasksArray = [];

        foreach ($tasks as $task) {
            array_push($tasksArray, [
                'task_id' => $task->id,
                'quote_id' => $task->work->quote_id,
                'description_quote' => $task->work->description_quote,
                'task' => $task->activity,
                'progress' => $task->progress,
                'phase' => $task->phase->description
            ]);
        }

        /*$timeline = Timeline::find($id_timeline);

        $timelines = Timeline::where('date', '<', $timeline->date)->get();

        $activitiesArray = [];

        foreach ( $timelines as $timeline )
        {
            foreach ( $timeline->works as $work )
            {
                foreach ( $work->phases as $phase )
                {
                    foreach ( $phase->tasks as $task )
                    {

                    }
                }
            }
            $tasks = Task::where('progress', '<', 100)
                ->where('assign_status', 0)
                ->where('timeline_id', $timeline->id)
                ->get();

            foreach ( $tasks as $task )
            {
                array_push($activitiesArray, [
                    'activity_id' => $activity->id,
                    'quote_id' => $activity->quote_id,
                    'description_quote' => $activity->description_quote,
                    'activity' => $activity->activity,
                    'progress' => $activity->progress,
                    'phase' => $activity->phase,
                    'performer' => $activity->performer
                ]);
            }

        }*/
        return response()->json(['tasks' => $tasksArray], 200);

    }

    public function assignActivityToTimeline( $id_activity, $id_timeline )
    {
        DB::beginTransaction();
        try {

            $activity = Activity::find($id_activity);
            $activity->assign_status = true;
            $activity->save();

            $timeline = Timeline::find($id_timeline);

            $actividad = Activity::create([
                'timeline_id' => $timeline->id,
                'quote_id' => $activity->quote_id,
                'description_quote' => $activity->description_quote,
                'activity' => $activity->activity,
                'progress' => $activity->progress,
                'phase' => $activity->phase,
                'performer' => $activity->performer,
                'parent_activity' => $activity->id
            ]);

            $activity_workers = ActivityWorker::where('activity_id', $activity->id)->get();

            if ( count($activity_workers) > 0 )
            {
                foreach ( $activity_workers as $worker )
                {
                    $activity_worker = ActivityWorker::create([
                        'activity_id' => $actividad->id,
                        'worker_id' => $worker->worker_id,
                        'hours_plan' => $worker->hours_plan,
                        'hours_real' => $worker->hours_real,
                    ]);
                }
            }

            $sendActivity = Activity::where('id', $actividad->id)
                ->with('quote')
                ->with('performer_worker')
                ->with('timeline')
                ->with(['activity_workers' => function ($query) {
                    $query->with('worker');
                }])->get();

            DB::commit();
        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Actividad asignada al cronograma con éxito.', 'activity' => $sendActivity], 200);

    }

    public function printTimeline( $timeline_id )
    {
        $timeline = Timeline::with('responsibleUser')
            ->with(['activities' => function ($query) {
                $query->with('quote')->with('performer_worker')
                    ->with(['activity_workers' => function ($query) {
                        $query->with('worker');
                    }]);
            }])
            ->find($timeline_id);

        /*foreach ( $timeline->activities as $activity )
        {
            dd(count($activity->activity_workers));
        }*/


        $view = view('exports.timelinePDF', compact('timeline'));

        $pdf = PDF::loadHTML($view);

        $name = 'Cronograma_' . $timeline->date->format('d-m-Y') . '.pdf';

        return $pdf->stream($name);
    }

    public function getInfoWork( $id )
    {
        $work = Work::find($id);

        return response()->json([
            'quote_id' => $work->quote_id,
            'quote_description' => $work->description_quote,
            'supervisor_id' => $work->supervisor_id,
        ], 200);

    }
}
