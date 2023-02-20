<?php

use Illuminate\Database\Seeder;
use \App\Relationship;

class RelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Relationship::create([
            'description' => 'Madre',
        ]);
        Relationship::create([
            'description' => 'Padre',
        ]);
        Relationship::create([
            'description' => 'Hermano',
        ]);
        Relationship::create([
            'description' => 'Hermana',
        ]);
        Relationship::create([
            'description' => 'Esposa',
        ]);
        Relationship::create([
            'description' => 'Esposo',
        ]);
        Relationship::create([
            'description' => 'Prima',
        ]);
    }
}
