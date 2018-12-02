<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Database\QueryBuilder\QueryBuilder;

class QueryBuilderMock extends QueryBuilder
{
    protected function buildStatement(): string
    {
        return '';
    }
}
