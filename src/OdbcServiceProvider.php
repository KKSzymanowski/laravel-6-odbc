<?php

namespace Odbc;

use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class OdbcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Connection::resolverFor('odbc', function ($connection, $database, $prefix, $config) {
            $connector = new OdbcConnector();
            $pdo = $connector->connect($config);

            return new OdbcConnection($pdo, $database, $prefix);
        });
    }
}
