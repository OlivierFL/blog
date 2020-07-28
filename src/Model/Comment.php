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
}
