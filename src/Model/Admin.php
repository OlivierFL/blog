<?php

namespace App\Model;

class Admin extends User
{
    /** @var string */
    private $description;
    /** @var string */
    private $urlAvatar;
    /** @var string */
    private $altUrlAvatar;
    /** @var string */
    private $urlCv;
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrlAvatar(): ?string
    {
        return $this->urlAvatar;
    }

    /**
     * @param null|string $urlAvatar
     */
    public function setUrlAvatar(?string $urlAvatar): void
    {
        $this->urlAvatar = $urlAvatar;
    }

    /**
     * @return string
     */
    public function getAltUrlAvatar(): ?string
    {
        return $this->altUrlAvatar;
    }

    /**
     * @param null|string $altUrlAvatar
     */
    public function setAltAvatar(?string $altUrlAvatar): void
    {
        $this->altUrlAvatar = $altUrlAvatar;
    }

    /**
     * @return string
     */
    public function getUrlCv(): ?string
    {
        return $this->urlCv;
    }

    /**
     * @param null|string $urlCv
     */
    public function setUrlCV(?string $urlCv): void
    {
        $this->urlCv = $urlCv;
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
