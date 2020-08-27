<?php

namespace Larangular\Metadata\Traits;

use Freshwork\Metable\Traits\Metable as FreshworkMetable;
use Larangular\Metadata\Models\Metadata;

trait Metable {

    use FreshworkMetable;

    public function meta() {
        return $this->morphMany(Metadata::class, 'metable');
    }
}
