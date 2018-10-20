<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\ResponderInterface;

class HttpResponder implements ResponderInterface
{
    /**
     * HTTP status code to use in response.
     *
     * @var int $statusCode
     */
    protected $statusCode = 200;

    /**
     * HTTP protocol version.
     *
     * @var string $version
     */
    protected $version = '1.1';

    /**
     * List of available HTTP status codes and names.
     *
     * @var array $statusMessages
     */
    protected $statusMessages = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    /**
     * Additional HTTP headers.
     *
     * @var array $headers
     */
    protected $headers = [];

    /**
     * The HTTP body.
     *
     * @var string $body
     */
    protected $body = '';

    /**
     * @param int $statusCode
     * @param string $version
     */
    public function __construct(int $statusCode = 200, string $version = '1.1')
    {
        $this->statusCode = $statusCode;
        $this->version = $version;
    }

    /**
     * Sets HTTP status code.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->statusCode;
    }

    /**
     * Returns HTTP status code.
     *
     * @param int $statusCode
     */
    public function setStatus(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Returns HTTP version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Sets HTTP version.
     *
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * Returns all additional HTTP headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Adds an additional HTTP header.
     *
     * @param string $name
     * @param string $value
     */
    public function addHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Removes an additional HTTP header.
     * @param string $name
     */
    public function removeHeader(string $name)
    {
        unset($this->headers[$name]);
    }

    /**
     * Removes all additional HTTP headers.
     */
    public function clearHeaders()
    {
        $this->headers = [];
    }

    /**
     * Returns the HTTP message body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sets the HTTP message body.
     *
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * Sends and HTTP message/response to the browser.
     */
    public function respond()
    {
        $this->sendHttpHeader();
        $this->sendAdditionalHeaders();
        $this->sendBody();
    }

    /**
     * Sends the HTTP header to the browser.
     */
    protected function sendHttpHeader()
    {
        $header = sprintf(
            'HTTP/%s %d %s',
            $this->version,
            $this->statusCode,
            $this->statusMessages[$this->statusCode]
        );
        header($header, true);
    }

    /**
     * Sends additional HTTP headers to the browser.
     *
     * @return bool
     */
    protected function sendAdditionalHeaders(): bool
    {
        if (empty($this->headers)) {
            return true;
        }
        foreach ($this->headers as $name => $value) {
            header($name .': ' . $value, true);
        }
        return true;
    }

    /**
     * Sends the HTTP message body to the browser.
     *
     * @return bool
     */
    protected function sendBody(): bool
    {
        if (mb_strlen($this->body) === 0) {
            return true;
        }
        echo $this->body;
        return true;
    }
}
