<?php

namespace App\Model;

use App\Core\Entity;
use App\Core\TimestampableEntity;

class Post extends Entity
{
    use TimestampableEntity;

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
    /** @var string */
    private $adminId;

    /**
     * Post constructor.
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getCoverImg(): string
    {
        return $this->coverImg;
    }

    /**
     * @param string $coverImg
     */
    public function setCoverImg(string $coverImg): void
    {
        $this->coverImg = $coverImg;
    }

    /**
     * @return string
     */
    public function getAltCoverImg(): string
    {
        return $this->altCoverImg;
    }

    /**
     * @param string $altCoverImg
     */
    public function setAltCoverImg(string $altCoverImg): void
    {
        $this->altCoverImg = $altCoverImg;
    }

    /**
     * @return string
     */
    public function getAdminId(): string
    {
        return $this->adminId;
    }

    /**
     * @param string $adminId
     */
    public function setAdminId(string $adminId): void
    {
        $this->adminId = $adminId;
    }
}
