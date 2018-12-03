<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database\QueryBuilder;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\ConnectionAdapter\PdoMysql;
use Nekudo\ShinyCore\Database\Factory;
use Nekudo\ShinyCore\Tests\Mocks\StatementBuilderMock;
use Nekudo\ShinyCore\Tests\Mocks\WhereQueryBuilderMock;
use Nekudo\ShinyCore\Tests\Unit\Database\DatabaseTest;

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
        $config = include SC_TESTS . '/Mocks/config.php';
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
