<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Shop extends Model
{
    protected $fillable = ['id', 'products', 'name', 'productTypes', 'deletedAt'];

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['products'])) {
            $attributes['products'] = [];
        }

        $attributes['products'] = collect($attributes['products'])->map(function ($storedProduct) {
            if ($storedProduct instanceof StoredProduct) {
                return $storedProduct;
            }
            return new StoredProduct($storedProduct);
        });

        parent::__construct($attributes);
    }

    public function isDeleted() {
        return (bool)$this->deletedAt;
    }

    public function haveNonEmptyProducts() {
        return StoredProduct::haveNonEmpty($this->products);
    }

    public function findNonEmptyProducts() {
        return StoredProduct::findNonEmpty($this->products);
    }

    public function updateProducts(Collection $updatedProducts) {
//        dd($this->products, $updatedProducts);
        $this->products =  StoredProduct::mergeCollections($this->products, $updatedProducts);
    }

    public function getProductById($productId) {
        return $this->products->first(function(StoredProduct $product) use ($productId) {
            return $product->id === $productId;
        }) ?: new StoredProduct([
            'id' => $productId,
        ]);
    }

    public function isAllowProduct($productId) {
        $product = Product::find($productId);

        if ($product) {
            return in_array($product->product_type_id, $this->productTypes);
        }
        return false;
    }

    public static function mergeCollections(Collection $before, Collection $updated) {
        $mergedExisted = $before->map(function(Shop $oldShop) use ($updated) {
            $updateSourceShop = $updated->first(function (Shop $newShop) use ($oldShop) {
                return $newShop->id === $oldShop->id;
            });

            // If deleted - other data unnecessary
            if ($updateSourceShop && $updateSourceShop->isDeleted()) {
                return $updateSourceShop;
            }

            if ($updateSourceShop && !$updateSourceShop->products->isEmpty()) {
                $oldShop->products = StoredProduct::mergeCollections($oldShop->products, $updateSourceShop->products);
            }

            return $oldShop;
        });

        return collect($mergedExisted)->merge($updated)->keyBy(function($shop) {
//            if (!($shop instanceof Shop)) dd($mergedExisted, $updated);
            return $shop->id;
        })->values();
    }
}
