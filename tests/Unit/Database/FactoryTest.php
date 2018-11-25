<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database;

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    use TestCaseTrait;

    // only instantiate pdo once for test clean-up/fixture load
    private $pdo = null;

    // only instantiate PHPUnit\DbUnit\Database\Connection once per test
    private $connection = null;

    private $initialized = false;

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
        $statement = "CREATE TABLE IF NOT EXISTS `testdata` (
                 `id` INT UNSIGNED,
                 `item_name` VARCHAR(200),
                 `item_value` VARCHAR(200)
                 );";
        $this->pdo->query($statement);
    }

    public function tearDownDatabase()
    {
        $this->pdo->query("DROP TABLE IF EXISTS `testdata`");
    }

    public function testCreateDataSet()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $this->assertSame(2, $this->getConnection()->getRowCount('testdata'));
    }
}
