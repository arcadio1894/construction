<?php

namespace App\Console\Commands;

use App\Projection;
use App\ProjectionDetail;
use App\Worker;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateProjectionMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projection:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the projection a projection details of each month';

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
        $dateCurrent = Carbon::now('America/Lima');

        $workers = Worker::where('enable', 1)->where('id', '<>', 1)->get();

        $typeExchange = $this->getExchange($dateCurrent->format('Y-m-d'));

        $quantityDays = $dateCurrent->daysInMonth;

        $projection = Projection::create([
            'year' => $dateCurrent->year,
            'month' => $dateCurrent->month,
            'projection_month_soles' => 0,
            'projection_month_dollars' => 0,
            'projection_week_soles' => 0,
            'projection_week_dollars' => 0
        ]);

        $projection_month_soles = 0;
        $projection_month_dollars = 0;

        foreach ( $workers as $worker )
        {
            $detail = ProjectionDetail::create([
                'projection_id' => $projection->id,
                'worker_id' => $worker->id,
                'salary' => ($worker->monthly_salary == null) ? 0:$worker->monthly_salary
            ]);

            $projection_month_soles += (($worker->monthly_salary == null) ? 0:$worker->monthly_salary);
            $projection_month_dollars += ((($worker->monthly_salary == null) ? 0:$worker->monthly_salary) / $typeExchange->compra);

        }
        $projection_week_soles = $projection_month_soles/($quantityDays/7);
        $projection_week_dollars = $projection_month_dollars/($quantityDays/7);

        $projection->projection_month_soles = $projection_month_soles;
        $projection->projection_month_dollars = $projection_month_dollars;
        $projection->projection_week_soles = $projection_week_soles;
        $projection->projection_week_dollars = $projection_week_dollars;
        $projection->save();

    }

    public function getExchange($fecha)
    {
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha='.$fecha,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $tipoCambioSunat = json_decode($response);

        return $tipoCambioSunat;

    }
}
