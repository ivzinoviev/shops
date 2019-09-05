<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Session extends Model
{
    public function getId() {
        return $this->attributes['id'];
    }

    // Using cache to store SessionRuntime:
    // 1) It requires less complicated logic then store data in sessions of not current user
    // 2) We can improve performance using fast cache solution
    function updateRuntime(callable $action) {
        Cache::put($this->getCacheKey(), $action($this->getRuntime()), config('session.lifetime') * 60);
    }

    function getRuntime() {
        return Cache::get($this->getCacheKey(), new SessionRuntime());
    }

    protected function getCacheKey() {
        return 'session_' . $this->getId();
    }
}
