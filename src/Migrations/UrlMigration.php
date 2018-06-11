<?php declare(strict_types = 1);

namespace Searchmetrics\SeniorTest\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class UrlMigration
{
    /**
     * @var string
     */
    private $table = 'urls';

    /**
     * UrlMigration constructor.
     */
    public function __construct()
    {

    }
    public function create()
    {
        Capsule::schema()->create($this->table, function ($table) {
            $table->increments('id');
            $table->string('url');
            $table->string('code');
            $table->timestamps();
        });

   }
    public function hasTable()
    {
        return Capsule::schema()->hasTable($this->table);
    }
}