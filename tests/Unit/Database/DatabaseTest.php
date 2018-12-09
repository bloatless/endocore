<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database;

use PHPUnit\Framework\TestCase;

abstract class DatabaseTest extends TestCase
{
    /**
     * @var \PDO $pdo
     */
    static private $pdo = null;

    /**
     * Initializes database connection.
     *
     * @return \PDO
     */
    final public function getConnection()
    {
        if (self::$pdo === null) {
            self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
        }

        return self::$pdo;
    }

    public function setUp()
    {
        $this->initDatabase();
        $this->seedDatabase();
    }

    public function tearDown()
    {
        $this->tearDownDatabase();
    }

    public function resetDatabase()
    {
        $this->tearDownDatabase();
        $this->initDatabase();
        $this->seedDatabase();
    }

    public function seedDatabase()
    {
        $statement = file_get_contents(SC_TESTS . '/Fixtures/seeds/testdata_seed.sql');
        $this->getConnection()->query($statement);
    }

    public function initDatabase()
    {
        $statement = file_get_contents(SC_TESTS . '/Fixtures/seeds/create_tables.sql');
        $this->getConnection()->query($statement);
    }

    public function tearDownDatabase()
    {
        $statement = file_get_contents(SC_TESTS . '/Fixtures/seeds/drop_tables.sql');
        $this->getConnection()->query($statement);
    }

    public function getRowCount($table)
    {
        $statement = sprintf('SELECT COUNT(*) FROM `%s`', $table);
        $res = $this->getConnection()->query($statement);
        return $res->fetchColumn();
    }
}
