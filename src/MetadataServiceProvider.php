<?php

namespace Larangular\Metadata;

use Larangular\Installable\{Contracts\HasInstallable, Contracts\Installable, Installer\Installer};
use Larangular\Installable\Support\{InstallableServiceProvider as ServiceProvider, PublisableGroups};

class MetadataServiceProvider extends ServiceProvider implements HasInstallable {

    protected $defer = false;

    public function boot(): void {
        $this->loadMigrationsFrom([
            __DIR__ . '/database/migrations',
            database_path('migrations/metadata'),
        ]);

        $this->declareMigrationGlobal();
        $this->declareMigrationMetadata();
    }

    public function installer(): Installable {
        return new Installer(__CLASS__);
    }

    private function declareMigrationGlobal(): void {
        $this->declareMigration([
            'connection'   => 'mysql',
            'migrations'   => [
                'local_path' => base_path() . '/vendor/larangular/metadata/database/migrations',
            ],
            'seeds'        => [
                'local_path' => __DIR__ . '/../database/seeds',
            ],
            'seed_classes' => [],
        ]);
    }

    private function declareMigrationMetadata(): void {
        $this->declareMigration([
            'name'      => 'metadata',
            'timestamp' => true,
        ]);
    }
}
