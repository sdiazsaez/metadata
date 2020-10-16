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

        switch(count($m)) {
            case 0:
                $this->addMeta($key, $value);
                break;
            case 1:
                $um = $m->first();
                $um->value = $value;
                $um->save();
                break;
            default:
                $this->removeMeta($key);
                $this->addMeta($key, $value);
                break;
        }

        return $this;
    }
}
