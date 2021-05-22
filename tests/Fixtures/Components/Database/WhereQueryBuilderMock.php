<?php

namespace Bloatless\Endocore\Tests\Fixtures\Components\Database;

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
