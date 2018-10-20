<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Interfaces;

interface RendererInterface
{
    public function assign(array $pairs) : void;

    public function render(string $view = '', array $data = []) : string;
}
