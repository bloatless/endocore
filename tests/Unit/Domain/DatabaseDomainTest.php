<?php

namespace Bloatless\Endocore\Tests\Unit\Action;

use Bloatless\Endocore\Domain\DatabaseDomain;
use Bloatless\Endocore\Components\Logger\NullLogger;
use PHPUnit\Framework\TestCase;

class DatabaseDomainTest extends TestCase
{
    public $config;

    public $logger;

    public function setUp(): void
    {
        $this->config = include SC_TESTS . '/Fixtures/config.php';
        $this->logger = new NullLogger;
    }

    public function testCanBeInitialized()
    {
        $domain = new DatabaseDomain($this->config, $this->logger);
        $this->assertInstanceOf(DatabaseDomain::class, $domain);
    }
}
