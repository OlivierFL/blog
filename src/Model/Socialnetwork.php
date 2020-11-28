<?php

namespace App\Model;

use App\Core\Entity;

class Socialnetwork extends Entity
{
    /** @var string */
    private string $name;
    /** @var string */
    private string $iconName;
    /** @var string */
    private string $url;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Socialnetwork
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getIconName(): string
    {
        return $this->iconName;
    }

    /**
     * @param string $name
     *
     * @return Socialnetwork
     */
    public function setIconName(string $name): self
    {
        $this->iconName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Socialnetwork
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
