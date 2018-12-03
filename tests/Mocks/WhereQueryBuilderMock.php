<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Database\QueryBuilder\WhereQueryBuilder;

class WhereQueryBuilderMock extends WhereQueryBuilder
{
    protected function buildStatement(): string
    {
        return '';
    }
}
