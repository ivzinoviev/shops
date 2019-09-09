<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StoredProduct extends Model
{
    protected $attributes = [
        'count' => 0
    ];

    protected $fillable = ['id', 'count', 'lastBuyAt', 'soldOutAt', 'lastAddAt'];

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['count'])) {
            $attributes['count'] = 0;
        }

        parent::__construct($attributes);
    }

    public function setCount($val) {
        $this->count = val;
    }

    public function bought() {
        $this->remove(1);
        $this->lastBuyAt = Carbon::now()->toAtomString();

        if ($this->count === 0) {
            $this->soldOutAt = Carbon::now()->toAtomString();
        }
    }

    public function remove($val) {
        $this->count = $this->count - $val;
    }

    public function add($val) {
        $this->count = $this->count + $val;
        $this->lastAddAt = Carbon::now()->toAtomString();

        if ($this->soldOutAt) {
            $this->soldOutAt = null;
        }
    }

    public function isEmpty() {
        return $this->count === 0;
    }

    public static function mergeCollections(Collection $before, Collection $updated) {
        $collection = collect($before)->merge($updated);
        return $collection->keyBy(function(StoredProduct $storedProduct) {
//            if (is_numeric($storedProduct)) dd($storedProduct);
            return $storedProduct->id;
        })->values();
    }

    public static function haveNonEmpty(Collection $products) {
        return $products->contains(function (StoredProduct $product) {
            return !$product->isEmpty();
        });
    }

    public static function findNonEmpty(Collection $products) {
        return $products->filter(function (StoredProduct $product) {
            return !$product->isEmpty();
        });
    }
}
