<?php

use Illuminate\Database\Seeder;
use App\Subcategory;

class SubcategorySeeder extends Seeder
{
    public function run()
    {
        Subcategory::create([
            'name' => 'INOX',
            'description' => 'INOXIDABLE',
            'category_id' => 4
        ]);
        Subcategory::create([
            'name' => 'PVC',
            'description' => 'PVC',
            'category_id' => 4
        ]);
        Subcategory::create([
            'name' => 'FENE',
            'description' => 'FENE',
            'category_id' => 4
        ]);
        Subcategory::create([
            'name' => 'FEGA',
            'description' => 'FEGA',
            'category_id' => 4
        ]);
    }
}
