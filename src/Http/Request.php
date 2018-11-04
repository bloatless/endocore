<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Http;

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
    public function getRequestMethod() : string
    {
        return $this->server['REQUEST_METHOD'] ?? '';
    }

    /**
     * Returns given HTTP request URI.
     *
     * @return string
     */
    public function getRequestUri() : string
    {
        return $this->server['REQUEST_URI'] ?? '';
    }

    /**
     * Returns requested content type.
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->server['CONTENT_TYPE'] ?? '';
    }
}
