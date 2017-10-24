<?php

namespace Nekudo\ShinyCore\Interfaces;

interface ActionInterface {
    public function __invoke(array $arguments = []);

    public function setDomain(DomainInterface $domain);
}
