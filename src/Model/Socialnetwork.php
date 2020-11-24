<?php

namespace App\Model;

use App\Core\Entity;

class Socialnetwork extends Entity
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $iconName;
    /**
     * @var string
     */
    private string $url;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     *
     * @return Socialnetwork
     */
    public function setName(?string $name): self
    {
        $name ? $this->name = $name : null;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getIconName(): ?string
    {
        return $this->iconName;
    }

    /**
     * @param null|string $name
     *
     * @return Socialnetwork
     */
    public function setIconName(?string $name): self
    {
        $name ? $this->iconName = $name : null;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     *
     * @return Socialnetwork
     */
    public function setUrl(?string $url): self
    {
        $url ? $this->url = $url : null;

        return $this;
    }
}
