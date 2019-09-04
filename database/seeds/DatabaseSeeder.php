<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    const PROD_TYPE_FOOD = 1;
    const PROD_TYPE_BUILD = 2;
    const PROD_TYPE_HOME = 3;

    const SHOP_TYPE_FOOD = 1;
    const SHOP_TYPE_HOME = 2;
    const SHOP_TYPE_BUILD = 3;
    const SHOP_TYPE_BUILD_HOME = 4;
    const SHOP_TYPE_FOOD_HOME = 5;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductTypeSeeder::class,
            ProductSeeder::class,
            ShopTypeSeeder::class,
            ShopTypeToProductTypeSeeder::class
        ]);
    }
}
