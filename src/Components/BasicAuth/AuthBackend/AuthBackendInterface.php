<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\BasicAuth\AuthBackend;

interface AuthBackendInterface
{
    public function validateCredentials(string $username, string $password): bool;
}
