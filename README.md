## ODBC driver for Laravel 6 and 7

Package inspired by [tck/odbc](https://github.com/bencarter78/odbc) but simplified, modernized and made compatible with Laravel 6, 7 and 8.

### Installation
```
composer require kkszymanowski/laravel-6-odbc
``` 
Add you database configuration in `config/database.php`, for example:
```php
'connections' => [
    'myOdbcConnection' => [
        'driver'   => 'odbc',
        'dsn'      => env('DB_ODBC_CONNECTION_STRING'),
        'host'     => env('DB_ODBC_HOST'),
        'database' => env('DB_ODBC_DATABASE'),
        'username' => env('DB_ODBC_USERNAME'),
        'password' => env('DB_ODBC_PASSWORD'),
    ],

    // ...
],
```
Add the environment variables referenced in the configuration to your `.env` file, for example:
```
DB_ODBC_CONNECTION_STRING="odbc:DRIVER=Pervasive;ServerName=192.168.0.1;DBQ=DATABASE;UID=User;PWD=Password"
DB_ODBC_HOST=192.168.0.1
DB_ODBC_DATABASE=DATABASE
DB_ODBC_USERNAME=User
DB_ODBC_PASSWORD=Password
```

If you would like to customize the schema grammar, query grammar or the post processor used in the ODBC connection you can do that by extending `\Odbc\OdbcSchemaGrammar`, `\Odbc\OdbcQueryGrammar` and `\Odbc\OdbcProcessor` respectively.
Then add the following configuration entries:
```
'database.connections.odbc.grammar.query'
'database.connections.odbc.grammar.schema'
'database.connections.odbc.processor'
```

For example in `config/database.php` add:
```php
'connections' => [
    // ...

    'odbc' => [
        'grammar' => [
            'query' => \App\Grammars\CustomQueryGrammar::class,
            'schema' => \App\Grammars\CustomSchemaGrammar::class,
        ],
        'processor' => \App\Processors\CustomProcessor::class,
    ],

    // ...
],
```
One of the more common cases would be to customize the `compileLimit()` method used in pagination and in the `skip()` method.
You can do this in the following way
```php
use Illuminate\Database\Query\Builder;
use Odbc\OdbcQueryGrammar;

class CustomQueryGrammar extends OdbcQueryGrammar
{
    /**
     * Compile the "limit" portions of the query.
     *
     * @param Builder $query
     * @param int     $limit
     *
     * @return string
     */
    protected function compileLimit(Builder $query, $limit)
    {
        return 'select top ' . (int) $limit;
    }
}
```

Note that the custom processor is **not** used when running raw queries, for example `$connection->select('SELECT * FROM USERS')`. 
To use it you must build the queries with the Eloquent query builder, for example:
```php
User::get();
DB::connection('myOdbcConnection')->table('USERS')->get(); 
```

### Usage
#### With Eloquent
To override your default database connection define `$connection` name in your Eloquent Model
```php
/**
 * The connection name for the model.
 *
 * @var string
 */
protected $connection = 'myOdbcConnection';
```
After defining the connection name you perform all the standard Eloquent operations:
```php
$user = User::where('id', 1)->get();
$users = User::all();
```

#### Without Eloquent
You can also perform queries without Eloquent models. Make sure you specify the connection name if it isn't your default one, for example:
```php
$user = DB::connection('myOdbcConnection')->select('SELECT * FROM USERS WHERE id = :id', ['id' => 1]);
$users = DB::connection('myOdbcConnection')->table('USERS')->where('id', 1)->get();
```
If you're running raw queries make sure to use parameter bindings wherever possible to avoid SQL Injection vulnerabilities.
