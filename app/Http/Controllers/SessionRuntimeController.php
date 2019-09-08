<?php

namespace App\Http\Controllers;

use App\Events\SessionTick;
use App\Restock;
use App\Rules\EnoughInStorage;
use App\Rules\ProductAllowed;
use App\Rules\ShopExists;
use App\Session;
use Illuminate\Http\Request;

class SessionRuntimeController extends Controller
{
    const RESTOCK_VALUE = 10;

    public function doShopping(Session &$session) {
        $session->updateRuntime(function() use (&$session) {
            $runtime = $session->getRuntime();
            $runtime->shopping();
        });

        event(new SessionTick($session->getId(), $session->getRuntime()->getDiff()));
    }

    public function restock(Request $request) {
        /** @var Session $session */
        $session = Session::getCurrent();

        $validatedData = $request->validate([
            'productId' => ['required', new EnoughInStorage(self::RESTOCK_VALUE)],
            'shopId' => ['required', new ShopExists, new ProductAllowed($request->get('productId'))],
        ]);

        $restock = new Restock([
            'productId' => $validatedData['productId'],
            'shopId' => $validatedData['shopId'],
            'amount' => self::RESTOCK_VALUE
        ]);

        $session->updateRuntime(function() use (&$session, $restock) {
            $runtime = $session->getRuntime();
            $runtime->restock($restock);
        });

        event(new SessionTick($session->getId(), $session->getRuntime()->getDiff()));
    }

    public function restart(Request $request) {
        /** @var Session $session */
        $session = Session::getCurrent();

        $session->updateRuntime(function() use (&$session) {
            $session->restartRuntime();
        });
    }

    public function shopDelete(Request $request) {
        $validatedData = $request->validate([
            'shopId' => ['required', new ShopExists],
        ]);

        /** @var Session $session */
        $session = Session::getCurrent();

        $session->updateRuntime(function() use (&$session, $validatedData) {
            $runtime = $session->getRuntime();
            $runtime->deleteShop((int)$validatedData['shopId']);
        });

        event(new SessionTick($session->getId(), $session->getRuntime()->getDiff()));
    }

    public function shopCreate(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'shopTypeId' => 'required|integer|exists:shop_types,id'
        ]);

        /** @var Session $session */
        $session = Session::getCurrent();

        $session->updateRuntime(function() use (&$session, $validatedData) {
            $runtime = $session->getRuntime();
            $runtime->createShop($validatedData);
        });

        event(new SessionTick($session->getId(), $session->getRuntime()->getDiff()));
    }
}
