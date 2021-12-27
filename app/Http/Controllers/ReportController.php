<?php

namespace App\Http\Controllers;

use App\DetailEntry;
use App\Entry;
use App\Exports\AmountReport;
use App\Exports\DatabaseMaterialsExport;
use App\Item;
use App\Material;
use App\Quote;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function amountInWarehouse()
    {
        $items = Item::whereNotIn('state_item', ['exited'])->get();
        $amount_dollars = 0;
        $amount_soles = 0;
        $quantity_items = 0;
        //dd($items);
        foreach ( $items as $item )
        {
            $detail_entry = DetailEntry::with('entry')->find($item->detail_entry_id);
            //dump($detail_entry);
            $currency = $detail_entry->entry->currency_invoice;

            if ( $currency === 'USD' )
            {
                $amount_dollars = $amount_dollars + (float)$item->price;
            } else {
                $amount_soles = $amount_soles + (float)$item->price;
            }
            $quantity_items = $quantity_items + (float)$item->percentage;
        }

        return response()->json(['amount_dollars' => $amount_dollars, 'amount_soles' => $amount_soles, 'quantity_items' => $quantity_items]);

    }

    public function excelAmountStock()
    {
        $materials = Material::where('stock_current', '>', 0)->get();
        $materials_array = [];
        $amount_dollars = 0;
        $amount_soles = 0;
        $quantity_dollars = 0;
        $quantity_soles = 0;
        foreach ( $materials as $material )
        {
            $items = Item::where('material_id', $material->id)
                ->whereNotIn('state_item', ['exited'])->get();
            foreach ( $items as $item )
            {
                $detail_entry = DetailEntry::with('entry')->find($item->detail_entry_id);
                //dump($detail_entry);
                $currency = $detail_entry->entry->currency_invoice;

                if ( $currency === 'USD' )
                {
                    $amount_dollars = $amount_dollars + (float)$item->price;
                    $quantity_dollars = $quantity_dollars + (float)$item->percentage;
                } else {
                    $amount_soles = $amount_soles + (float)$item->price;
                    $quantity_soles = $quantity_soles + (float)$item->percentage;
                }
            }

            array_push($materials_array, ['material'=>$material->full_description, 'stock_dollars'=>$quantity_dollars, 'stock_soles'=>$quantity_soles, 'amount_dollars'=>$amount_dollars, 'amount_soles'=>$amount_soles]);

            // Reset values
            $amount_dollars = 0;
            $amount_soles = 0;
            $quantity_dollars = 0;
            $quantity_soles = 0;
        }
        //dump($materials_array);

        return Excel::download(new AmountReport($materials_array), 'reporte_Stock_Monto_En_Almacen.xlsx');
    }

    public function excelBDMaterials()
    {
        $materials = Material::with('category', 'materialType','unitMeasure','subcategory','subType','exampler','brand','warrant','quality','typeScrap')->get();

        $materials_array = [];

        foreach ( $materials as $material )
        {
            $priority = '';
            if ( $material->stock_current > $material->stock_max ){
                $priority = 'Completo';
            } else if ( $material->stock_current = $material->stock_max ){
                $priority = 'Aceptable';
            } else if ( $material->stock_current > $material->stock_min && $material->stock_current < $material->stock_max ){
                $priority = 'Aceptable';
            } else if ( $material->stock_current = $material->stock_min ){
                $priority = 'Por agotarse';
            } else if ( $material->stock_current < $material->stock_min || $material->stock_current == 0 ){
                $priority = 'Agotado';
            }
            array_push($materials_array, [
                'code' => $material->code,
                'material' => $material->full_description,
                'measure' => $material->measure,
                'unit' => ($material->unitMeasure == null) ? '':$material->unitMeasure->name,
                'stock_max' => $material->stock_max,
                'stock_min' => $material->stock_min,
                'stock_current' => $material->stock_current,
                'priority'=> $priority,
                'price'=> $material->unit_price,
                'category'=> ($material->category == null) ? '': $material->category->name,
                'subcategory'=> ($material->subcategory == null) ? '': $material->subcategory->name,
                'type'=> ($material->materialType == null) ? '': $material->materialType->name,
                'subtype'=> ($material->subType == null) ? '': $material->subType->name,
                'brand'=> ($material->brand == null) ? '': $material->brand->name,
                'exampler'=> ($material->exampler == null) ? '': $material->exampler->name,
                'quality'=> ($material->quality == null) ? '': $material->quality->name,
                'warrant'=> ($material->warrant == null) ? '':$material->warrant->name,
                'scrap'=> ($material->typeScrap == null) ? '':$material->typeScrap->name,
            ]);
        }
        //dump($materials_array);

        return Excel::download(new DatabaseMaterialsExport($materials_array), 'reporte_base_materiales.xlsx');
    }

    public function chartQuotesDollarsSoles()
    {
        $meses = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");

        $current_date = CarbonImmutable::now('America/Lima');
        $current_month = $current_date->format('m');
        $current_year = $current_date->format('Y');
        //dump($current_date);
        //dump($current_month);
        //dump($current_year);
        $arrayMonths = [];
        $arrayYears = [];
        $arrayMonthsNames = [];
        for ( $i = 0; $i<=6; $i++ )
        {
            if ( (int)$current_month - $i <= 0 )
            {
                $mes = (int)$current_month - $i + 12;
                array_push($arrayMonths, (int)$mes);
                array_push($arrayYears, $current_year - 1);
                array_push($arrayMonthsNames, $meses[((int)$mes) - 1] . ' ' . $current_year - 1);

            } else {
                array_push($arrayYears, $current_year);
                array_push($arrayMonths, (int)$current_month - $i);
                array_push($arrayMonthsNames, $meses[((int)$current_month - $i) - 1] . ' ' . $current_year);
            }
        }
        //dump($arrayMonths);
        //dump($arrayMonthsNames);
        $total_dollars = 0;
        $total_soles = 0;

        $total_quantity = 0;
        $dollars_quantity = 0;
        $soles_quantity = 0;

        $amounts_dollars = [];
        $amounts_soles = [];

        for ( $i=0; $i<count($arrayMonths); $i++ )
        {
            $quotes = Quote::whereNotIn('state', ['expired', 'canceled'])
                ->where('raise_status', 1)
                ->whereMonth('date_quote', $arrayMonths[$i])
                ->whereYear('date_quote', $arrayYears[$i])
                ->get();

            $total_quantity += $quotes->count();

            foreach ( $quotes as $quote )
            {
                if ($quote->currency_invoice === 'PEN')
                {
                    //dump((float) $quote->subtotal_rent);
                    $total_soles += (float) $quote->subtotal_rent;
                    $soles_quantity += 1;
                } else {
                    //dump((float) $quote->subtotal_rent);
                    $total_dollars += (float) $quote->subtotal_rent;
                    $dollars_quantity += 1;
                }
            }

            array_push($amounts_dollars, $total_dollars);
            array_push($amounts_soles, $total_soles);

            $total_dollars = 0;
            $total_soles = 0;
        }
        //dump($amounts_dollars);
        //dump($amounts_soles);
        $months = array_reverse($arrayMonths);
        $monthsNames = array_reverse($arrayMonthsNames);
        $dollars = array_reverse($amounts_dollars);
        $soles = array_reverse($amounts_soles);
        //dump($months);
        //dump($monthsNames);
        //dump($dollars);
        //dump($soles);

        $percentage_dollars = round((($dollars_quantity/$total_quantity)*100), 0);
        $percentage_soles = round((($soles_quantity/$total_quantity)*100), 0);

        $sum_dollars = array_sum($dollars);
        $sum_soles = array_sum($soles);

        return response()->json([
            'months' => $months,
            'monthsNames' => $monthsNames,
            'dollars' => $dollars,
            'soles' => $soles,
            'percentage_dollars' => $percentage_dollars,
            'percentage_soles' => $percentage_soles,
            'sum_dollars' => $sum_dollars,
            'sum_soles' => $sum_soles
        ]);

    }

    public function chartQuotesDollarsSolesView( $dateStart, $dateEnd )
    {
        $date_start = Carbon::parse($dateStart);
        $date_end = Carbon::parse($dateEnd);
        //dump($dateStart);
        //dump($dateEnd);
        //dump($date_start);
        //dump($date_end);
        $meses = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");

        $year_start = (int)$date_start->format('Y');
        $month_start = (int)$date_start->format('m');
        $day_start = 1;
        $tz = 'America/Lima';

        $start_date = Carbon::createFromDate($year_start, $month_start, $day_start, $tz);

        $year_end = (int)$date_end->format('Y');
        $month_end = (int)$date_end->format('m');
        $day_end = 1;

        $endDate = Carbon::createFromDate($year_end, $month_end, $day_end, $tz);
        $lastDayofMonth = $endDate->endOfMonth()->toDateString();
        $end_date = Carbon::createFromFormat('Y-m-d', $lastDayofMonth);
        //dump($start_date);
        //dump($end_date);

        $arrayMonths = [];
        $arrayYears = [];
        $arrayMonthsNames = [];
        while ($start_date < $end_date) {
            array_push($arrayMonths, (int)$start_date->format('m'));
            array_push($arrayYears, (int)$start_date->format('Y'));
            $start_date->addMonth();
        }

        for ( $j = 0; $j < count($arrayMonths); $j++ )
        {
            array_push($arrayMonthsNames, $meses[(int)$arrayMonths[$j] - 1].' '.(int)$arrayYears[$j]);
        }

        //dump($arrayMonths);
        //dump($arrayYears);
        //dump($arrayMonthsNames);

        $total_quantity = 0;

        $total_soles = 0;
        $soles_quantity = 0;
        $total_dollars = 0;
        $dollars_quantity = 0;

        $amounts_dollars = [];
        $amounts_soles = [];

        for ( $i=0; $i<count($arrayMonths); $i++ )
        {
            $quotes = Quote::whereNotIn('state', ['expired', 'canceled'])
                ->where('raise_status', 1)
                ->whereMonth('date_quote', $arrayMonths[$i])
                ->whereYear('date_quote', $arrayYears[$i])
                ->get();

            $total_quantity += $quotes->count();

            foreach ( $quotes as $quote )
            {
                if ($quote->currency_invoice === 'PEN')
                {
                    //dump((float) $quote->subtotal_rent);
                    $total_soles += (float) $quote->subtotal_rent;
                    $soles_quantity += 1;
                } else {
                    //dump((float) $quote->subtotal_rent);
                    $total_dollars += (float) $quote->subtotal_rent;
                    $dollars_quantity += 1;
                }
            }

            array_push($amounts_dollars, $total_dollars);
            array_push($amounts_soles, $total_soles);

            $total_dollars = 0;
            $total_soles = 0;
        }
        //dump($amounts_dollars);
        //dump($amounts_soles);
        $months = array_reverse($arrayMonths);
        $monthsNames = array_reverse($arrayMonthsNames);
        $dollars = array_reverse($amounts_dollars);
        $soles = array_reverse($amounts_soles);


        $percentage_dollars = round((($dollars_quantity/$total_quantity)*100), 0);
        $percentage_soles = round((($soles_quantity/$total_quantity)*100), 0);

        $sum_dollars = array_sum($dollars);
        $sum_soles = array_sum($soles);

        //dump($arrayMonths);
        //dump($arrayYears);
        //dump($monthsNames);
        //dump($dollars);
        //dump($soles);
        //dump($percentage_dollars);
        //dump($percentage_soles);
        //dump($sum_dollars);
        //dump($sum_soles);

        return response()->json([
            'months' => $arrayMonths,
            'monthsNames' => $arrayMonthsNames,
            'dollars' => $amounts_dollars,
            'soles' => $amounts_soles,
            'percentage_dollars' => $percentage_dollars,
            'percentage_soles' => $percentage_soles,
            'sum_dollars' => $sum_dollars,
            'sum_soles' => $sum_soles
        ]);

    }

    public function chartExpensesIncomeDollarsSoles()
    {
        $meses = array("ENE","FEB","MAR","ABR","MAY","JUN","JUL","AGO","SEP","OCT","NOV","DIC");

        $current_date = CarbonImmutable::now('America/Lima');
        $current_month = $current_date->format('m');
        $current_year = $current_date->format('Y');
        //dump($current_date);
        //dump($current_month);
        //dump($current_year);
        $arrayMonths = [];
        $arrayYears = [];
        $arrayMonthsNames = [];
        for ( $i = 0; $i<=6; $i++ )
        {
            if ( (int)$current_month - $i <= 0 )
            {
                $mes = (int)$current_month - $i + 12;
                array_push($arrayMonths, (int)$mes);
                array_push($arrayYears, $current_year - 1);
                array_push($arrayMonthsNames, $meses[((int)$mes) - 1] . ' ' . $current_year - 1);

            } else {
                array_push($arrayYears, $current_year);
                array_push($arrayMonths, (int)$current_month - $i);
                array_push($arrayMonthsNames, $meses[((int)$current_month - $i) - 1] . ' ' . $current_year);
            }
        }
        //dump($arrayMonths);
        //dump($arrayMonthsNames);
        $total_dollars = 0;
        $total_soles = 0;

        $total_quantity = 0;
        $dollars_quantity = 0;
        $soles_quantity = 0;

        $amounts_dollars = [];
        $amounts_soles = [];

        // Ingresos
        for ( $i=0; $i<count($arrayMonths); $i++ )
        {
            $quotes = Quote::whereNotIn('state', ['expired', 'canceled'])
                ->where('raise_status', 1)
                ->whereMonth('date_quote', $arrayMonths[$i])
                ->whereYear('date_quote', $arrayYears[$i])
                ->get();

            $total_quantity += $quotes->count();

            foreach ( $quotes as $quote )
            {
                if ($quote->currency_invoice === 'PEN')
                {
                    //dump((float) $quote->subtotal_rent);
                    $total_soles += (float) $quote->subtotal_rent;
                    $soles_quantity += 1;
                } else {
                    //dump((float) $quote->subtotal_rent);
                    $total_dollars += (float) $quote->subtotal_rent;
                    $dollars_quantity += 1;
                }
            }

            array_push($amounts_dollars, $total_dollars);
            array_push($amounts_soles, $total_soles);

            $total_dollars = 0;
            $total_soles = 0;
        }

        // Egresos
        $expense_soles = 0;
        $expense_soles_quantity = 0;
        $expense_dollars = 0;
        $expense_dollars_quantity = 0;

        $amounts_dollars = [];
        $amounts_soles = [];

        for ( $i=0; $i<count($arrayMonths); $i++ )
        {
            $entries = Entry::with('details')
                ->whereMonth('date_entry', $arrayMonths[$i])
                ->whereYear('date_entry', $arrayYears[$i])
                ->get();

            foreach ( $entries as $entry )
            {
                if ($entry->currency_invoice === 'PEN')
                {
                    //dump((float) $quote->subtotal_rent);
                    $expense_soles += (float) $quote->subtotal_rent;
                    $expense_soles_quantity += 1;
                } else {
                    //dump((float) $quote->subtotal_rent);
                    $total_dollars += (float) $quote->subtotal_rent;
                    $dollars_quantity += 1;
                }
            }

            array_push($amounts_dollars, $total_dollars);
            array_push($amounts_soles, $total_soles);

            $total_dollars = 0;
            $total_soles = 0;
        }
        //dump($amounts_dollars);
        //dump($amounts_soles);
        $months = array_reverse($arrayMonths);
        $monthsNames = array_reverse($arrayMonthsNames);
        $dollars = array_reverse($amounts_dollars);
        $soles = array_reverse($amounts_soles);
        //dump($months);
        //dump($monthsNames);
        //dump($dollars);
        //dump($soles);

        $percentage_dollars = round((($dollars_quantity/$total_quantity)*100), 0);
        $percentage_soles = round((($soles_quantity/$total_quantity)*100), 0);

        $sum_dollars = array_sum($dollars);
        $sum_soles = array_sum($soles);

        return response()->json([
            'months' => $months,
            'monthsNames' => $monthsNames,
            'income_dollars' => $dollars,
            'income_soles' => $soles,
            'percentage_dollars' => $percentage_dollars,
            'percentage_soles' => $percentage_soles,
            'sum_dollars' => $sum_dollars,
            'sum_soles' => $sum_soles
        ]);

    }
}
