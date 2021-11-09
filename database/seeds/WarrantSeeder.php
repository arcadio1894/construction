<?php

use Illuminate\Database\Seeder;
use App\Warrant;

class WarrantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warrant::create([
            'name' => 'SCH10',
            'description' => 'SCH10'
        ]);
        Warrant::create([
            'name' => 'SCH40',
            'description' => 'SCH40'
        ]);
    }
}
