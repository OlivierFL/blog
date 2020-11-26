<?php

namespace App\Core;

use DateTime;

trait TimestampableEntity
{
    /** @var DateTime|string */
    protected $createdAt;
    /** @var DateTime|string */
    protected $updatedAt;

    /**
     * @return null|string
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    /**
     * @param null|DateTime|string $createdAt
     */
    public function setCreatedAt(?string $createdAt = null): void
    {
        if (null === $createdAt) {
            $this->createdAt = (new \DateTime())->format('Y-m-d H:i:s');

            return;
        }

        $this->createdAt = $createdAt;
    }

    /**
     * @return null|string
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void
    {
        $this->updatedAt = (new \DateTime())->format('Y-m-d H:i:s');
    }
}
