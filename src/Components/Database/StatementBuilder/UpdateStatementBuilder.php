<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Database\StatementBuilder;

class UpdateStatementBuilder extends WhereStatementBuilder
{
    public function __construct()
    {
        $this->statement = 'UPDATE';
    }

    /**
     * Adds table name to UPDATE statement.
     *
     * @param string $table
     * @return void
     */
    public function addTable(string $table): void
    {
        $this->statement .= ' ' . $this->quoteName($table);
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds field assignments to UPDATE query.
     *
     * @param array $cols
     * @return void
     */
    public function addCols(array $cols): void
    {
        $this->statement .= 'SET ' . PHP_EOL;
        $assignments = [];
        foreach ($cols as $key => $value) {
            $placeholder = $this->addBindingValue($key, $value);
            $assignment = sprintf('%s = %s', $this->quoteName($key), $placeholder);
            array_push($assignments, $assignment);
        }
        $this->statement .= implode(', ', $assignments);
        $this->statement .= PHP_EOL;
    }
}
