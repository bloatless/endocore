<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

class InsertStatementBuilder extends StatementBuilder
{
    public function __construct()
    {
        $this->statement = 'INSERT';
    }

    /**
     * Adds table name to insert statement.
     *
     * @param string $table
     * @return void
     */
    public function addInto(string $table): void
    {
        $table = $this->quoteName($table);
        $this->statement .= ' INTO ' . $table;
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds column names and values to insert statement.
     *
     * @param array $rows
     * @return void
     */
    public function addRows(array $rows): void
    {
        $cols = array_keys(reset($rows));
        foreach ($cols as $i => $col) {
            $cols[$i] = $this->quoteName($col);
        }
        $pattern = " (%s) VALUES" . PHP_EOL;
        $this->statement .= sprintf($pattern, implode(',', $cols));

        $rowPatterns = [];
        foreach ($rows as $row) {
            $placeholders = [];
            foreach ($row as $key => $value) {
                $placeholder = $this->addBindingValue($key, $value);
                array_push($placeholders, $placeholder);
            }
            array_push($rowPatterns, sprintf('(%s)', implode(',', $placeholders)));
        }
        $this->statement .= implode(',', $rowPatterns);
    }
}
