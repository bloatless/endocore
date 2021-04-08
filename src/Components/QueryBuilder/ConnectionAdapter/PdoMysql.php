<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\QueryBuilder\ConnectionAdapter;

use Bloatless\Endocore\Components\QueryBuilder\Exception\DatabaseException;

class PdoMysql implements ConnectionAdapterInterface
{
    /**
     * @todo Add support for unix socket
     * @todo Add support for PDO options
     *
     * @param array $credentials
     * @throws DatabaseException
     * @throws \Exception
     * @return \PDO
     */
    public function connect(array $credentials): \PDO
    {
        $dsn = 'mysql:';
        $dsnParams = [];
        $pdoOptions = [];
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

        if (!empty($credentials['timezone'])) {
            $tzOffset = $this->getMysqlTimeZoneOffset($credentials['timezone']);
            $pdoOptions = [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '" . $tzOffset . "'"
            ];
        }

        try {
            $username = $credentials['username'] ?? '';
            $password = $credentials['password'] ?? '';
            $pdo = new \PDO($dsn, $username, $password, $pdoOptions);
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            return $pdo;
        } catch (\PDOException $e) {
            throw new DatabaseException(sprintf('Error connecting to database (%s)', $e->getMessage()));
        }
    }

    /**
     * Calculates offset between given timezone and UTC in mysql compatible format.
     *
     * @param string $timezone
     * @return string
     * @throws \Exception
     */
    private function getMysqlTimeZoneOffset(string $timezone): string
    {
        $tzUtc = new \DateTimeZone('UTC');
        $tLocal = new \DateTimeZone($timezone);
        $timeUtc = new \DateTime('now', $tzUtc);
        $offsetSeconds = $tLocal->getOffset($timeUtc);
        $offsetHours = $offsetSeconds / 3600;
        $offsetHours = ($offsetHours < 0) ? $offsetHours * -1 : $offsetHours;
        $prefix = ($offsetSeconds < 0) ? '-' : '+';
        return $prefix . sprintf('%02d:00', $offsetHours);
    }
}
