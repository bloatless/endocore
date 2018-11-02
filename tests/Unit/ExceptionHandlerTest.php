<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exceptions\Application\ShinyCoreException;
use Nekudo\ShinyCore\Exceptions\ExceptionHandler;
use Nekudo\ShinyCore\Exceptions\Http\BadRequestException;
use Nekudo\ShinyCore\Exceptions\Http\MethodNotAllowedException;
use Nekudo\ShinyCore\Exceptions\Http\NotFoundException;
use Nekudo\ShinyCore\Logger\NullLogger;
use Nekudo\ShinyCore\Request;
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
        $configData = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($configData);
        $this->logger = new NullLogger;
        $request = new Request;
        $this->handler = new ExceptionHandler($this->config, $this->logger, $request);
    }

    public function testCanBeInitialized()
    {
        $this->assertInstanceOf(ExceptionHandler::class, $this->handler);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHandlesInternalError()
    {
        $error = new \Error('Test', 42);
        $this->handler->handleError($error);
        $this->expectOutputRegex('/<title>Error 500<\/title>/');
        $this->expectOutputRegex('/ExceptionHandlerTest\.php/');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHandlesBadRequestException()
    {
        $error = new BadRequestException('bad request');
        $this->handler->handleException($error);
        $this->expectOutputRegex('/<title>400 Bad Request<\/title>/');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHandlesNotFoundException()
    {
        $error = new NotFoundException('not found');
        $this->handler->handleException($error);
        $this->expectOutputRegex('/<title>404 Not found<\/title>/');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHandlesMethodNotAllowedException()
    {
        $error = new MethodNotAllowedException('method not allowed');
        $this->handler->handleException($error);
        $this->expectOutputRegex('/<title>405 Method not allowed<\/title>/');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHandlesGeneralException()
    {
        $error = new ShinyCoreException('foobar error');
        $this->handler->handleException($error);
        $this->expectOutputRegex('/<title>Error 500<\/title>/');
        $this->expectOutputRegex('/foobar error/');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRespondsWithJson()
    {
        $request = new Request([], [], ['CONTENT_TYPE' => 'application/json']);
        $handler = new ExceptionHandler($this->config, $this->logger, $request);
        $error = new ShinyCoreException('json error');
        $handler->handleException($error);
        $this->expectOutputRegex('/json error/');
        $output = $this->getActualOutput();
        $outputDecoded = json_decode($output, true);
        $this->assertArrayHasKey('errors', $outputDecoded);
    }
}
