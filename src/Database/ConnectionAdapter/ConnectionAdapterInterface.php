<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Database\ConnectionAdapter;

interface ConnectionAdapterInterface
{
    public function connect(array $credentials): \PDO;
}
