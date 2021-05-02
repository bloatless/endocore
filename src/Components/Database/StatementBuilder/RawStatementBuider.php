<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Database\StatementBuilder;

class RawStatementBuider extends StatementBuilder
{
    /**
     * Prepares a raw statement and binds possible values.
     *
     * @param string $statement
     * @param array $bindings
     * @return void
     */
    public function prepareRawStatement(string $statement, array $bindings): void
    {
        $this->statement = $statement;
        if (empty($bindings)) {
            return;
        }

        foreach ($bindings as $key => $value) {
            $this->addBindingValue($key, $value);
        }
    }
}
