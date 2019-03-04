<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Domain;

use Bloatless\Endocore\Components\Database\Factory as DatabaseFactory;
use Bloatless\Endocore\Components\Logger\LoggerInterface;

class DatabaseDomain extends Domain
{
    /**
     * @var DatabaseFactory $db
     */
    protected $db;

    public function __construct(array $config, LoggerInterface $logger)
    {
        parent::__construct($config, $logger);
        $this->db = new DatabaseFactory($config['db']);
    }
}
