<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Exceptions;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Logger\LoggerInterface;
use Nekudo\ShinyCore\Request;

interface ExceptionHandlerInterface
{
    public function __construct(Config $config, LoggerInterface $logger, Request $request);

    /**
     * Handles internal php errors.
     *
     * @param \Error $e
     */
    public function handleError(\Error $e): void;

    /**
     * Handles exceptions.
     *
     * @param \Exception $e
     */
    public function handleException(\Exception $e): void;
}
