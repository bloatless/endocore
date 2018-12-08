<?php

namespace Nekudo\ShinyCore\Tests\Unit\Action;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Domain\DatabaseDomain;
use Nekudo\ShinyCore\Logger\NullLogger;
use PHPUnit\Framework\TestCase;

class DatabaseDomainTest extends TestCase
{
    public $config;

    public $logger;

    public function setUp()
    {
        $config = include SC_TESTS . '/Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->logger = new NullLogger;
    }

    public function testCanBeInitialized()
    {
        $domain = new DatabaseDomain($this->config, $this->logger);
        $this->assertInstanceOf(DatabaseDomain::class, $domain);
    }
}
