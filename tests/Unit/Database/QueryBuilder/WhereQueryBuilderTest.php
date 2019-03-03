<?php

namespace Bloatless\Endocore\Tests\Unit\Database\QueryBuilder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Database\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Database\Factory;
use Bloatless\Endocore\Tests\Fixtures\StatementBuilderMock;
use Bloatless\Endocore\Tests\Fixtures\WhereQueryBuilderMock;
use Bloatless\Endocore\Tests\Unit\Database\DatabaseTest;

class WhereQueryBuilderTest extends DatabaseTest
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
        $config = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($config);
    }

    public function testSetter()
    {
        $connection = (new PdoMysql)->connect($this->config->getDefaultDbConfig());
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
