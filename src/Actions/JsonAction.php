<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Actions;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Responder\JsonResponder;
use Nekudo\ShinyCore\Responder\ResponderInterface;

/**
 * @property JsonResponder $responder
 */

abstract class JsonAction extends BaseAction
{
    public function __construct(Config $config, Request $request)
    {
        parent::__construct($config, $request);
        $this->responder = new JsonResponder($config);
    }

    /**
     * Returns the responder.
     *
     * @return ResponderInterface
     */
    public function getResponder(): ResponderInterface
    {
        return $this->responder;
    }
}
