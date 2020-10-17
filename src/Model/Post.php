<?php

namespace App\Model;

use App\Core\Entity;
use App\Core\TimestampableEntity;
use App\Managers\PostManager;
use Cocur\Slugify\Slugify;
use Exception;

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
     *
     * @throws Exception
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
        $this->setSlug();
        if (!isset($data['created_at'], $data['updated_at'])) {
            $this->setCreatedAt();
            $this->setUpdatedAt();
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
     * @throws Exception
     */
    public function setSlug(): void
    {
        $this->slug = $this->createSlug($this->title);
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

    /**
     * @param string $title
     *
     * @throws Exception
     *
     * @return string
     */
    private function createSlug(string $title): string
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
