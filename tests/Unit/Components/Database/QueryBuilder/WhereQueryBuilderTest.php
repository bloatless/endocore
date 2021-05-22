<?php

namespace Bloatless\Endocore\Tests\Unit\Components\Database\QueryBuilder;

use Bloatless\Endocore\Components\Database\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Tests\Fixtures\Components\Database\StatementBuilderMock;
use Bloatless\Endocore\Tests\Fixtures\Components\Database\WhereQueryBuilderMock;
use Bloatless\Endocore\Tests\Unit\Components\Database\AbstractDatabaseTest;

class WhereQueryBuilderTest extends AbstractDatabaseTest
{
    public $defaultCredentials;

    public function setUp(): void
    {
        parent::setUp();
        $configData = include TESTS_ROOT . '/Fixtures/config.php';
        $config = $configData['db'];
        $defaultConnection = $config['default_connection'];
        $this->defaultCredentials = $config['connections'][$defaultConnection];
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
