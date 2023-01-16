<?php

use Illuminate\Database\Seeder;
use App\Holiday;
use Carbon\Carbon;

class HolidaysBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Holiday::create([
            'description' => 'Año nuevo',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '01/01/2023'),
        ]);

        Holiday::create([
            'description' => 'Jueves Santo',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '06/04/2023'),
        ]);

        Holiday::create([
            'description' => 'Viernes Santo',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '07/04/2023'),
        ]);

        Holiday::create([
            'description' => 'Día del Trabajo',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '01/05/2023'),
        ]);

        Holiday::create([
            'description' => 'San Pedro y San Pablo',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '29/06/2023'),
        ]);

        Holiday::create([
            'description' => 'Fiestas Patrias',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '28/07/2023'),
        ]);

        Holiday::create([
            'description' => 'Fiestas Patrias',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '29/07/2023'),
        ]);

        Holiday::create([
            'description' => 'Santa Rosa de Lima',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '30/08/2023'),
        ]);

        Holiday::create([
            'description' => 'Combate de Angamos',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '08/10/2023'),
        ]);

        Holiday::create([
            'description' => 'Todos los Santos',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '01/11/2023'),
        ]);

        Holiday::create([
            'description' => 'Inmaculada Concepción',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '08/12/2023'),
        ]);

        Holiday::create([
            'description' => 'Batalla de Ayacucho',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '09/12/2023'),
        ]);

        Holiday::create([
            'description' => 'Navidad',
            'year' => 2023,
            'date_complete' => Carbon::createFromFormat('d/m/Y', '25/12/2023'),
        ]);
    }
}
