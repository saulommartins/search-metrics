<?php

namespace Searchmetrics\SeniorTest\Config;

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

class DatabaseConnection {
    public function connect() {
        $dotenv = new Dotenv(__DIR__ . '../Config/config.env');

        $capsule = new Capsule;

        $capsule->addConnection([
            "driver"   => $dotenv->required("DB_CONNECTION"),
            "host"     => $dotenv->required("DB_HOST"),
            "database" => $dotenv->required("DB_DATABASE"),
            "username" => $dotenv->required("DB_USERNAME"),
            "password" => $dotenv->required("DB_PASSWORD"),
        ]);

        //Make this Capsule instance available globally.
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM.
        $capsule->bootEloquent();
    }
}
