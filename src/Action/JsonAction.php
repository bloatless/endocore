<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Action;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Logger\LoggerInterface;
use Nekudo\ShinyCore\Responder\JsonResponder;

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
