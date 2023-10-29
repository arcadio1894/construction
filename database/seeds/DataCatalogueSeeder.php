<?php

use Illuminate\Database\Seeder;
use App\CategoryEquipment;

class DataCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryEquipment::create([
            'description' => 'FAJAS DE PVC',
            'image' => 'no_image.png'
        ]);
    }
}
