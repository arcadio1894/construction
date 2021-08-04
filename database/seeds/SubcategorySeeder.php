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
        Subcategory::create([
            'name' => 'BRONCE',
            'description' => 'BRONCE',
            'category_id' => 4
        ]);
        Subcategory::create([
            'name' => 'NYLON',
            'description' => 'NYLON',
            'category_id' => 4
        ]);

        Subcategory::create([
            'name' => 'INOX',
            'description' => 'INOXIDABLE',
            'category_id' => 5
        ]);
        Subcategory::create([
            'name' => 'PVC',
            'description' => 'PVC',
            'category_id' => 5
        ]);
        Subcategory::create([
            'name' => 'FENE',
            'description' => 'FENE',
            'category_id' => 5
        ]);
        Subcategory::create([
            'name' => 'FEGA',
            'description' => 'FEGA',
            'category_id' => 5
        ]);
        Subcategory::create([
            'name' => 'NYLON',
            'description' => 'NYLON',
            'category_id' => 5
        ]);
        Subcategory::create([
            'name' => 'UHMW',
            'description' => 'UHMW',
            'category_id' => 5
        ]);
        Subcategory::create([
            'name' => 'TEFLON',
            'description' => 'TEFLON',
            'category_id' => 5
        ]);
    }
}
