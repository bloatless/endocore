<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\Components;

interface FactoryContract
{
    public function __construct(array $config);

    public function make();
}
