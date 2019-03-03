<?php

namespace Bloatless\Endocore\Tests\Unit\Database\QueryBuilder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Database\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Database\Factory;
use Bloatless\Endocore\Database\StatementBuilder\SelectStatementBuilder;
use Bloatless\Endocore\Exception\Application\DatabaseException;
use Bloatless\Endocore\Tests\Fixtures\QueryBuilderMock;
use Bloatless\Endocore\Tests\Fixtures\StatementBuilderMock;
use Bloatless\Endocore\Tests\Unit\Database\DatabaseTest;

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
        $config = include SC_TESTS . '/Fixtures/config.php';
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
