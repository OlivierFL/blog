<?php

namespace App\Model;

use App\Core\Entity;
use App\Core\TimestampableEntity;

class Comment extends Entity
{
    use TimestampableEntity;

    /** @var string */
    public const STATUS_PENDING = 'pending';
    /** @var string */
    public const STATUS_APPROVED = 'approved';
    /** @var string */
    public const STATUS_REJECTED = 'rejected';
    /** @var string */
    private $content;
    /** @var string */
    private $status;
    /** @var string */
    private $userId;
    /** @var string */
    private $postId;

    /**
     * Comment constructor.
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
        if (null === $this->status) {
            return $this->status = self::STATUS_PENDING;
        }

        return $this->status;
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
     * @param string $userId
     */
    public function setUserId(string $userId): void
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
     * @param string $postId
     */
    public function setPostId(string $postId): void
    {
        $this->postId = $postId;
    }
}
