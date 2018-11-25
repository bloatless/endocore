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
    private $pdo = null;

    /**
     * @var \PHPUnit\DbUnit\Database\Connection $connection
     */
    private $connection = null;

    /**
     * @var bool $initialized
     */
    private $initialized = false;

    /**
     * Initialized connection to database.
     *
     * @return \PHPUnit\DbUnit\Database\Connection|\PHPUnit\DbUnit\Database\DefaultConnection
     */
    final public function getConnection()
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        }
        if ($this->connection === null) {
            $this->connection = $this->createDefaultDBConnection($this->pdo, $GLOBALS['DB_DBNAME']);
        }

        if ($this->initialized === false) {
            $this->initDatabase();
            $this->initialized = true;
        }

        return $this->connection;
    }

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/../../Mocks/seeds/query_builder_testdata.xml');
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->tearDownDatabase();
    }

    public function initDatabase()
    {
        $statement = file_get_contents(__DIR__ . '/../../Mocks/seeds/create_tables.sql');
        $this->pdo->query($statement);
    }

    public function tearDownDatabase()
    {
        $statement = file_get_contents(__DIR__ . '/../../Mocks/seeds/drop_tables.sql');
        $this->pdo->query($statement);
    }
}
