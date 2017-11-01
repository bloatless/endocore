<?php

namespace Nekudo\ShinyCore\Interfaces;

interface ResponderInterface
{
    public function found();

    public function notFound();

    public function error();
}
