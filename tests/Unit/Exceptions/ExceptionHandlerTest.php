<?php

namespace Bloatless\Endocore\Tests\Unit\Exception;

use Bloatless\Endocore\Exception\Application\EndocoreException;
use Bloatless\Endocore\Exception\ExceptionHandler;
use Bloatless\Endocore\Exception\Http\BadRequestException;
use Bloatless\Endocore\Exception\Http\MethodNotAllowedException;
use Bloatless\Endocore\Exception\Http\NotFoundException;
use Bloatless\Endocore\Components\Logger\NullLogger;
use Bloatless\Endocore\Http\Request;
use PHPUnit\Framework\TestCase;

class ExceptionHandlerTest extends TestCase
{
    /** @var array $config */
    public $config;

    /** @var NullLogger $logger */
    public $logger;

    /** @var ExceptionHandler $handler */
    public $handler;

    public function setUp(): void
    {
        $this->config = include SC_TESTS . '/Fixtures/config.php';
        $this->logger = new NullLogger;
        $request = new Request;
        $this->handler = new ExceptionHandler($this->config, $this->logger, $request);
    }

    public function testCanBeInitialized()
    {
        $this->assertInstanceOf(ExceptionHandler::class, $this->handler);
    }

    public function testHandlesInternalError()
    {
        $error = new \Error('Test', 42);
        $response = $this->handler->handleError($error);
        $this->assertEquals(500, $response->getStatus());
        $this->assertStringContainsString('ExceptionHandlerTest.php', $response->getBody());
    }

    public function testHandlesBadRequestException()
    {
        $error = new BadRequestException('bad request');
        $response = $this->handler->handleException($error);
        $this->assertEquals(400, $response->getStatus());
        $this->assertStringContainsString('<title>400 Bad Request</title>', $response->getBody());
    }

    public function testHandlesNotFoundException()
    {
        $error = new NotFoundException('not found');
        $response = $this->handler->handleException($error);
        $this->assertEquals(404, $response->getStatus());
        $this->assertStringContainsString('<title>404 Not found</title>', $response->getBody());
    }

    public function testHandlesMethodNotAllowedException()
    {
        $error = new MethodNotAllowedException('method not allowed');
        $response = $this->handler->handleException($error);
        $this->assertEquals(405, $response->getStatus());
        $this->assertStringContainsString('<title>405 Method not allowed</title>', $response->getBody());
    }

    public function testHandlesGeneralException()
    {
        $error = new EndocoreException('foobar error');
        $response = $this->handler->handleException($error);
        $this->assertEquals(500, $response->getStatus());
        $this->assertStringContainsString('<title>Error 500</title>', $response->getBody());
    }

    public function testRespondsWithJson()
    {
        $request = new Request([], [], ['HTTP_ACCEPT' => 'application/json']);
        $handler = new ExceptionHandler($this->config, $this->logger, $request);
        $error = new EndocoreException('json error');
        $response = $handler->handleException($error);
        $this->assertStringContainsString('json error', $response->getBody());
        $bodyDecoded = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('errors', $bodyDecoded);
    }
}
