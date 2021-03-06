<?php

namespace Searchmetrics\SeniorTest\Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

class DatabaseConnection {
    private $connection;
    public function connect() {
        $dotenv = new Dotenv(__DIR__, "config.env");
        $dotenv->load();

        $capsule = new Capsule;
        $capsule->addConnection([
            "driver"   => getenv('DB_CONNECTION'),
            "host"     => getenv('DB_HOST'),
            "database" => getenv('DB_DATABASE'),
            "username" => getenv('DB_USERNAME'),
            "password" => getenv('DB_PASSWORD'),
        ]);

        //Make this Capsule instance available globally.
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM.
        $capsule->bootEloquent();
        $this->connection = $capsule;
    }
    public function disconnect() {
        $this->connection->connection()->disconnect();
    }
}
