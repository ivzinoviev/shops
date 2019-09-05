<?php

namespace App\Console\Commands;

use App\Events\SessionTick;
use App\Http\Controllers\SessionRuntimeController;
use App\Session;
use App\SessionRuntime;
use Illuminate\Console\Command;

class Tick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:tick';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run one runtime logic tick';

    protected $sessionRuntimeController;

    /**
     * Create a new command instance.
     *
     * @param SessionRuntimeController $sessionRuntimeController
     */
    public function __construct(SessionRuntimeController $sessionRuntimeController)
    {
        $this->sessionRuntimeController = $sessionRuntimeController;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Session::all()->each(function(Session $session) {
            $this->sessionRuntimeController->doShopping($session);

            /** @var SessionRuntime $runtime */
            $runtime = $session->getRuntime();

            event(new SessionTick($session->getId(), [
                'storageProductsCount' => $runtime->getAttribute('storage'),
//                'shopsProductsCount' => [[
//                    'shop_id' => 1,
//                    [
//                        'id' => 1,
//                        'count' => 1000
//                    ]
//                ]]
            ]));
        });
    }
}
