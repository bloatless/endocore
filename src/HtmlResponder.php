<?php

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\ResponderInterface;

class HtmlResponder extends HttpResponder implements ResponderInterface
{
    public function __invoke()
    {

    }

    public function found()
    {
        $this->respond();
    }

    public function notFound()
    {
        $this->setStatus(404);
        $this->setBody('<html><head><title>404 Not found</title></head><body>404 Not found</body></html>');
        $this->respond();
    }

    public function error()
    {
        $this->setStatus(500);
        $this->respond();
    }
}
