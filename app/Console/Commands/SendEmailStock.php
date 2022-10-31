<?php

namespace App\Console\Commands;

use App\Exports\StockMaterialsExcel;
use App\Mail\StockmaterialsEmail;
use App\Material;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendEmailStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a email with an excel attachment of stocks materials';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // TODO: Obtener los materiales por deshabastecerse
        $array = [];

        // TODO: Solo categoria de estructuras
        $materials = Material::where('category_id', 5)
            ->where('stock_min', '>',0)
            ->get();

        foreach ( $materials as $material )
        {
            $state = '';

            if ( $material->stock_current < $material->stock_min )
            {
                $state = 'Deshabastecido';
                array_push($array, [
                    'id' => $material->id,
                    'code' => $material->code,
                    'material' => $material->full_description,
                    'stock' => $material->stock_current,
                    'stock_max' => $material->stock_max,
                    'stock_min' => $material->stock_min,
                    'state' => $state,
                ]);
            } elseif ( $material->stock_current < 0.25 * $material->stock_max ) {
                $state = 'Por deshabastecer';
                array_push($array, [
                    'id' => $material->id,
                    'code' => $material->code,
                    'material' => $material->full_description,
                    'stock' => $material->stock_current,
                    'stock_max' => $material->stock_max,
                    'stock_min' => $material->stock_min,
                    'state' => $state,
                ]);
            }

        }

        //return (new StockMaterialsExcel($array))->download('facturasFinanzas.xlsx');

        //dd($array);
        // TODO: Crear el excel y guardarlo
        $path = public_path('excels');
        $dt = Carbon::now();
        $filename = 'MaterialesDeshabastecidos_'. $dt->toDateString() .'.xlsx';
        Excel::store(new StockMaterialsExcel($array), $filename, 'excel_uploads');

        $pathComplete = $path .'/'. $filename;
        //TODO: Enviar el correo
        Mail::to('kparedes@sermeind.com')
            ->cc(['almacen.sermeind@gmail.com','joryes1894@gmail.com'])
            ->send(new StockmaterialsEmail($pathComplete, $filename));
    }
}
