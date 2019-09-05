<?php

namespace App\Http\Controllers;

use App\Session;
use App\SessionRuntime;
use Illuminate\Http\Request;
use Illuminate\Session\DatabaseSessionHandler;

class SessionRuntimeController extends Controller
{
    const PRODUCTS_BOUGHT_PER_SHOPPING = 1;

    public function doShopping(Session $session) {
        $session->updateRuntime(function(SessionRuntime $sessionRuntime) {
            $sessionRuntime->shopping(self::PRODUCTS_BOUGHT_PER_SHOPPING);
            return $sessionRuntime;
        });
    }
}
