<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Domain;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Logger\LoggerInterface;

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
