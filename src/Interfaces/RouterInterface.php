<?php

namespace Nekudo\ShinyCore\Interfaces;

interface RouterInterface
{
    public function dispatch() : array;
}