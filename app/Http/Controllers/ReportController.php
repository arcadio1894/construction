<?php

namespace App\Http\Controllers;

use App\DetailEntry;
use App\Entry;
use App\Exports\AmountReport;
use App\Item;
use App\Material;
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
}
