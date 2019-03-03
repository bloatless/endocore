<?php

namespace Bloatless\Endocore\Tests\Unit\Action;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Domain\DatabaseDomain;
use Bloatless\Endocore\Logger\NullLogger;
use PHPUnit\Framework\TestCase;

class DatabaseDomainTest extends TestCase
{
    public $config;

    public $logger;

    public function setUp()
    {
        $config = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->logger = new NullLogger;
    }

    public function testCanBeInitialized()
    {
        $domain = new DatabaseDomain($this->config, $this->logger);
        $this->assertInstanceOf(DatabaseDomain::class, $domain);
    }
}
