<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\Renderer;

class JsStack
{
    private static ?JsStack $instance = null;

    private array $stack = [];

    public static function getInstance(): JsStack
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function add(string $jsCode): void
    {
        $jsCodeHash = md5($jsCode);
        $this->stack[$jsCodeHash] = $jsCode;
    }

    public function all(): array
    {
        return $this->stack;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \RuntimeException('JsStack can not unserialized.');
    }
}
