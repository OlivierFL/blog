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
     *
     * @return Admin
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
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
     *
     * @return Admin
     */
    public function setUrlAvatar(?string $urlAvatar): self
    {
        $this->urlAvatar = $urlAvatar;

        return $this;
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
     *
     * @return Admin
     */
    public function setAltAvatar(?string $altUrlAvatar): self
    {
        $this->altUrlAvatar = $altUrlAvatar;

        return $this;
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
     *
     * @return Admin
     */
    public function setUrlCV(?string $urlCv): self
    {
        $this->urlCv = $urlCv;

        return $this;
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
     *
     * @return Admin
     */
    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
