<?php

use App\ProductType;
use App\ShopType;
use Illuminate\Database\Seeder;

class ShopTypeToProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shopTypeToProducts = [
            [
                'shop_type_id' => ShopType::SHOP_TYPE_FOOD,
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
            ],
            [
                'shop_type_id' => ShopType::SHOP_TYPE_HOME,
                'product_type_id' => ProductType::PROD_TYPE_HOME,
            ],
            [
                'shop_type_id' => ShopType::SHOP_TYPE_BUILD,
                'product_type_id' => ProductType::PROD_TYPE_BUILD,
            ],
            [
                'shop_type_id' => ShopType::SHOP_TYPE_BUILD_HOME,
                'product_type_id' => ProductType::PROD_TYPE_BUILD,
            ],
            [
                'shop_type_id' => ShopType::SHOP_TYPE_BUILD_HOME,
                'product_type_id' => ProductType::PROD_TYPE_HOME,
            ],
            [
                'shop_type_id' => ShopType::SHOP_TYPE_FOOD_HOME,
                'product_type_id' => ProductType::PROD_TYPE_FOOD,
            ],
            [
                'shop_type_id' => ShopType::SHOP_TYPE_FOOD_HOME,
                'product_type_id' => ProductType::PROD_TYPE_HOME,
            ],
        ];

        DB::table('shop_type_to_product_type')->insert($shopTypeToProducts);
    }
}
