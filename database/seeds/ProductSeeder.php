<?php

use App\ProductType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
                'name' => 'Хлеб'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
                'name' => 'Молоко'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
                'name' => 'Мясо'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
                'name' => 'Овощи'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
                'name' => 'Фрукты'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
                'name' => 'Мороженое'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
                'name' => 'Шоколад'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_BUILD,
                'name' => 'Бетон'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_BUILD,
                'name' => 'Кирпичи'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_BUILD,
                'name' => 'Гвозди'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_BUILD,
                'name' => 'Фанера'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_HOME,
                'name' => 'Шампунь'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_HOME,
                'name' => 'Мыло'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_HOME,
                'name' => 'Ведро'
            ],
            [
                'product_type_id' => ProductType::PROD_TYPE_HOME,
                'name' => 'Швабра'
            ],
        ];

        DB::table('products')->insert($products);
    }
}
