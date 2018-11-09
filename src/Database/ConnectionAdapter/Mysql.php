<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\ConnectionAdapter;

use Nekudo\ShinyCore\Exception\Application\DatabaseException;

class Mysql implements ConnectionAdapterInterface
{
    /**
     * @todo Add support for unix socket
     * @todo Add support for PDO options
     *
     * @param array $credentials
     * @throws DatabaseException
     * @return \PDO
     */
    public function connect(array $credentials): \PDO
    {
        $dsn = 'mysql:';
        $dsnParams = [];
        if (!empty($credentials['host'])) {
            array_push($dsnParams, 'host='.$credentials['host']);
        }
        if (!empty($credentials['port'])) {
            array_push($dsnParams, 'port=' . $credentials['port']);
        }
        if (!empty($credentials['database'])) {
            array_push($dsnParams, 'dbname=' . $credentials['database']);
        }
        if (!empty($credentials['charset'])) {
            array_push($dsnParams, 'charset=' . $credentials['charset']);
        }
        $dsn .= implode(';', $dsnParams);

        try {
            return new \PDO($dsn, $credentials['username'], $credentials['password']);
        } catch (\PDOException $e) {
            throw new DatabaseException(sprintf('Error connecting to database (%s)', $e->getMessage()));
        }
    }
}
