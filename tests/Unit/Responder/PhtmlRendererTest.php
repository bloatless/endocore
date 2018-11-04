<?php

namespace Nekudo\ShinyCore\Tests\Unit\Responder;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Responder\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class PhtmlRendererTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $configData = include __DIR__ . '/../../Mocks/config.php';
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
