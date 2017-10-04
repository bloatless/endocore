<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Environment;
use PHPUnit\Framework\TestCase;

class EnvironmentTest extends TestCase
{
    /**
     * @var Environment $environment
     */
    protected $environment;

    public function setUp()
    {
        $this->environment = new Environment;
    }

    public function testSetRquestMethod()
    {
        $this->environment->setRequestMethod('GET');
        $this->assertEquals('GET', $this->environment->getRequestMethod());
    }

    public function testSetRequestUri()
    {
        $this->environment->setRequestUri('/foo/bar');
        $this->assertEquals('/foo/bar', $this->environment->getRequestUri());
    }
}