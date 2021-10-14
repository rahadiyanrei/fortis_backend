<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ApparelCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Shirt'
            ],
            [
                'name' => 'Headwear'
            ],
            [
                'name' => 'Accessories'
            ]
        ];
        DB::table('apparel_categories')->insert($data);
    }
}
