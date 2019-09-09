<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use SessionRuntimeSeeder;

class SessionRuntime extends Model
{
    protected $fillable = ['shops', 'storage'];

    protected $attributes = [
        'storage' => []
    ];

    /** @var RuntimeDiff */
    protected $diff;

    public function __construct(array $attributes = [])
    {
        $this->diff = new RuntimeDiff();

        if (!Arr::get($attributes, 'inited')) {
            $attributes = array_merge(SessionRuntimeSeeder::getDefaultAttributes(), $attributes);
        }

        parent::__construct($attributes);
    }

    /**
     * @return Collection
     */
    protected function findNonEmptyShops() {
        return $this->shops->filter(function (Shop $shop) {
            return !$shop->isDeleted() && $shop->haveNonEmptyProducts();
        });
    }

    protected function updateShopProducts($shopId, Collection $updatedProducts) {
        $shop = $this->shops->firstWhere('id', $shopId);
        $shop->updateProducts($updatedProducts);

        $this->diff->addDiff(new RuntimeDiff([
            'shops' => collect([new Shop([
                'id' => $shopId,
                'products' => $updatedProducts
            ])])
        ]));
    }

    protected function updateStorageProducts(Collection $updatedProducts) {
        $this->storage = StoredProduct::mergeCollections($this->storage, $updatedProducts);

        $this->diff->addDiff(new RuntimeDiff([
            'storage' => $updatedProducts
        ]));
    }

    /**
     * @param int $shopId
     * @return Shop
     */
    protected function findShopById($shopId) {
        return $this->shops->first(function(Shop $shop) use ($shopId) {
            return $shop->id === $shopId;
        });
    }

    protected function findStoredProductById($productId) {
        return $this->storage->first(function(StoredProduct $product) use ($productId) {
            return $product->id === $productId;
        });
    }

    public function getDiff() {
        return $this->diff;
    }

    public function shopping() {
        $nonEmptyShops = $this->findNonEmptyShops();
        if (!count($nonEmptyShops)) {
            return;
        }
        $shop = $nonEmptyShops->random();
        $product = $shop->findNonEmptyProducts()->random();

        $product->bought();

        $this->updateShopProducts($shop->id, collect([$product]));
    }

    public function restock(Restock $restock) {
        $shop = $this->findShopById($restock->shopId);
        $storageProduct = $this->findStoredProductById( $restock->productId);
        $shopProduct = $shop->getProductById($restock->productId);

        $storageProduct->remove($restock->amount);
        $shopProduct->add($restock->amount);

        $this->updateShopProducts($shop->id, collect([$shopProduct]));
        $this->updateStorageProducts(collect([$storageProduct]));
    }

    public function deleteShop($shopId) {
        $this->updateStorageProducts($this->findShopById($shopId)->products->map(function(StoredProduct $shopProduct) {
            $storageProduct = $this->findStoredProductById($shopProduct->id);
            $storageProduct->add($shopProduct->count);
            return $storageProduct;
        }));

        $deletedShops = collect([new Shop([
            'id' => $shopId,
            'deletedAt' => Carbon::now()->toAtomString(),
            'products' => collect()
        ])]);

        $this->shops = Shop::mergeCollections($this->shops, $deletedShops);

        $this->diff->addDiff(new RuntimeDiff(['shops' => $deletedShops]));
    }

    public function createShop($data) {
        $newShops = collect([new Shop([
            'id' => $this->shops->reduce(function ($carry, $item) {
                return $carry > $item->id ? $carry : $item->id;
            }, 1) + 1,
            'name' => $data['name'],
            'productTypes' => ShopType::find($data['shopTypeId'])->productTypes->pluck('id')->toArray(),
        ])]);

        $this->shops = Shop::mergeCollections($this->shops, $newShops);

        $this->diff->addDiff(new RuntimeDiff(['shops' => $newShops]));
    }

    public function getStorageStockCount($productId) {
        $product = $this->findStoredProductById($productId);
        return $product ? $product->count : 0;
    }

    public function isShopExists($shopId) {
        $shop = $this->findShopById($shopId);
        return $shop && !$shop->isDeleted();
    }

    public function isShopAllowProduct($shopId, $productId) {
        $shop = $this->findShopById($shopId);
        return $shop && $shop->isAllowProduct($productId);
    }
}
