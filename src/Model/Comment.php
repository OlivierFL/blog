<?php

namespace App\Model;

use App\Core\Entity;

class Comment extends Entity
{
    /** @var string */
    public const STATUS_PENDING = 'En attente de modération';
    /** @var string */
    public const STATUS_APPROVED = 'Approuvé';
    /** @var string */
    public const STATUS_REJECTED = 'Non validé';
    /** @var string */
    private $content;
    /** @var string */
    private $status;
    /** @var string */
    private $userId;
    /** @var string */
    private $postId;

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
    public function getStatus(): string
    {
        return $this->status ?? ($this->status = self::STATUS_PENDING);
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param null|string $userId
     */
    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getPostId(): string
    {
        return $this->postId;
    }

    /**
     * @param null|string $postId
     */
    public function setPostId(?string $postId): void
    {
        $this->postId = $postId;
    }
}
