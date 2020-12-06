<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Domain;

class Payload implements \ArrayAccess
{
    public const STATUS_OK = 200;

    public const STATUS_BAD_REQUEST = 400;

    public const STATUS_NOT_FOUND = 404;

    public const STATUS_METHOD_NOT_ALLOWED = 405;

    public const STATUS_ERROR = 500;

    private array $container;

    private int $status;

    public function __construct(int $status = self::STATUS_OK, array $data = [])
    {
        $this->status = $status;
        $this->container = $data;
    }

    public function setStatus(int $status): void
    {
        $this->status  = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    public function asArray(): array
    {
        return $this->container;
    }

    public function asJson(): string
    {
        return json_encode($this->container);
    }
}
