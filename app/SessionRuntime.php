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
        if (isset($data['storage'])) {
            $data['storage'] = mergeProducts($this->getDiffByKey('storage'), $data['storage']);
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
            return !isset($shop['deletedAt']) && isProductListNonEmpty($shop['products']);
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

    protected function updateStorageProducts($updatedProducts) {
        $this->storage = mergeProducts($this->storage, $updatedProducts);

        $this->addDiff([
            'storage' => $updatedProducts
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

    public function restock(Restock $restock) {
        $shop = $this->shops[findIndexById($restock->getAttribute('shopId'), $this->getAttribute('shops'))];
        $storageProduct = $this->storage[findIndexById($restock->getAttribute('productId'), $this->getAttribute('storage'))];
        $productIndexInShop = findIndexById($restock->productId, $shop['products']);
        $shopProduct = is_numeric($productIndexInShop) ? $shop['products'][$productIndexInShop] : [
            'id' => $restock->productId,
            'count' => 0
        ];

        $storageProduct['count'] = $storageProduct['count'] - $restock->amount;
        $shopProduct['count'] = $shopProduct['count'] + $restock->amount;
        $shopProduct['lastAddAt'] = Carbon::now()->toAtomString();
        if (isset($shopProduct['soldOutAt'])) {
            $shopProduct['soldOutAt'] = null;
        }

        $this->updateShopProducts($shop['id'], [$shopProduct]);

        $this->updateStorageProducts([$storageProduct]);
    }

    public function deleteShop($shopId) {
        $shopIndex = findIndexById($shopId, $this->shops);

        $this->updateStorageProducts(array_map(function($shopProduct) {
            $storageProduct = $this->storage[findIndexById($shopProduct['id'], $this->storage)];
            $storageProduct['count'] = $storageProduct['count'] + $shopProduct['count'];
            return $storageProduct;
        }, $this->shops[$shopIndex]['products']));

        $deletedShop = [
            'id' => $shopId,
            'deletedAt' => Carbon::now()->toAtomString(),
            'products' => []
        ];

        $this->shops = mergeShops($this->shops, [$deletedShop]);

        $this->addDiff(['shops' => [$deletedShop]]);
    }

    public function createShop($data) {
        $newShop = [
            'id' => max(array_column($this->shops, 'id')) + 1,
            'products' => [],
            'name' => $data['name'],
            'productTypes' => ShopType::find($data['shopTypeId'])->productTypes->pluck('id')->toArray(),
        ];
        $newShop['products'] = [];
        $this->shops = mergeShops($this->shops, [$newShop]);

        $this->addDiff(['shops' => [$newShop]]);
    }

    public function getStorageStockCount($productId) {
        $stock = $this->storage[findIndexById($productId, $this->storage)];
        return $stock ? $stock['count'] : 0;
    }

    public function isShopExists($shopId) {
        $shop = $this->shops[findIndexById($shopId, $this->getAttribute('shops'))];

        return is_array($shop) && !isset($shop['deletedAt']);
    }

    public function isShopAllowProduct($shopId, $productId) {
        $shop = $this->shops[findIndexById($shopId, $this->shops)];
        $product = Product::find($productId);
        if ($shop && $product) {
            return in_array($product->getAttribute('product_type_id'), $shop['productTypes']);
        }
        return false;
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

        if (is_numeric($updatedShopIndex)) {
            // If deleted - other data unnecessary
            if (isset($updated[$updatedShopIndex]['deletedAt'])) {
                return $updated[$updatedShopIndex];
            }

            if (isset($updated[$updatedShopIndex]['products'])) {
                $beforeItem['products'] = mergeProducts($beforeItem['products'], $updated[$updatedShopIndex]['products']);
            }

            return array_merge($beforeItem,Arr::except($updated[$updatedShopIndex], 'products'));
        }

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
