<?php

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Exceptions\Http\NotFoundException;

class ExceptionHandler
{
    /**
     * Handles internal php errors.
     *
     * @param \Error $e
     */
    public function handleError(\Error $e)
    {
        // @todo Implement error output and logging
    }

    /**
     * Handles exceptions thrown by application.
     *
     * @param \Exception $e
     */
    public function handleException(\Exception $e)
    {
        // @todo Implement error output and logging
        if ($e instanceof NotFoundException) {
            (new HtmlResponder(404))->notFound();
        }
    }
}
