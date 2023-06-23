<?php

namespace App\Http\Controllers;

use App\Bill;
use App\DateDimension;
use App\Expense;
use App\Exports\ExpensesReportExcelExport;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();

        return view('expense.index', compact('permissions'));

    }

    public function create()
    {
        $workers = Worker::where('id', '<>', 1)
            ->where('enable', 1)
            ->get();

        $bills = Bill::all();
        return view('expense.create', compact('workers', 'bills'));
    }

    public function store(ExpenseStoreRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $fecha = Carbon::createFromFormat('d/m/Y', $request->get('date_expense'));
            $expense = Expense::create([
                'bill_id' => $request->get('bill_id'),
                'date_expense' => ($request->get('date_expense') != null || $request->get('date_expense') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date_expense')) : null,
                'total' => ($request->get('total') == null || $request->get('total') == '') ? 0: $request->get('total'),
                'worker_id' => $request->get('worker_id'),
                'week' => $fecha->week
            ]);

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gasto guardado con éxito.'], 200);

    }

    public function edit($expense_id)
    {
        $expense = Expense::with(['worker', 'bill'])->find($expense_id);
        $bills = Bill::all();
        return view('expense.edit', compact('expense', 'bills'));

    }

    public function update(ExpenseUpdateRequest $request)
    {
        DB::beginTransaction();
        try {

            $expense = Expense::find($request->get('expense_id'));
            $fecha = Carbon::createFromFormat('d/m/Y', $request->get('date_expense'));

            $expense->bill_id = $request->get('bill_id');
            $expense->date_expense = ($request->get('date_expense') != null || $request->get('date_expense') != '') ? Carbon::createFromFormat('d/m/Y', $request->get('date_expense')) : null;
            $expense->total =  ($request->get('total') == null || $request->get('total') == '') ? 0: $request->get('total');
            $expense->week = $fecha->week;
            $expense->save();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gasto modificado con éxito.'], 200);

    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $expense = Expense::find($request->get('expense_id'));

            $expense->delete();

            DB::commit();

        } catch ( \Throwable $e ) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
        return response()->json(['message' => 'Gasto eliminado con éxito.'], 200);

    }

    public function getAllExpenses()
    {
        $dateCurrent = Carbon::now('America/Lima');
        $date4MonthAgo = $dateCurrent->subMonths(4);

        $expenses = Expense::select('id', 'bill_id', 'worker_id', 'week', 'total', 'date_expense')
            ->with(['worker','bill'])
            ->where('date_expense', '>=', $date4MonthAgo)
            ->orderBy('worker_id', 'asc')
            ->orderBy('date_expense', 'desc')
            ->get();

        return datatables($expenses)->toJson();

    }

    public function report()
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

        return view('expense.report', compact( 'permissions', 'workers', 'years', 'types'));

    }

    public function reportExpenses()
    {
        $type = $_GET['type'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $week = $_GET['week'];
        $worker_id = $_GET['worker'];

        if ( $worker_id == 0 )
        {
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->where('week', $week)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            } else {
                // Mensual
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            }
        } else {
            // El usuario eligio un trabajador
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->where('week', $week)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            } else {
                // Mensual
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            }
        }

        return response()->json([
            'expenses' => $expenses
        ], 200);
    }

    public function downloadExpenses()
    {
        $type = $_GET['type'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $week = $_GET['week'];
        $worker_id = $_GET['worker'];

        if ( $worker_id == 0 )
        {
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->where('week', $week)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            } else {
                // Mensual
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            }
        } else {
            // El usuario eligio un trabajador
            if ( $type == 1 )
            {
                // Tipo = 1 Semanal
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->where('week', $week)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            } else {
                // Mensual
                $expenses = Expense::with(['worker', 'bill'])
                    ->whereYear('date_expense', $year)
                    ->whereMonth('date_expense', $month)
                    ->where('worker_id', $worker_id)
                    ->orderBy('worker_id', 'asc')
                    ->orderBy('date_expense', 'desc')
                    ->get();
            }
        }

        $dates = "RENDICIÓN DE GASTOS " . $year;
        $expenses_array = [];

        foreach ( $expenses as $expense )
        {
            array_push($expenses_array, [
                'trabajador' => $expense->worker->first_name.' '.$expense->worker->last_name,
                'fecha' => $expense->date_expense->format('d/m/Y'),
                'week' => 'SEMANA '.$expense->week,
                'tipo' => $expense->bill->description,
                'total' => $expense->total,
            ]);
        }
        $nombre = "RENDICIÓN_DE_GASTOS_" . $year;

        return (new ExpensesReportExcelExport($expenses_array, $dates))->download($nombre.'.xlsx');

    }
}
