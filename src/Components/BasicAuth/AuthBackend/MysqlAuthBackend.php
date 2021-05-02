<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\BasicAuth\AuthBackend;

use Bloatless\Endocore\Components\Database\Database;

class MysqlAuthBackend extends AuthBackend
{
    private Database $database;

    private string $connectionName;

    public function __construct(Database $database, string $connectionName = '')
    {
        $this->database = $database;
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
        $queryBuilder = $this->database->makeSelect($this->connectionName);
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
