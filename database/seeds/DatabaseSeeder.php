<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
