<?php

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\ActionInterface;
use Nekudo\ShinyCore\Interfaces\DomainInterface;

abstract class Action implements ActionInterface
{
    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @inheritDoc
     */
    protected $domain;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setDomain(DomainInterface $domain)
    {
        $this->domain = $domain;
    }
}
