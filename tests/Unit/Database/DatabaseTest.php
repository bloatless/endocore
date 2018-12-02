<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database;

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTest extends TestCase
{
    use TestCaseTrait;

    /**
     * @var \PDO $pdo
     */
    static private $pdo = null;

    /**
     * @var \PHPUnit\DbUnit\Database\Connection $connection
     */
    private $connection = null;

    /**
     * Initialized connection to database.
     *
     * @return \PHPUnit\DbUnit\Database\Connection|\PHPUnit\DbUnit\Database\DefaultConnection
     */
    final public function getConnection()
    {
        if (self::$pdo === null) {
            self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        }
        if ($this->connection === null) {
            $this->connection = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        $this->initDatabase();

        return $this->connection;
    }

    public function getDataSet()
    {
        return $this->createXMLDataSet(SC_TESTS . '/Mocks/seeds/testdata.xml');
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->tearDownDatabase();
    }

    public function initDatabase()
    {
        $statement = file_get_contents(SC_TESTS . '/Mocks/seeds/create_tables.sql');
        self::$pdo->query($statement);
    }

    public function tearDownDatabase()
    {
        $statement = file_get_contents(SC_TESTS . '/Mocks/seeds/drop_tables.sql');
        self::$pdo->query($statement);
    }
}
