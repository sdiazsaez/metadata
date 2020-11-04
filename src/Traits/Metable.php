<?php

namespace Larangular\Metadata\Traits;

use Larangular\Metadata\Models\Metadata;

trait Metable {

    public function meta() {
        return $this->morphMany(Metadata::class, 'metable');
    }

    public function addUniqueMeta(string $key, $value) {
        $m = $this->meta()
                  ->where('key', $key)
                  ->get();

        switch (count($m)) {
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

    public function addMeta($key, $value) {
        $this->meta()
             ->create([
                 'key'   => $key,
                 'value' => $value,
             ]);

        return $this;
    }

    public function getAllMeta() {
        $all = [];
        foreach ($this->meta as $result) {
            $all[$result['key']][$result['id']] = $result['value'];
        }
        return collect($all);
    }

    public function loadMeta() {
        $this->metadata = $this->getAllMeta();

        return $this;
    }

    public function getMeta($key, $single = true, $cacheAll = false) {
        $results = ($cacheAll)
            ? $this->meta->where('key', $key)
            : $this->meta()
                   ->where('key', $key)
                   ->get();

        $return = [];
        foreach ($results as $result) {
            $return[$result['id']] = $result['value'];
        }
        $return = collect($return);

        return ($single)
            ? $return->first()
            : $return;
    }


    public function removeMeta($key) {
        $this->meta()
             ->where('key', $key)
             ->delete();

        return $this;
    }

    public function scopeWhereMeta($query, $key, $value) {
        return $query->whereHas('meta', function ($query) use ($key, $value) {
            $query->where('key', $key)
                  ->where('value', $value);
        });
    }

    public function scopeOrWhereMeta($query, $key, $value) {
        return $query->orWhereHas('meta', function ($query) use ($key, $value) {
            $query->where('key', $key)
                  ->where('value', $value);
        });
    }
}
