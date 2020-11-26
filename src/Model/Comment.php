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
    private string $content;
    /** @var string */
    private string $status;
    /** @var string */
    private string $userId;
    /** @var string */
    private string $postId;

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
     * @return Comment
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
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
     *
     * @return Comment
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

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
     * @return Comment
     */
    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPostId(): string
    {
        return $this->postId;
    }

    /**
     * @param string $postId
     *
     * @return Comment
     */
    public function setPostId(string $postId): self
    {
        $this->postId = $postId;

        return $this;
    }
}
