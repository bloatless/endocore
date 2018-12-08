<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Domain;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Logger\LoggerInterface;

abstract class Domain
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    public function __construct(Config $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }
}
