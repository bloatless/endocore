<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Responder\HtmlResponder;
use Nekudo\ShinyCore\Responder\ResponderInterface;

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
