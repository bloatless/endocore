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
