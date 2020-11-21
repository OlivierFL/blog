<?php

namespace App\Model;

use App\Core\Entity;

class User extends Entity
{
    /** @var string */
    public const ROLE_USER = 'user';
    /** @var string */
    public const ROLE_ADMIN = 'admin';
    /** @var string */
    protected $userName;
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $lastName;
    /** @var string */
    protected $email;
    /** @var string */
    protected $password;
    /** @var string */
    protected $role;
    /** @var null|int */
    protected ?int $adminId;

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     *
     * @return User
     */
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role ?? ($this->role = self::ROLE_USER);
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return null|int
     */
    public function getAdminId(): ?int
    {
        return $this->adminId;
    }

    /**
     * @param null|int $adminId
     *
     * @return User
     */
    public function setAdminId(?int $adminId): self
    {
        $this->adminId = $adminId;

        return $this;
    }
}
