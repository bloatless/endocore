<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Action;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Logger\LoggerInterface;
use Bloatless\Endocore\Responder\JsonResponder;

/**
 * @property JsonResponder $responder
 */

abstract class JsonAction extends Action
{
    public function __construct(Config $config, LoggerInterface $logger, Request $request)
    {
        parent::__construct($config, $logger, $request);
        $this->setResponder(new JsonResponder($config));
    }
}
