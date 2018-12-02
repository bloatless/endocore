<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database\QueryBuilder;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\ConnectionAdapter\PdoMysql;
use Nekudo\ShinyCore\Database\Factory;
use Nekudo\ShinyCore\Database\StatementBuilder\SelectStatementBuilder;
use Nekudo\ShinyCore\Exception\Application\DatabaseException;
use Nekudo\ShinyCore\Tests\Mocks\QueryBuilderMock;
use Nekudo\ShinyCore\Tests\Mocks\StatementBuilderMock;
use Nekudo\ShinyCore\Tests\Unit\Database\DatabaseTest;

class QueryBuilderTest extends DatabaseTest
{
    /**
     * @var Config $config
     */
    public $config;

    /**
     * @var Factory $factory
     */
    public $factory;

    /**
     * @var \PDO $connection
     */
    public $connection;

    public function setUp(): void
    {
        parent::setUp();
        $config = include SC_TESTS . '/Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->factory = new Factory($this->config);
        $this->connection = (new PdoMysql)->connect($this->config->getDefaultDbConfig());
    }

    public function testCanBeInitialized()
    {

        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $this->assertInstanceOf(QueryBuilderMock::class, $queryBuilder);
    }

    public function testSetGetStatementBuilder()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);

        $selectStatementBuilder = new SelectStatementBuilder;
        $queryBuilder->setStatementBuilder($selectStatementBuilder);
        $this->assertInstanceOf(SelectStatementBuilder::class, $queryBuilder->getStatementBuilder());
    }

    public function testExecute()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);

        // test valid statement:
        $statement = $this->connection->prepare('SELECT COUNT(*) FROM `customers`');
        $this->assertInstanceOf(\PDOStatement::class, $queryBuilder->execute($statement));

        // test invalid statement:
        $statement = $this->connection->prepare('SELECT * FROM `customers` WHERE customer_id = ?');
        $this->expectException(DatabaseException::class);
        $queryBuilder->execute($statement);
    }

    public function testProvideStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $queryBuilder->setTestStatement('SELECT COUNT(*) FROM `customers`', []);
        $this->assertInstanceOf(\PDOStatement::class, $queryBuilder->execProvideStatement());
    }

    public function testPrepareStatement()
    {
        // test all datatype bindings:
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $statement = $queryBuilder->execPrepareStatement(
            'SELECT * FROM customers WHERE customer_id IN (:p1,:p2,:p3,:p4)',
            [
                'p1' => 1,
                'p2' => false,
                'p3' => null,
                'p4' => '2'
            ]
        );
        $this->assertInstanceOf(\PDOStatement::class, $statement);

        // test invalid query:
        $this->expectException(DatabaseException::class);
        $queryBuilder->execPrepareStatement('SELECT * FROM foo', []);
    }
}
