<?php

namespace App\Http\Controllers;

use App\Product;
use App\ShopType;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function getInitData() {
        return [
            'products' => Product::all(),
            'shopTypes' => ShopType::all(),
            'webSocketUrl' =>
        ];
    }
}
