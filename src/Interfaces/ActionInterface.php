<?php

namespace Nekudo\ShinyCore\Interfaces;

interface ActionInterface
{
    public function __invoke(array $arguments = []);

    public function setDomain(DomainInterface $domain);

    public function setResponder(ResponderInterface $responder);

    public function getResponder() : ResponderInterface;
}
