<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\BasicAuth\AuthBackend;

use Bloatless\Endocore\Components\Database\Factory as QueryBuilderFactory;

class MysqlAuthBackend extends AuthBackend
{
    /**
     * @var QueryBuilderFactory $queryBuilderFactory
     */
    protected $queryBuilderFactory;

    /**
     * @var string $connectionName
     */
    protected $connectionName = '';

    public function __construct(QueryBuilderFactory $queryBuilderFactory, string $connectionName = '')
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->connectionName = $connectionName;
    }

    /**
     * Matches user credentials against database.
     *
     * @param string $username
     * @param string $password
     * @return bool
     * @throws \Bloatless\Endocore\Components\Database\Exception\DatabaseException
     */
    public function validateCredentials(string $username, string $password): bool
    {
        $queryBuilder = $this->queryBuilderFactory->makeSelect($this->connectionName);
        $row = $queryBuilder->cols(['password'])
            ->from('users')
            ->whereEquals('username', $username)
            ->first();

        if (empty($row)) {
            return false;
        }

        return password_verify($password, $row->password);
    }
}
