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

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getUrlAvatar(): string
    {
        return $this->urlAvatar;
    }

    public function setUrlAvatar(string $urlAvatar): void
    {
        $this->urlAvatar = $urlAvatar;
    }

    public function getAltAvatar(): string
    {
        return $this->altAvatar;
    }

    public function setAltAvatar(string $altAvatar): void
    {
        $this->altAvatar = $altAvatar;
    }

    public function getUrlCV(): string
    {
        return $this->urlCV;
    }

    public function setUrlCV(string $urlCV): void
    {
        $this->urlCV = $urlCV;
    }
}
