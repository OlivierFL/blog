<?php

namespace App\Model;

class Admin extends User
{
    /** @var string */
    private $description;
    /** @var string */
    private $urlAvatar;
    /** @var string */
    private $altAvatar;
    /** @var string */
    private $urlCV;
    /** @var string */
    private $userId;

    /**
     * Admin constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrlAvatar(): string
    {
        return $this->urlAvatar;
    }

    /**
     * @param string $urlAvatar
     */
    public function setUrlAvatar(string $urlAvatar): void
    {
        $this->urlAvatar = $urlAvatar;
    }

    /**
     * @return string
     */
    public function getAltAvatar(): string
    {
        return $this->altAvatar;
    }

    /**
     * @param string $altAvatar
     */
    public function setAltAvatar(string $altAvatar): void
    {
        $this->altAvatar = $altAvatar;
    }

    /**
     * @return string
     */
    public function getUrlCV(): string
    {
        return $this->urlCV;
    }

    /**
     * @param string $urlCV
     */
    public function setUrlCV(string $urlCV): void
    {
        $this->urlCV = $urlCV;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }
}
