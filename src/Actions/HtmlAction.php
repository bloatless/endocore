<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Actions;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Responder\HtmlResponder;

/**
 * @property HtmlResponder $responder
 */

abstract class HtmlAction extends Action
{
    public function __construct(Config $config, Request $request)
    {
        parent::__construct($config, $request);
        $this->setResponder(new HtmlResponder($config));
    }
}
