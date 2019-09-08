<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopType extends Model
{
    const SHOP_TYPE_FOOD = 1;
    const SHOP_TYPE_HOME = 2;
    const SHOP_TYPE_BUILD = 3;
    const SHOP_TYPE_BUILD_HOME = 4;
    const SHOP_TYPE_FOOD_HOME = 5;

    public function productTypes() {
        return $this->belongsToMany('App\ProductType', 'shop_type_to_product_type');
    }
}
