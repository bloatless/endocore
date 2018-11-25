<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database;


class FactoryTest extends DatabaseTest
{
    public function testCreateDataSet()
    {
        $dataSet = $this->getConnection()->createDataSet();
        $this->assertSame(2, $this->getConnection()->getRowCount('testdata'));
    }
}
