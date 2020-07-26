<?php

namespace App\Model;

use App\Core\Entity;

class Post extends Entity
{
    /** @var string */
    private $title;
    /** @var string */
    private $content;
    /** @var string */
    private $slug;
    /** @var string */
    private $coverImg;
    /** @var string */
    private $altCoverImg;
    /** @var \DateTime */
    private $createdAt;
    /** @var \DateTime */
    private $updatedAt;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCoverImg(): string
    {
        return $this->coverImg;
    }

    public function setCoverImg(string $coverImg): void
    {
        $this->coverImg = $coverImg;
    }

    public function getAltCoverImg(): string
    {
        return $this->altCoverImg;
    }

    public function setAltCoverImg(string $altCoverImg): void
    {
        $this->altCoverImg = $altCoverImg;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
