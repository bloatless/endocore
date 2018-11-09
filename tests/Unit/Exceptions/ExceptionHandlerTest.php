<?php

namespace Nekudo\ShinyCore\Tests\Unit\Exception;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exception\Application\ShinyCoreException;
use Nekudo\ShinyCore\Exception\ExceptionHandler;
use Nekudo\ShinyCore\Exception\Http\BadRequestException;
use Nekudo\ShinyCore\Exception\Http\MethodNotAllowedException;
use Nekudo\ShinyCore\Exception\Http\NotFoundException;
use Nekudo\ShinyCore\Logger\NullLogger;
use Nekudo\ShinyCore\Http\Request;
use PHPUnit\Framework\TestCase;

class ExceptionHandlerTest extends TestCase
{
    /** @var Config $config */
    public $config;

    /** @var NullLogger $logger */
    public $logger;

    /** @var ExceptionHandler $handler */
    public $handler;

    public function setUp()
    {
        $configData = include __DIR__ . '/../../Mocks/config.php';
        $this->config = (new Config)->fromArray($configData);
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
        $this->assertContains('ExceptionHandlerTest.php', $response->getBody());
    }

    public function testHandlesBadRequestException()
    {
        $error = new BadRequestException('bad request');
        $response = $this->handler->handleException($error);
        $this->assertEquals(400, $response->getStatus());
        $this->assertContains('<title>400 Bad Request</title>', $response->getBody());
    }

    public function testHandlesNotFoundException()
    {
        $error = new NotFoundException('not found');
        $response = $this->handler->handleException($error);
        $this->assertEquals(404, $response->getStatus());
        $this->assertContains('<title>404 Not found</title>', $response->getBody());
    }

    public function testHandlesMethodNotAllowedException()
    {
        $error = new MethodNotAllowedException('method not allowed');
        $response = $this->handler->handleException($error);
        $this->assertEquals(405, $response->getStatus());
        $this->assertContains('<title>405 Method not allowed</title>', $response->getBody());
    }

    public function testHandlesGeneralException()
    {
        $error = new ShinyCoreException('foobar error');
        $response = $this->handler->handleException($error);
        $this->assertEquals(500, $response->getStatus());
        $this->assertContains('<title>Error 500</title>', $response->getBody());
    }

    public function testRespondsWithJson()
    {
        $request = new Request([], [], ['HTTP_ACCEPT' => 'application/json']);
        $handler = new ExceptionHandler($this->config, $this->logger, $request);
        $error = new ShinyCoreException('json error');
        $response = $handler->handleException($error);
        $this->assertContains('json error', $response->getBody());
        $bodyDecoded = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('errors', $bodyDecoded);
    }
}
