<?php

namespace Bloatless\Endocore\Tests\Unit\Core\ErrorHandler;

use Bloatless\Endocore\Core\ErrorHandler\ErrorHandler;
use Bloatless\Endocore\Core\Http\Exception\NotFoundException;
use Bloatless\Endocore\Core\Logger\NullLogger;
use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{
    public function testHandleError()
    {
        $logger = new NullLogger();
        $errorHandler = new ErrorHandler($logger);

        $this->assertTrue(
            $errorHandler->handleError(E_USER_DEPRECATED, 'foo', 'bar.php', 12)
        );

        $this->assertTrue(
            $errorHandler->handleError(E_USER_NOTICE, 'foo', 'bar.php', 12)
        );

        $this->assertTrue(
            $errorHandler->handleError(E_USER_WARNING, 'foo', 'bar.php', 12)
        );

        $this->expectException(\ErrorException::class);
        $errorHandler->handleError(E_USER_ERROR, 'foo', 'bar.php', 12);

    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHandleException()
    {
        $logger = new NullLogger();
        $errorHandler = new ErrorHandler($logger);
        $notFound = new NotFoundException('test');
        $this->expectOutputRegex('/.*not found.*/i');
        $errorHandler->handleException($notFound);
    }
}
