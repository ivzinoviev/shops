<?php

namespace App\Http\Controllers;

use App\Session;
use App\SessionRuntime;
use Illuminate\Http\Request;
use Illuminate\Session\DatabaseSessionHandler;

class SessionRuntimeController extends Controller
{

    public function doShopping(Session $session) {
        $session->updateRuntime(function() use (&$session) {
            $runtime = $session->getRuntime();
            $runtime->shopping();

            return $session;
        });
    }
}
