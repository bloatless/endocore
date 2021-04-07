<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\BasicAuth;

use Bloatless\Endocore\Components\BasicAuth\AuthBackend\AuthBackendInterface;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Http\Response;

class BasicAuth
{
    /**
     * @var AuthBackendInterface $authBackend
     */
    protected $authBackend;

    public function __construct(AuthBackendInterface $authBackend)
    {
        $this->authBackend = $authBackend;
    }

    /**
     * Checks if given request is authenticated.
     *
     * @param Request $request
     * @return bool
     */
    public function isAuthenticated(Request $request): bool
    {
        $credentials = $this->getCredentialsFromRequest($request);
        if (empty($credentials['username']) || empty($credentials['password'])) {
            return false;
        }

        return $this->validateCredentials($credentials['username'], $credentials['password']);
    }

    /**
     * Checks if given credentials are valid.
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function validateCredentials(string $username, string $password): bool
    {
        return $this->authBackend->validateCredentials($username, $password);
    }

    /**
     * Returns a response requesting authentication.
     *
     * @return Response
     */
    public function requestAuthorization(): Response
    {
        return new Response(401, [
            'WWW-Authenticate' => 'Basic realm="Restricted access"',
        ]);
    }

    /**
     * Fetches username from http-authorization header.
     *
     * @param Request $request
     * @return string
     */
    public function getUsernameFromRequest(Request $request): string
    {
        $credentials = $this->getCredentialsFromRequest($request);

        return $credentials['username'] ?? '';
    }

    /**
     * Parses authorization header and returns credentials.
     *
     * @param Request $request
     * @return array
     */
    protected function getCredentialsFromRequest(Request $request): array
    {
        $credentials = [
            'username' => null,
            'password' => null,
        ];

        // Check if authentication header is present
        $authHeader = $request->getServerParam('HTTP_AUTHORIZATION');
        if (empty($authHeader)) {
            return $credentials;
        }

        // Check if authentication header is valid
        $authHeaderParts = explode(' ', $authHeader);
        if ($authHeaderParts[0] !== 'Basic') {
            return $credentials;
        }

        // Collect and return credentials
        $userPass = base64_decode($authHeaderParts[1]);
        if (strpos($userPass, ':') === false) {
            return $credentials;
        }
        $colonPos = strpos($userPass, ':');
        $credentials['username'] = trim(substr($userPass, 0, $colonPos));
        $credentials['password'] = trim(substr($userPass, $colonPos + 1));

        return $credentials;
    }
}
