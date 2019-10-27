<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Http;

class Request
{
    /**
     * @var array $get
     */
    protected $get;

    /**
     * @var array $post
     */
    protected $post;

    /**
     * @var array $server
     */
    protected $server;


    public function __construct(array $get = [], array $post = [], array $server = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }

    /**
     * Returns given HTTP request method.
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return (string) $this->getServerParam('REQUEST_METHOD', '');
    }

    /**
     * Returns given HTTP request URI.
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return (string) $this->getServerParam('REQUEST_URI', '');
    }

    /**
     * Returns requested content type.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return (string) $this->getServerParam('CONTENT_TYPE', '');
    }

    /**
     * Returns server params.
     *
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->server;
    }

    /**
     * Returns a server parameter.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getServerParam(string $key, $default = null)
    {
        return isset($this->server[$key]) ? $this->server[$key] : $default;
    }

    /**
     * Fetches request parameter from POST or GET data (in that order).
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function getParam(string $key, $default = null)
    {
        if (isset($this->post[$key])) {
            return $this->post[$key];
        }
        if (isset($this->get[$key])) {
            return $this->get[$key];
        }
        return $default;
    }

    /**
     * Returns the raw request body.
     *
     * @return string
     */
    public function getRawBody(): string
    {
        $body = file_get_contents('php://input');
        if ($body === false) {
            return '';
        }
        return $body;
    }
}
