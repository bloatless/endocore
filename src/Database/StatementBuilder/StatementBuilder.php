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

    /**
     * Quotes a table or field name.
     *
     * @param string $name
     * @return string
     */
    protected function quoteName(string $name): string
    {
        $name = str_replace('`', '', $name);
        if (stripos($name, ' AS ') === false) {
            return $this->quoteSimpleName($name);
        }
        return $this->quoteAliasedName($name);
    }

    /**
     * Quotes a table of field name without  alias.
     * Example: user.id will become `user`.`id`
     *
     * @param string $name
     * @return string
     */
    private function quoteSimpleName(string $name): string
    {
        $parts = explode('.', $name);
        foreach ($parts as $i => $part) {
            $parts[$i] = sprintf('`%s`', $part);
        }
        return implode('.', $parts);
    }

    /**
     * Quotes table or field name with alias.
     * Example: "select from users as u" will become "select from `users` as `u`"
     *
     * @param string $name
     * @return string
     */
    private function quoteAliasedName(string $name): string
    {
        $parts = preg_split('/ AS /i', $name);
        foreach ($parts as $i => $part) {
            $parts[$i] = $this->quoteSimpleName($part);
        }
        return implode(' AS ', $parts);
    }
}
