<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\ConnectionAdapter;

interface ConnectionAdapterInterface
{
    public function connect(array $credentials): \PDO;
}
