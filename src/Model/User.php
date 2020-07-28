<?php

namespace App\Model;

use App\Core\Entity;
use App\Core\TimestampableEntity;

class User extends Entity
{
    use TimestampableEntity;

    /** @var string */
    public const ROLE_USER = 'user';
    /** @var string */
    public const ROLE_ADMIN = 'admin';
    /** @var string */
    private $userName;
    /** @var string */
    private $firstName;
    /** @var string */
    private $lastName;
    /** @var string */
    private $email;
    /** @var string */
    private $password;
    /** @var string */
    private $role;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        if (null === $this->role) {
            return $this->role = self::ROLE_USER;
        }

        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
