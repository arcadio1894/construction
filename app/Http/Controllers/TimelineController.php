<?php

namespace App\Http\Controllers;

use App\Timeline;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller
{
    public function showTimelines()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('timeline.index', compact( 'permissions'));

    }

    public function getTimelineCurrent()
    {
        $date_current = Carbon::now('America/Lima')->format('Y-m-d');

        $lastTimeline = Timeline::withTrashed()->latest('id')->first();;

        $date = '';
        if ( isset($lastTimeline) )
        {
            $date = $lastTimeline->date;
        }

        if ($date_current == $date)
        {
            return response()->json(['message' => 'Ya existe un cronograma para este día.', 'error'=>1], 200);
        }

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

        $timeline = Timeline::with('responsibleUser')
            ->with(['activities' => function ($query) {
                $query->with('quote')
                ->with(['activity_workers' => function ($query) {
                    $query->with('worker');
                }]);
            }])
            ->find($timeline_id);

        return view('timeline.manage', compact( 'permissions', 'timeline'));

    }
}
