<?php

namespace App\Http\Controllers;

use App\Events\SessionTick;
use App\Restock;
use App\Rules\EnoughInStorage;
use App\Rules\ProductAllowed;
use App\Session;
use Illuminate\Http\Request;

class SessionRuntimeController extends Controller
{
    const RESTOCK_VALUE = 10;

    public function doShopping(Session &$session) {
        $session->updateRuntime(function() use (&$session) {
            $runtime = $session->getRuntime();
            $runtime->shopping();

            return $session;
        });

        event(new SessionTick($session->getId(), $session->getRuntime()->getDiff()));
    }

    public function restock(Request $request) {
        $session = Session::getCurrent();

        $validatedData = $request->validate([
            'productId' => ['required', new EnoughInStorage(self::RESTOCK_VALUE)],
            'shopId' => ['required', new ProductAllowed($request->get('productId'))],
        ]);

        $restock = new Restock([
            'productId' => $validatedData['productId'],
            'shopId' => $validatedData['shopId'],
            'amount' => self::RESTOCK_VALUE
        ]);

        $session->updateRuntime(function() use (&$session, $restock) {
            $runtime = $session->getRuntime();
            $runtime->restock($restock);

            return $session;
        });

        event(new SessionTick($session->getId(), $session->getRuntime()->getDiff()));
    }
}
