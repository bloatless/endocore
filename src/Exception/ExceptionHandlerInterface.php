<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Exception;

use Bloatless\Endocore\Http\Response;
use Bloatless\Endocore\Components\Logger\LoggerInterface;
use Bloatless\Endocore\Http\Request;

interface ExceptionHandlerInterface
{
    public function __construct(array $config, LoggerInterface $logger, Request $request);

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
