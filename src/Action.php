<?php

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\ActionInterface;
use Nekudo\ShinyCore\Interfaces\DomainInterface;
use Nekudo\ShinyCore\Interfaces\ResponderInterface;

abstract class Action implements ActionInterface
{
    /**
     * @var array $config
     */
    protected $config;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @inheritDoc
     */
    protected $domain;


    /**
     * @inheritdoc
     */
    protected $responder;

    public function __construct(array $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    public function setDomain(DomainInterface $domain)
    {
        $this->domain = $domain;
    }

    public function setResponder(ResponderInterface $responder)
    {
        $this->responder = $responder;
    }
}
