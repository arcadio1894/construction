<?php

use Illuminate\Database\Seeder;
use App\Subtype;

class SubtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subtype::create([
            'name' => 'BRILLANTE',
            'description' => 'BRILLANTE',
            'material_type_id' => 3
        ]);
        Subtype::create([
            'name' => 'SATINADO',
            'description' => 'SATINADO',
            'material_type_id' => 3
        ]);
    }
}
