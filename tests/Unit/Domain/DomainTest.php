<?php

namespace Bloatless\Endocore\Tests\Unit\Domain;

use Bloatless\Endocore\Components\Logger\NullLogger;
use Bloatless\Endocore\Tests\Fixtures\DummyDomain;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    public $config;

    public $logger;

    public function setUp(): void
    {
        $this->config = include SC_TESTS . '/Fixtures/config.php';
        $this->logger = new NullLogger;
    }

    public function testDomainCanBeInitialized()
    {
        $domain = new DummyDomain($this->config, $this->logger);
        $this->assertInstanceOf('Bloatless\Endocore\Domain\Domain', $domain);
    }
}
