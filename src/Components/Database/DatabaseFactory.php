<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Database;

use Bloatless\Endocore\Components\Database\Exception\DatabaseException;
use Bloatless\Endocore\Contracts\Components\FactoryContract;

class DatabaseFactory implements FactoryContract
{
    /**
     * @var array $config
     */
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function make(): Database
    {
        if (empty($this->config['db'])) {
            throw new DatabaseException('Can not provide component "Database". Configuration missing.');
        }

        $dbConfig = $this->config['db'];
        $credentials = $dbConfig['connections'];
        $defaultConnectionName = $dbConfig['default_connection'] ?? key(reset($dbConfig['connections']));

        return new Database($credentials, $defaultConnectionName);
    }
}
