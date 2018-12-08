<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Action;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Logger\LoggerInterface;
use Nekudo\ShinyCore\Responder\HtmlResponder;

/**
 * @property HtmlResponder $responder
 */

abstract class HtmlAction extends Action
{
    public function __construct(Config $config, LoggerInterface $logger, Request $request)
    {
        parent::__construct($config, $logger, $request);
        $this->setResponder(new HtmlResponder($config));
    }
}
