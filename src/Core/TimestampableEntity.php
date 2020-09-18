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
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|string $createdAt
     *
     * @return TimestampableEntity
     */
    public function setCreatedAt($createdAt): TimestampableEntity
    {
        $this->createdAt = $createdAt;

        return $this;
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
     *
     * @return TimestampableEntity
     */
    public function setUpdatedAt($updatedAt): TimestampableEntity
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
