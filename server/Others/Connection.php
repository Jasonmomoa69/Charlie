<?php

namespace Server\Others;

class Connection
{
    public $capsule;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        $warm_database = [
            'port' => 3306,
            'engine' => null,
            'strict' => false,
            'driver' => 'mysql',
            'charset' => 'utf8',
            'host' => '127.0.0.1',
            'collation' => 'utf8_unicode_ci',
            'password' => getenv("DB_PASS"),
            'username' => getenv("DB_USER"),
            'database' => getenv("DB_NAME"),
        ];

        $cold_database = [
            'port' => 3306,
            'engine' => null,
            'strict' => false,
            'driver' => 'mysql',
            'charset' => 'utf8',
            'host' => '127.0.0.1',
            'collation' => 'utf8_unicode_ci',
            'password' => getenv("DB_PASS"),
            'username' => getenv("DB_USER")."_cold",
            'database' => getenv("DB_NAME")."_cold",
        ];

        $capsule = new \Illuminate\Database\Capsule\Manager;
        $capsule->addConnection($warm_database);
        $capsule->addConnection($cold_database, "cold_database");
        $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        \Illuminate\Pagination\Paginator::currentPathResolver(function () {
            return strtok($_SERVER['REQUEST_URI'] ?? "", '?') ?? '/';
        });

        \Illuminate\Pagination\Paginator::currentPageResolver(function () {
            return $_GET['page'] ?? 1;
        });

        $this->capsule = $capsule;
    }
}