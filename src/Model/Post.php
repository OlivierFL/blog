<?php

namespace App\Model;

use App\Core\Entity;
use App\Managers\PostManager;
use Cocur\Slugify\Slugify;
use Exception;

class Post extends Entity
{
    /** @var string */
    private string $title;
    /** @var string */
    private string $content;
    /** @var null|string */
    private ?string $slug = null;
    /** @var null|string */
    private ?string $coverImg = null;
    /** @var string */
    private string $altCoverImg;
    /** @var string */
    private string $userId;

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
     *
     * @return Post
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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
     *
     * @return Post
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param null|string $slug
     *
     * @return Post
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
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
     *
     * @return Post
     */
    public function setCoverImg(?string $coverImg): self
    {
        $this->coverImg = $coverImg;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAltCoverImg(): ?string
    {
        return $this->altCoverImg;
    }

    /**
     * @param string $altCoverImg
     *
     * @return Post
     */
    public function setAltCoverImg(string $altCoverImg): self
    {
        $this->altCoverImg = $altCoverImg;

        return $this;
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
     *
     * @return Post
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
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
