<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\ActionInterface;
use Nekudo\ShinyCore\Interfaces\ResponderInterface;

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
     * @inheritdoc
     */
    protected $responder;

    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    public function setResponder(ResponderInterface $responder)
    {
        return $this->responder = $responder;
    }
}
