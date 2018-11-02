<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Actions;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Responder\ResponderInterface;

abstract class BaseAction implements ActionInterface
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var ResponderInterface $responder
     */
    protected $responder;

    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Sets a responder.
     *
     * @param ResponderInterface $responder
     * @return ResponderInterface
     */
    public function setResponder(ResponderInterface $responder)
    {
        return $this->responder = $responder;
    }
}
