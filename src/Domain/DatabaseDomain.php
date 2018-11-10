<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Domain;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\Factory;

class DatabaseDomain
{
    protected $config;

    protected $db;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->db = new Factory($config);
    }
}
