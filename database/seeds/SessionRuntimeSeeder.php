<?php

use App\Product;
use App\ProductType;
use Illuminate\Database\Seeder;

class SessionRuntimeSeeder extends Seeder
{
    public function run()
    {

    }

    static public function getDefaultAttributes() {
        return [
            'shops' =>  [
                [
                    'id' => 1,
                    'productTypes' => [ProductType::PROD_TYPE_FOOD],
                    'name' => 'Продукты',
                    'products' => Product::where(['product_type_id' => ProductType::PROD_TYPE_FOOD])->get()
                        ->map(function(Product $product) {
                            return [
                                'id' => $product->getAttribute('id'),
                                'count' => 100
                            ];
                        })
                ],
                [
                    'id' => 2,
                    'productTypes' => [ProductType::PROD_TYPE_BUILD],
                    'name' => 'Строительный',
                    'products' => Product::where(['product_type_id' => ProductType::PROD_TYPE_BUILD])->get()
                        ->map(function(Product $product) {
                            return [
                                'id' => $product->getAttribute('id'),
                                'count' => 100
                            ];
                        })
                ],
                [
                    'id' => 3,
                    'productTypes' => [ProductType::PROD_TYPE_HOME],
                    'name' => 'Всё для дома',
                    'products' => Product::where(['product_type_id' => ProductType::PROD_TYPE_HOME])->get()
                        ->map(function(Product $product) {
                            return [
                                'id' => $product->getAttribute('id'),
                                'count' => 100
                            ];
                        })
                ],
                [
                    'id' => 4,
                    'productTypes' => [ProductType::PROD_TYPE_FOOD, ProductType::PROD_TYPE_HOME],
                    'name' => 'Супермаркет',
                    'products' => Product::whereIn('product_type_id', [ProductType::PROD_TYPE_FOOD, ProductType::PROD_TYPE_HOME])->get()
                        ->map(function(Product $product) {
                            return [
                                'id' => $product->getAttribute('id'),
                                'count' => 100
                            ];
                        })
                ],
            ],
            'storage' => Product::all()->map(function(Product $product) {
                return [
                    'id' => $product->getAttribute('id'),
                    'count' => 1000
                ];
            })
        ];
    }
}
