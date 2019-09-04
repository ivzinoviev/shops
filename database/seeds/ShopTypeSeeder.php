<?php

use Illuminate\Database\Seeder;

class ShopTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shopTypes = [
            [
                'id' => DatabaseSeeder::SHOP_TYPE_FOOD,
                'name' => 'Продуктовый'
            ],
            [
                'id' => DatabaseSeeder::SHOP_TYPE_HOME,
                'name' => 'Хозяйственный'
            ],
            [
                'id' => DatabaseSeeder::SHOP_TYPE_BUILD,
                'name' => 'Строительный'
            ],
            [
                'id' => DatabaseSeeder::SHOP_TYPE_BUILD_HOME,
                'name' => 'Строительно-хозяйственный'
            ],
            [
                'id' => DatabaseSeeder::SHOP_TYPE_FOOD_HOME,
                'name' => 'Продуктово-хозяйственный'
            ],
        ];

        DB::table('shop_types')->insert($shopTypes);
    }
}
