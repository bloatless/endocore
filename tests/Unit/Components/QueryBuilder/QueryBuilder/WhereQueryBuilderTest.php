<?php

namespace Bloatless\Endocore\Components\QueryBuilder\Tests\Unit\QueryBuilder;

use Bloatless\Endocore\Components\QueryBuilder\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Components\QueryBuilder\Factory;
use Bloatless\Endocore\Components\QueryBuilder\Tests\Fixtures\StatementBuilderMock;
use Bloatless\Endocore\Components\QueryBuilder\Tests\Fixtures\WhereQueryBuilderMock;
use Bloatless\Endocore\Components\QueryBuilder\Tests\Unit\DatabaseTest;

class WhereQueryBuilderTest extends DatabaseTest
{
    public $config;

    public $defaultCredentials;

    public $factory;

    public function setUp(): void
    {
        parent::setUp();
        $configData = include TESTS_ROOT . '/Fixtures/config.php';
        $this->config = $configData['db'];
        $defaultConnection = $this->config['default_connection'];
        $this->defaultCredentials = $this->config['connections'][$defaultConnection];
    }

    public function testSetter()
    {
        $connection = (new PdoMysql)->connect($this->defaultCredentials);
        $statementBuilder = new StatementBuilderMock;
        $builder = new WhereQueryBuilderMock($connection, $statementBuilder);
        $builder = $builder->where('customer_id', '=', 1)
            ->whereEquals('customer_id', 1)
            ->orWhere('customer_id', '=', 2)
            ->whereIn('customer_id', [1, 2])
            ->whereNotIn('customer_id', [3, 4])
            ->orWhereIn('customer_id', [1, 2])
            ->orWhereNotIn('customer_id', [3, 4])
            ->whereBetween('customer_id', 1, 2)
            ->orWhereBetween('customer_id', 3, 4)
            ->whereNull('firstname')
            ->whereNotNull('lastname')
            ->orWhereNull('lastname')
            ->orWhereNotNull('firstname');
        $this->assertInstanceOf(WhereQueryBuilderMock::class, $builder);
    }
}
