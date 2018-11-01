<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

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
     * @return string
     */
    public function getRequestMethod() : string
    {
        return $this->server['REQUEST_METHOD'] ?? '';
    }

    /**
     * @return string
     */
    public function getRequestUri() : string
    {
        return $this->server['REQUEST_URI'] ?? '';
    }

    public function getContentType(): string
    {
        return $this->server['CONTENT_TYPE'] ?? '';
    }
}
