<?php

namespace App\Http\Controllers;

use App\Events\SessionTick;
use App\Product;
use App\Session;
use App\ShopType;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function getInitData() {
        $channelName = SessionTick::getChannelName(session()->getId());

        return [
            'products' => Product::all(),
            'shopTypes' => ShopType::all(),
            'wsChannel' => $channelName,
            'runtime' => Session::find(session()->getId())->getRuntime()
        ];
    }
}
