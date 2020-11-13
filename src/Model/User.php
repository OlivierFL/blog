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
    /** @var null|int */
    private ?int $adminId;

    /**
     * User constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
        if (!isset($data['created_at'], $data['updated_at'])) {
            $this->setCreatedAt();
            $this->setUpdatedAt();
        }
    }

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
