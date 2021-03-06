<?php

namespace Odbc;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Connection;
use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Processors\Processor;

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

    /**
     * Get the default post processor instance.
     *
     * @return Processor
     */
    protected function getDefaultPostProcessor()
    {
        /** @var Repository $config */
        $config = Container::getInstance()->make(Repository::class);
        $class = $config->get('database.connections.odbc.processor') ?: OdbcProcessor::class;

        return new $class;
    }
}
