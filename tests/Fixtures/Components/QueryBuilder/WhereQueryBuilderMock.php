<?php

namespace Bloatless\Endocore\Tests\Fixtures\Components\QueryBuilder;

use Bloatless\Endocore\Components\Database\QueryBuilder\WhereQueryBuilder;

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
