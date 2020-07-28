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

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getStatus(): string
    {
        if (null === $this->status) {
            return $this->status = self::STATUS_PENDING;
        }

        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function setPostId(string $postId): void
    {
        $this->postId = $postId;
    }
}
