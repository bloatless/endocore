<?php

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\ActionInterface;

abstract class Action implements ActionInterface
{
    /**
     * @var Request $request
     */
    protected $request;

    protected $domain;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
    }
}
