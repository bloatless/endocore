<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Database\ConnectionAdapter;

interface ConnectionAdapterInterface
{
    public function connect(array $credentials): \PDO;
}
