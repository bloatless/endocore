<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

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
