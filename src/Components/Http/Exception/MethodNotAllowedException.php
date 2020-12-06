<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Http\Exception;

/**
 * Class MethodNotAllowedException
 *
 * This exception should be thrown in case of a "method not allowed" error (http error 405).
 *
 * @package Bloatless\Endocore\Exception\Http
 */
class MethodNotAllowedException extends HttpException
{

}
