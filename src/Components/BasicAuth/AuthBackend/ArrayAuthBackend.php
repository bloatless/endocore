<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\BasicAuth\AuthBackend;

class ArrayAuthBackend extends AuthBackend
{
    /**
     * Valid/known users.
     * Username as array key and password-hash as value.
     *
     * @var array $users
     */
    protected $users = [];

    public function __construct(array $users = [])
    {
        $this->setUsers($users);
    }

    /**
     * Sets valid/known users.
     *
     * @param array $users
     */
    public function setUsers(array $users): void
    {
        $this->users = $users;
    }

    /**
     * Returns valid/known users.
     *
     * @return array
     */
    public function getUsers(): array
    {
        return $this->users;
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
        if (empty($this->users)) {
            return false;
        }

        if (!isset($this->users[$username])) {
            return false;
        }

        $knowHash = $this->users[$username];

        return password_verify($password, $knowHash);
    }
}
