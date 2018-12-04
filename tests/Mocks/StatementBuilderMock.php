<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Database\StatementBuilder\StatementBuilder;

class StatementBuilderMock extends StatementBuilder
{
    /**
     * Sets a statement.
     *
     * @param string $statement
     */
    public function setStatement(string $statement): void
    {
        $this->statement = $statement;
    }

    /**
     * Exposes protected quoteName method.
     *
     * @param string $name
     * @return string
     */
    public function exposedQuoteName(string $name): string
    {
        return $this->quoteName($name);
    }
}
