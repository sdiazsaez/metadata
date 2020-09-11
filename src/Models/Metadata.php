<?php

namespace Larangular\Metadata\Models;

use Freshwork\Metable\Models\Meta;
use Larangular\Installable\Facades\InstallableConfig;

class Metadata extends Meta {

    protected $touches = ['metable'];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $installableConfig = InstallableConfig::config('Larangular\Metadata\MetadataServiceProvider');
        $this->connection = $installableConfig->getConnection('metadata');
        $this->table = $installableConfig->getName('metadata');
        $this->timestamps = $installableConfig->getTimestamp('metadata');
    }

}
