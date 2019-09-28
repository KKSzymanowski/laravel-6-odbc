<?php

namespace Odbc;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Connection;
use Illuminate\Database\Grammar;

class OdbcConnection extends Connection
{

    /**
     * Get the default query grammar instance.
     *
     * @return Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        /** @var Repository $config */
        $config = Container::getInstance()->make(Repository::class);
        $class = $config->get('database.connections.odbc.grammar.query') ?: OdbcQueryGrammar::class;

        return $this->withTablePrefix(new $class);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        /** @var Repository $config */
        $config = Container::getInstance()->make(Repository::class);
        $class = $config->get('database.connections.odbc.grammar.schema') ?: OdbcSchemaGrammar::class;

        return $this->withTablePrefix(new $class);
    }
}
