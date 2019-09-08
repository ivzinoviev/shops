<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Session extends Model
{
    protected $sessionRuntime;

    public function getId() {
        return $this->attributes['id'];
    }

    // Using cache to store SessionRuntime:
    // 1) It requires less complicated logic then store data in sessions of not current user
    // 2) We can improve performance using fast cache solution
    public function updateRuntime(callable $action) {
        $action($this->getRuntime());

        Cache::put($this->getCacheKey(), $this->getRuntime()->toArray(), config('session.lifetime') * 60);
    }

    public function getRuntime() {
        if (!$this->sessionRuntime) {
            $this->sessionRuntime = new SessionRuntime(Cache::get($this->getCacheKey(), []));
        }

        return $this->sessionRuntime;
    }

    public function restartRuntime() {
        $this->sessionRuntime = new SessionRuntime();
    }

    protected function getCacheKey() {
        return 'session_' . $this->getId();
    }

    public static function getCurrent() {
        return Session::find(session()->getId());
    }
}
