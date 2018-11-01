<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Exceptions;

use Nekudo\ShinyCore\Exceptions\Http\BadRequestException;
use Nekudo\ShinyCore\Exceptions\Http\MethodNotAllowedException;
use Nekudo\ShinyCore\Exceptions\Http\NotFoundException;
use Nekudo\ShinyCore\Responder\HtmlResponder;

class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * Handles internal php errors.
     *
     * @param \Error $e
     */
    public function handleError(\Error $e): void
    {
        // @todo Implement error output and logging
        var_dump($e);
    }

    /**
     * Handles exceptions thrown by application.
     *
     * @param \Exception $e
     */
    public function handleException(\Exception $e): void
    {
        // @todo Implement error output and logging
        var_dump($e);

        if ($e instanceof NotFoundException) {
            (new HtmlResponder(404))->notFound();
        } elseif ($e instanceof MethodNotAllowedException) {

        } elseif ($e instanceof BadRequestException) {

        }
    }
}
