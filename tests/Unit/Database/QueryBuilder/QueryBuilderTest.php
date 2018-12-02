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

    public function setUp(): void
    {
        parent::setUp();
        $config = include SC_TESTS . '/Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->factory = new Factory($this->config);
    }

    public function testCanBeInitialized()
    {
        $connection = (new PdoMysql)->connect($this->config->getDefaultDbConfig());
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($connection, $statementBuilder);
        $this->assertInstanceOf(QueryBuilderMock::class, $queryBuilder);
    }

    public function testSetGetStatementBuilder()
    {
        $connection = (new PdoMysql)->connect($this->config->getDefaultDbConfig());
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($connection, $statementBuilder);

        $selectStatementBuilder = new SelectStatementBuilder;
        $queryBuilder->setStatementBuilder($selectStatementBuilder);
        $this->assertInstanceOf(SelectStatementBuilder::class, $queryBuilder->getStatementBuilder());
    }

    public function testExecute()
    {
        $connection = (new PdoMysql)->connect($this->config->getDefaultDbConfig());
        $statementBuilder = new StatementBuilderMock;
        $queryBuilder = new QueryBuilderMock($connection, $statementBuilder);

        // test valid statement:
        $statement = $connection->prepare('SELECT COUNT(*) FROM `customers`');
        $this->assertInstanceOf(\PDOStatement::class, $queryBuilder->execute($statement));

        // test invalid statement:
        $statement = $connection->prepare('SELECT * FROM `customers` WHERE customer_id = ?');
        $this->expectException(DatabaseException::class);
        $queryBuilder->execute($statement);
    }
}
