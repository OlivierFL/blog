<?php

namespace App\Model;

class Admin extends User
{
    /** @var null|string */
    private ?string $description = null;
    /** @var null|string */
    private ?string $urlAvatar = null;
    /** @var null|string */
    private ?string $altUrlAvatar = null;
    /** @var null|string */
    private ?string $urlCv = null;

    /**
     * @return null|string
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
     * @return null|string
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
     * @return null|string
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
    public function setAltUrlAvatar(?string $altUrlAvatar): self
    {
        $this->altUrlAvatar = $altUrlAvatar;

        return $this;
    }

    /**
     * @return null|string
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
}
