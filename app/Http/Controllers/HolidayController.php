<?php

namespace App\Http\Controllers;

use App\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('holiday.index', compact('permissions'));

    }

    public function create()
    {
        $current_date = Carbon::now('America/Lima');
        $current_year = $current_date->year;
        return view('holiday.create', compact('current_year'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $holiday = Holiday::create([
                'description' => $request->get('description'),
                'year' => $request->get('year'),
                'date_complete' => ($request->get('date_complete') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_complete')) : null,
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Feriado guardado con éxito.'], 200);

    }

    public function show(Holiday $holiday)
    {
        //
    }

    public function edit($holiday_id)
    {
        $holiday = Holiday::find($holiday_id);

        return view('holiday.edit', compact('holiday'));

    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $holiday = Holiday::find($request->get('holiday_id'));

            $holiday->description = $request->get('description');
            $holiday->year = $request->get('year');
            $holiday->date_complete = ($request->get('date_complete') != null) ? Carbon::createFromFormat('d/m/Y', $request->get('date_complete')) : null;

            $holiday->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Feriado modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $holiday = Holiday::find($request->get('holiday_id'));
            $holiday->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Feriado eliminado con éxito.'], 200);

    }

    public function getAllHolidays()
    {
        $holidays = Holiday::select('id', 'description', 'year', 'date_complete')
            ->orderBy('year', 'DESC')
            ->orderBy('date_complete', 'ASC')
            ->get();
        return datatables($holidays)->toJson();

    }

    public function generateHolidays()
    {
        DB::beginTransaction();
        try {

            $yearCurrent = Carbon::now('America/Lima')->year;
            $yearMax = (int) Holiday::max('year');

            if ($yearCurrent+1 == $yearMax)
            {
                return response()->json(['message' => 'Lo sentimos, los feriados del próximo año ya fueron creados.'], 422);
            }

            $holidays = Holiday::select('id', 'description', 'year', 'date_complete')
                ->where('year', $yearMax)
                ->orderBy('year', 'DESC')
                ->orderBy('date_complete', 'ASC')
                ->get();

            foreach ( $holidays as $holiday )
            {
                $tz = 'America/Lima';
                $day = $holiday->date_complete->day;
                $month = $holiday->date_complete->month;
                $date = Carbon::createFromDate($yearMax+1, $month, $day, $tz);
                Holiday::create([
                    'description' => $holiday->description,
                    'year' => $yearMax+1,
                    'date_complete' => $date,
                ]);
            }

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Feriados generados con éxito.'], 200);

    }
}
