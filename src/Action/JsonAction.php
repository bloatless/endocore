<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Action;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Responder\JsonResponder;

/**
 * @property JsonResponder $responder
 */

abstract class JsonAction extends Action
{
    public function __construct(Config $config, Request $request)
    {
        parent::__construct($config, $request);
        $this->setResponder(new JsonResponder($config));
    }
}
