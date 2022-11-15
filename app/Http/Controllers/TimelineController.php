<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ActivityWorker;
use App\Quote;
use App\Timeline;
use App\TimelineArea;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        return response()->json(['message' => 'Redireccionando ...', 'error'=>0, 'url' => route('manage.timeline', $timeline->id)], 200);

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
                    'url' => route('manage.timeline', $timeline->id),
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
                        'url' => route('show.timeline', $timeline->id),
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
                            'url' => route('manage.timeline', $timeline->id),
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

        return response()->json(['message' => 'Redireccionando ...', 'error'=>0, 'url' => route('manage.timeline', $timeline->id)], 200);

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

        $timelines = Timeline::where('date', '<', $timeline->date)->get();

        $activitiesArray = [];

        foreach ( $timelines as $timeline )
        {
            $activities = Activity::where('progress', '<', 100)
                ->where('assign_status', 0)
                ->where('timeline_id', $timeline->id)
                ->get();

            foreach ( $activities as $activity )
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

        }
        return response()->json(['activities' => $activitiesArray], 200);

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
}
