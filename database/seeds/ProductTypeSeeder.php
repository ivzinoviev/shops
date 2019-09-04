<?php

use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productTypes = [
            [
                'id' => DatabaseSeeder::PROD_TYPE_FOOD,
                'name' => 'Продукт питания'
            ],
            [
                'id' => DatabaseSeeder::PROD_TYPE_BUILD,
                'name' => 'Стройматериал'
            ],
            [
                'id' => DatabaseSeeder::PROD_TYPE_HOME,
                'name' => 'Товар для дома'
            ],
        ];

        DB::table('product_types')->insert($productTypes);
    }
}
