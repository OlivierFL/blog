<?php

namespace App\Core;

use DateTime;

trait TimestampableEntity
{
    /** @var DateTime */
    private $createdAt;
    /** @var DateTime */
    private $updatedAt;

    /**
     * @return null|string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|string $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|string $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
