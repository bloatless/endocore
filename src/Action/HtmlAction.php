<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Action;

use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Components\Logger\LoggerInterface;
use Bloatless\Endocore\Responder\HtmlResponder;

/**
 * @property HtmlResponder $responder
 */

abstract class HtmlAction extends Action
{
    public function __construct(array $config, LoggerInterface $logger, Request $request)
    {
        parent::__construct($config, $logger, $request);
        $this->setResponder(new HtmlResponder($config));
    }
}
