<?php

namespace App\Model;

use App\Core\Entity;
use App\Managers\PostManager;
use Cocur\Slugify\Slugify;
use Exception;

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
    /** @var string */
    private $userId;

    /**
     * Post constructor.
     *
     * @param null|array $data
     *
     * @throws Exception
     */
    public function __construct(?array $data)
    {
        parent::__construct($data);
        if (null === $this->slug) {
            $this->slug = $this->createSlug($data['title']);
        }
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
     * @param null|string $slug
     *
     * @throws Exception
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return null|string
     */
    public function getCoverImg(): ?string
    {
        return $this->coverImg;
    }

    /**
     * @param null|string $coverImg
     */
    public function setCoverImg(?string $coverImg): void
    {
        $this->coverImg = $coverImg;
    }

    /**
     * @return null|string
     */
    public function getAltCoverImg(): ?string
    {
        return $this->altCoverImg;
    }

    /**
     * @param null|string $altCoverImg
     */
    public function setAltCoverImg(?string $altCoverImg): void
    {
        $this->altCoverImg = $altCoverImg;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @param string $title
     *
     * @throws Exception
     *
     * @return string
     */
    public function createSlug(string $title): string
    {
        $sluggedTitle = (new Slugify())->slugify($title);

        $count = 0;
        while (false === $this->checkSlug($sluggedTitle)) {
            $sluggedTitle = rtrim($sluggedTitle, '-0123456789').'-'.++$count;
        }

        return $sluggedTitle;
    }

    /**
     * @param string $slug
     *
     * @throws Exception
     *
     * @return bool
     */
    private function checkSlug(string $slug): bool
    {
        return (new PostManager())->preventReuse(['slug' => $slug]);
    }
}
