<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

// Requirement:
// 1) Do not store runtime data in database
// 2) Link runtime data lifecycle to session
// Therefore no advantage to use Eloquent Models inside runtime
class SessionRuntime extends Model
{
    protected $fillable = ['shops', 'storage'];

    protected $diff = [];

    protected function addDiff($data = []) {
        if (isset($data['products'])) {
            $data['products'] = mergeProducts($this->getDiffByKey('products'), $data['products']);
        }
        if (isset($data['shops'])) {
            $data['shops'] = mergeShops($this->getDiffByKey('shops'), $data['shops']);
        }

        $this->diff = array_merge($this->diff, $data);
    }

    protected function getDiffByKey($key = '') {
        return isset($this->diff[$key]) ? $this->diff[$key] : [];
    }

    protected function findNonEmptyShops() {
        return array_filter($this->getAttribute('shops'), function ($shop) {
            return isProductListNonEmpty($shop['products']);
        });
    }

    protected function updateShopProducts($shopId, $updatedProducts) {
        $shops = $this->getAttribute('shops');
        $shopIndex = findIndexById($shopId, $shops);
        data_set($shops, "{$shopIndex}.products", mergeProducts($shops[$shopIndex]['products'], $updatedProducts));
        $this->setAttribute('shops', $shops);
        $this->addDiff([
            'shops' => [[
                'id' => $shopId,
                'products' => $updatedProducts
            ]]
        ]);
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct(array_merge(SessionRuntime::getDefaultAttributes(),$attributes));
    }

    public function getDiff() {
        return $this->diff;
    }

    public function shopping() {
        $nonEmptyShops = $this->findNonEmptyShops();
        if (!count($nonEmptyShops)) {
            return;
        }
        $shop = Arr::random($nonEmptyShops);
        $product = Arr::random(findNonEmptyProducts($shop['products']));

        $product['count'] = --$product['count'];

        $product['lastBuyAt'] = Carbon::now()->toAtomString();

        if ($product['count'] === 0) {
            $product['soldOutAt'] = Carbon::now()->toAtomString();
        }

        $this->updateShopProducts($shop['id'], [$product]);
    }

    static private function getDefaultAttributes() {
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
                    })->toArray()
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
                        })->toArray()
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
                        })->toArray()
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
                        })->toArray()
                ],
            ],
            'storage' => Product::all()->map(function(Product $product) {
                return [
                    'id' => $product->getAttribute('id'),
                    'count' => 1000
                ];
            })->toArray()
        ];
    }
}

function mergeShops(array $before, array $updated) {
    $updatedFiltred = array_filter($updated, function ($updatedItem) use ($before) {
        return !in_array($updatedItem['id'], array_column($before, 'id'));
    });

    $beforeUpdated = array_map(function($beforeItem) use ($updated) {
        $updatedShopIndex = array_search($beforeItem['id'], array_column($updated, 'id'));
        if (is_numeric($updatedShopIndex) && isset($updated[$updatedShopIndex]['products'])) {
            $beforeItem['products'] = mergeProducts($beforeItem['products'], $updated[$updatedShopIndex]['products']);
        }
        // TODO: update name & productTypes
        return $beforeItem;
    }, $before);

    return array_merge($beforeUpdated, $updatedFiltred);
}

function mergeProducts(array $before, array $updated) {
    $beforeFiltred = array_filter($before, function ($beforeItem) use ($updated) {
        return !in_array($beforeItem['id'], array_column($updated, 'id'));
    });
    return array_merge($beforeFiltred, $updated);
}

function isProductListNonEmpty($products = []) {
    foreach(array_column($products, 'count') as $count) {
        if ($count > 0) {
            return true;
        }
    }
    return false;
}

function findNonEmptyProducts($products = []) {
    return array_filter($products, function($product) {
        return $product['count'] > 0;
    });
}

function findIndexById($id, array $set) {
    return array_search($id, array_column($set, 'id'));
}
