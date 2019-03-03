<?php

namespace Bloatless\Endocore\Tests\Unit\Responder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Responder\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class PhtmlRendererTest extends TestCase
{
    public $config;

    public function setUp(): void
    {
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($configData);
    }

    public function testGetSetLayout()
    {
        $renderer = new PhtmlRenderer($this->config);
        $renderer->setLayout('mock_layout');
        $this->assertEquals('mock_layout', $renderer->getLayout());
    }

    public function testGetSetView()
    {
        $renderer = new PhtmlRenderer($this->config);
        $renderer->setView('mock');
        $this->assertEquals('mock', $renderer->getView());
    }
}
