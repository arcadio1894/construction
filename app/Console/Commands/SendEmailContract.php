<?php

namespace App\Console\Commands;

use App\Contract;
use App\Exports\ContractExpireExcel;
use App\Mail\ContractExpireEmail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SendEmailContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a email with an excel attachment of contracts for expire';

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
        $contractsAboutToExpire = [];

        $currentDate = Carbon::now('America/Lima');
        $futureDate = $currentDate->copy()->addDays(15);

        $contracts = Contract::whereBetween('date_fin', [$currentDate->toDateString(), $futureDate->toDateString()])->get();

        foreach ($contracts as $contract) {
            $daysRemaining = Carbon::parse($contract->date_fin)->diffInDays($currentDate);

            $contractData = [
                'worker_name' => $contract->worker->first_name." ".$contract->worker->last_name,
                'id' => $contract->id,
                'code' => $contract->code,
                'date_start' => $contract->date_start->format('d/m/Y'),
                'date_fin' => $contract->date_fin->format('d/m/Y'),
                'days_remaining' => $daysRemaining,
            ];

            array_push($contractsAboutToExpire, $contractData);
        }

        //return (new StockMaterialsExcel($array))->download('facturasFinanzas.xlsx');

        //dd($array);
        // TODO: Crear el excel y guardarlo
        $path = public_path('excels');
        $dt = Carbon::now();
        $filename = 'Contratos_por_expirar_'. $dt->toDateString() .'.xlsx';
        Excel::store(new ContractExpireExcel($contractsAboutToExpire), $filename, 'excel_uploads');

        $pathComplete = $path .'/'. $filename;
        //TODO: Enviar el correo
        Mail::to('kparedes@sermeind.com.pe'/*'joryes1894@gmail.com'*/)
            ->cc(['joryes1894@gmail.com','edesceperu@gmail.com','shuaman@sermeind.com.pe','jmauricio@sermeind.com.pe'])
            ->send(new ContractExpireEmail($pathComplete, $filename));
    }
}
