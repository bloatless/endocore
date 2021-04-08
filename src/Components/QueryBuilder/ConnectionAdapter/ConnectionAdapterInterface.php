<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\QueryBuilder\ConnectionAdapter;

interface ConnectionAdapterInterface
{
    public function connect(array $credentials): \PDO;
}
