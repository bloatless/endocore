<?php

namespace Bloatless\Endocore\Tests\Unit\Components\QueryBuilder\QueryBuilder;

use Bloatless\Endocore\Components\Database\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Components\Database\Factory;
use Bloatless\Endocore\Components\Database\StatementBuilder\SelectStatementBuilder;
use Bloatless\Endocore\Components\Database\Exception\DatabaseException;
use Bloatless\Endocore\Tests\Fixtures\Components\QueryBuilder\QueryBuilderMock;
use Bloatless\Endocore\Tests\Fixtures\Components\QueryBuilder\StatementBuilderMock;
use Bloatless\Endocore\Tests\Unit\Components\QueryBuilder\DatabaseTest;

class QueryBuilderTest extends DatabaseTest
{
    /**
     * @var array $config
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
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $defaultConnectionName = $config['db']['default_connection'];
        $this->config = $config['db'];
        $this->factory = new Factory($this->config);
        $this->connection = (new PdoMysql)->connect($this->config['connections'][$defaultConnectionName]);
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

    public function testExecuteWithValidStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $statement = $this->connection->prepare('SELECT COUNT(*) FROM `customers`');
        $this->assertInstanceOf(\PDOStatement::class, $queryBuilder->execute($statement));
    }

    public function testExecuteWithInvalidStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $statement = $this->connection->prepare('SELECT * FROM `customers` WHERE customer_id = ?');
        $this->expectException(DatabaseException::class);
        $queryBuilder->execute($statement);
    }

    public function testProvideStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $queryBuilder->setTestStatement('SELECT COUNT(*) FROM `customers`', []);
        $this->assertInstanceOf(\PDOStatement::class, $queryBuilder->exposedProvideStatement());
    }

    public function testPrepareStatementWithValidStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $statement = $queryBuilder->exposedPrepareStatement(
            'SELECT * FROM customers WHERE customer_id IN (:p1,:p2,:p3,:p4)',
            [
                'p1' => 1,
                'p2' => false,
                'p3' => null,
                'p4' => '2'
            ]
        );
        $this->assertInstanceOf(\PDOStatement::class, $statement);
    }

    public function testPrepareStatementWithInvalidStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($this->connection, $statementBuilder);
        $this->expectException(DatabaseException::class);
        $queryBuilder->exposedPrepareStatement('SELECT * FROM foo', []);
    }
}
