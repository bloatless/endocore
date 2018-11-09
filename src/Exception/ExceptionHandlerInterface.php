<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Exception;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Http\Response;
use Nekudo\ShinyCore\Logger\LoggerInterface;
use Nekudo\ShinyCore\Http\Request;

interface ExceptionHandlerInterface
{
    public function __construct(Config $config, LoggerInterface $logger, Request $request);

    /**
     * Handles internal php errors.
     *
     * @param \Error $e
     * @return Response
     */
    public function handleError(\Error $e): Response;

    /**
     * Handles exceptions.
     *
     * @param \Exception $e
     * @return Response
     */
    public function handleException(\Exception $e): Response;
}
