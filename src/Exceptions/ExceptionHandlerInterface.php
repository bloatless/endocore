<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Exceptions;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Logger\LoggerInterface;
use Nekudo\ShinyCore\Request;

interface ExceptionHandlerInterface
{
    public function __construct(Config $config, LoggerInterface $logger, Request $request);

    public function handleError(\Error $e): void;

    public function handleException(\Exception $e): void;
}
