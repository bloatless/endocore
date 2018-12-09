<?php

namespace Nekudo\ShinyCore\Tests\Fixtures;

use Nekudo\ShinyCore\Database\QueryBuilder\WhereQueryBuilder;

class WhereQueryBuilderMock extends WhereQueryBuilder
{
    public function reset(): void
    {
        // just a mock
    }

    protected function buildStatement(): string
    {
        return '';
    }
}
