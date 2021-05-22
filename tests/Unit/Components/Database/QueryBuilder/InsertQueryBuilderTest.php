<?php

namespace Bloatless\Endocore\Tests\Unit\Components\Database\QueryBuilder;

use Bloatless\Endocore\Components\Database\Database;
use Bloatless\Endocore\Components\Database\DatabaseFactory;
use Bloatless\Endocore\Components\Database\QueryBuilder\InsertQueryBuilder;
use Bloatless\Endocore\Tests\Unit\Components\Database\AbstractDatabaseTest;

class InsertQueryBuilderTest extends AbstractDatabaseTest
{
    private Database $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $factory = new DatabaseFactory($config);
        $this->db = $factory->make();
    }

    public function testIgnore()
    {
        $builder = $this->db->makeInsert();
        $this->assertInstanceOf(InsertQueryBuilder::class, $builder->ignore());
    }

    public function testInto()
    {
        $builder = $this->db->makeInsert();
        $this->assertInstanceOf(InsertQueryBuilder::class, $builder->into('customers'));
    }
    public function testRow()
    {
        $builder = $this->db->makeInsert();
        $builder->into('customers')
            ->row([
                'firstname' => 'Maggie',
                'lastname' => 'Simpson',
                'email' => 'maggie@simpsons.com'
            ]);
        $this->assertEquals(5, $this->getRowCount('customers'));
    }

    public function testRows()
    {
        $builder = $this->db->makeInsert();
        $builder->into('customers')
            ->rows([
                [
                    'firstname' => 'Santa',
                    'lastname' => 'Simpson',
                    'email' => 'santa@simpsons.com'
                ],
                [
                    'firstname' => 'Snowball',
                    'lastname' => 'Simpson',
                    'email' => 'snowball@simpsons.com'
                ]
            ]);
        $this->assertEquals(6, $this->getRowCount('customers'));
    }

    public function testGetLastInsertId()
    {
        $builder = $this->db->makeInsert();
        $builder->into('customers')
            ->row([
                'firstname' => 'Maggie',
                'lastname' => 'Simpson',
                'email' => 'maggie@simpsons.com'
            ]);
        $this->assertEquals(5, $builder->getLastInsertId());
    }

    public function testReset()
    {
        $builder = $this->db->makeInsert()
            ->into('foobar');
        $builder->reset();
        $builder->into('customers')
            ->row([
                'firstname' => 'Maggie',
                'lastname' => 'Simpson',
                'email' => 'maggie@simpsons.com'
            ]);
        $this->assertEquals(5, $builder->getLastInsertId());
    }
}
