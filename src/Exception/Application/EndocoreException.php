<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Exception\Application;

/**
 * This exception is thrown in case Endocore application needs to abort processing and can handle the error within
 * the application itself.
 */
class EndocoreException extends \Exception
{

}
