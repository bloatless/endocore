<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Http;

class Response
{
    /**
     * @var string $protocolVersion
     */
    protected $protocolVersion = '1.1';

    /**
     * @var int $statusCode
     */
    protected $statusCode = 200;

    /**
     * @var array $headers
     */
    protected $headers = [];

    /**
     * @var string $body
     */
    protected $body = '';

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

    public function __construct(int $statusCode = 200, array $headers = [], string $body = '')
    {
        $this->setStatus($statusCode);
        $this->setHeaders($headers);
        $this->setBody($body);
    }

    /**
     * Returns protocol version.
     *
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * Sets protocol version.
     *
     * @param string $version
     * @return void
     */
    public function setProtocolVersion(string $version): void
    {
        $this->protocolVersion = $version;
    }

    /**
     * Returns HTTP status code.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->statusCode;
    }

    /**
     * Sets HTTP status code.
     *
     * @param int $statusCode
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setStatus(int $statusCode): void
    {
        if (!isset($this->statusMessages[$statusCode])) {
            throw new \InvalidArgumentException('Invalid HTTP status code.');
        }
        $this->statusCode = $statusCode;
    }

    /**
     * Returns HTTP status message.
     *
     * @return string
     */
    public function getStatusMessage(): string
    {
        return $this->statusMessages[$this->statusCode];
    }

    /**
     * (Re)Sets all HTTP headers.
     *
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
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
     * @return void
     */
    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Removes an additional HTTP header.
     *
     * @param string $name
     * @return void
     */
    public function removeHeader(string $name): void
    {
        unset($this->headers[$name]);
    }

    /**
     * Removes all additional HTTP headers.
     *
     * @return void
     */
    public function clearHeaders(): void
    {
        $this->headers = [];
    }

    /**
     * Sets HTTP body.
     *
     * @param string $body
     * @return void
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * Returns HTTP body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Converts HTTP response to string.
     *
     * @return string
     */
    public function __toString(): string
    {
        // add http header to output:
        $output = sprintf(
            "HTTP/%s %d %s\r\n",
            $this->getProtocolVersion(),
            $this->getStatus(),
            $this->getStatusMessage()
        );

        // add additional headers to output:
        foreach ($this->headers as $name => $value) {
            $output .= sprintf("%s: %s\r\n", $name, $value);
        }

        // add body to output:
        $output .= $this->getBody();

        return $output;
    }
}
