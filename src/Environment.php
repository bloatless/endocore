<?php

namespace Nekudo\ShinyCore;

class Environment
{
    /**
     * @var string $requestMethod
     */
    protected $requestMethod;

    /**
     * @var string $requestUri
     */
    protected $requestUri;

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->requestUri = $_SERVER['REQUEST_URI'] ?? '';
    }

    /**
     * @return string
     */
    public function getRequestMethod() : string
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestMethod
     */
    public function setRequestMethod(string $requestMethod)
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return string
     */
    public function getRequestUri() : string
    {
        return $this->requestUri;
    }

    /**
     * @param string $requestUri
     */
    public function setRequestUri(string $requestUri)
    {
        $this->requestUri = $requestUri;
    }
}
