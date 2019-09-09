<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RuntimeDiff extends Model
{
    protected $fillable = ['storage', 'shops'];

    function addDiff(RuntimeDiff $newDiff) {
        $this->storage = StoredProduct::mergeCollections(collect($this->storage), collect($newDiff->storage));

        $this->shops = Shop::mergeCollections(collect($this->shops), collect($newDiff->shops));
    }


}
