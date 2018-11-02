<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Actions;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Responder\HtmlResponder;
use Nekudo\ShinyCore\Responder\ResponderInterface;

/**
 * @property HtmlResponder $responder
 */

abstract class HtmlAction extends BaseAction
{
    public function __construct(Config $config, Request $request)
    {
        parent::__construct($config, $request);
        $this->setResponder(new HtmlResponder($config));
    }

    public function getResponder(): ResponderInterface
    {
        return $this->responder;
    }
}
