<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Responder\JsonResponder;
use Nekudo\ShinyCore\Responder\ResponderInterface;

abstract class JsonAction extends BaseAction
{
    public function __construct(Config $config, Request $request)
    {
        parent::__construct($config, $request);
        $this->responder = new JsonResponder;
    }

    public function getResponder(): ResponderInterface
    {
        return $this->responder;
    }
}
