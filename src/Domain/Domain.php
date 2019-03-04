<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Domain;

use Bloatless\Endocore\Components\Logger\LoggerInterface;

abstract class Domain
{
    /**
     * @var array $config
     */
    protected $config;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    public function __construct(array $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }
}
