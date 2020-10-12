<?php

namespace Larangular\Metadata\Traits;

use Freshwork\Metable\Traits\Metable as FreshworkMetable;
use Larangular\Metadata\Models\Metadata;

trait Metable {

    use FreshworkMetable;

    public function meta() {
        return $this->morphMany(Metadata::class, 'metable');
    }

    public function addUniqueMeta(string $key, $value) {
        $m = $this->meta()
                  ->where('key', $key)
                  ->get();

        if (count($m) > 1) {
            $this->removeMeta($key);
            $this->addMeta($key, $value);
        } else if($um = $m->first() && !is_null($um)) {
            $um = $m->first();
            $um->value = $value;
            $um->save();
        } else {
            $this->addMeta($key, $value);
        }
        return $this;
    }
}
