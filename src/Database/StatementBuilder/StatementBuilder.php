<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

abstract class StatementBuilder
{
    /**
     * @var string $statement
     */
    protected $statement = '';

    /**
     * @var array $bindingValues
     */
    protected $bindingValues = [];

    /**
     * @var array $bindingValueCounts
     */
    protected $bindingValueCounts = [];

    /**
     * Retrieves the SQL statement.
     *
     * @return string
     */
    public function getStatement(): string
    {
        return $this->statement;
    }

    /**
     * Retrieves list of binding values.
     *
     * @return array
     */
    public function getBindingValues(): array
    {
        return $this->bindingValues;
    }

    /**
     * Adds a value which will be used to prepare the SQL statement.
     * Returns the placeholder name to use in SQL statement.
     *
     * @param string $key
     * @param mixed $value
     * @return string The placeholder name.
     */
    public function addBindingValue(string $key, $value): string
    {
        $key = $this->removeTableFromKey($key);
        $placeholder = ':' . $key;
        if (!isset($this->bindingValues[$key])) {
            $this->bindingValues[$key] = $value;
            $this->bindingValueCounts[$key] = 1;
            return $placeholder;
        }

        $placeholder .= $this->bindingValueCounts[$key];
        $this->bindingValueCounts[$key]++;
        $this->bindingValues[$placeholder] = $value;
        return $placeholder;
    }

    /**
     * Returns a key/field-name without the table prefix.
     *
     * @param string $key
     * @return string
     */
    protected function removeTableFromKey(string $key): string
    {
        if (strpos($key, '.') === false) {
            return $key;
        }
        $keyParts = explode('.', $key);
        return array_pop($keyParts);
    }
}
