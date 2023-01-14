<?php

use Illuminate\Database\Seeder;
use \App\PercentageWorker;

class PercentageWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PercentageWorker::create([
            'name' => 'assign_family',
            'value' => 102.50,
        ]);

        PercentageWorker::create([
            'name' => 'essalud',
            'value' => 9,
        ]);
    }
}
