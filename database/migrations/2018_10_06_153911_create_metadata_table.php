<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\MigrationPackage\Migration\Schematics;

class CreateMetadataTable extends Migration {

    use Schematics;

    protected $name;
    private   $installableConfig;


    public function __construct() {
        $this->installableConfig = InstallableConfig::config('Larangular\Metadata\MetadataServiceProvider');
        $this->connection = $this->installableConfig->getConnection('metadata');
        $this->name = $this->installableConfig->getName('metadata');
    }

    public function up(): void {
        $this->create(function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('metable');
            $table->string('type')
                  ->default('null');

            $table->string('key')
                  ->index();
            $table->text('value')
                  ->nullable();

            if ($this->installableConfig->getTimestamp('metadata')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void {
        $this->drop();
    }
}

