<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Exceptions\Http;

/**
 * Class MethodNotAllowedException
 *
 * This exception should be thrown in case of a "method not allowed" error (http error 405).
 *
 * @package Nekudo\ShinyCore\Exceptions\Http
 */
class MethodNotAllowedException extends HttpException
{

}
