<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\BasicAuth;

use Bloatless\Endocore\Components\BasicAuth\AuthBackend\ArrayAuthBackend;
use Bloatless\Endocore\Components\BasicAuth\AuthBackend\MysqlAuthBackend;
use Bloatless\Endocore\Components\Database\Factory as QueryBuilderFactory;
use Bloatless\Endocore\Contracts\Components\FactoryContract;

class BasicAuthFactory implements FactoryContract
{
    /**
     * @var array $config
     */
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Creates and returns basic-auth object.
     *
     * @return BasicAuth
     * @throws BasicAuthException
     */
    public function make(): BasicAuth
    {
        if (empty($this->config['auth'])) {
            throw new BasicAuthException('Can not provide component "BasicAuth". Configuration missing.');
        }

        switch ($this->config['auth']['backend']) {
            case 'array':
                $authBackend = $this->getArrayAuthBackend();
                break;
            case 'mysql':
                $authBackend = $this->getMysqlAuthBackend();
                break;
            default:
                throw new BasicAuthException('Unknown or missing auth-backend. Can not provide auth object.');
        }

        return new BasicAuth($authBackend);
    }

    /**
     * Provides the "array authentication" backend.
     *
     * @return ArrayAuthBackend
     */
    protected function getArrayAuthBackend(): ArrayAuthBackend
    {
        $users = $this->config['auth']['backends']['array']['users'] ?? [];

        return new ArrayAuthBackend($users);
    }

    /**
     * Provides the "mysql authentication backend".
     *
     * @return MysqlAuthBackend
     * @throws BasicAuthException
     * @throws \Bloatless\Endocore\Components\Database\Exception\DatabaseException
     */
    protected function getMysqlAuthBackend(): MysqlAuthBackend
    {
        if (empty($this->config['db'])) {
            throw new BasicAuthException('Database configuration missing. Can not provide auth object.');
        }

        $connectionName = $this->config['auth']['backends']['mysql']['db_connection'] ?? '';
        $queryBuilderFactory = new QueryBuilderFactory($this->config['db']);

        return new MysqlAuthBackend($queryBuilderFactory, $connectionName);
    }
}
