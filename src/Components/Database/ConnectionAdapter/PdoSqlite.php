<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Database\ConnectionAdapter;

use Bloatless\Endocore\Components\Database\Exception\DatabaseException;

class PdoSqlite implements ConnectionAdapterInterface
{
    /**
     * @param array $credentials
     * @throws DatabaseException
     * @throws \Exception
     * @return \PDO
     */
    public function connect(array $credentials): \PDO
    {
        $database = $credentials['database'] ?? '';
        $dsn = 'sqlite:' . $database;

        try {
            $pdo = new \PDO($dsn);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $pdo;
        } catch (\PDOException $e) {
            throw new DatabaseException(sprintf('Error connecting to database (%s)', $e->getMessage()));
        }
    }
}
