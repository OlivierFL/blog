<?php

namespace App\Core;

use ReflectionClass;
use ReflectionException;

class Entity
{
    use TimestampableEntity;

    /** @var int */
    private $id;

    /**
     * Entity constructor.
     *
     * @param null|array $data
     */
    public function __construct(?array $data = null)
    {
        if (null !== $data) {
            $this->hydrate($data);
            if (!isset($data['created_at'], $data['updated_at'])) {
                $this->setCreatedAt();
                $this->setUpdatedAt();
            }
        }
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     */
    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $key = $this->snakeCaseToCamelCase($key);
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    /**
     * @throws ReflectionException
     *
     * @return array
     */
    public function getProperties(): array
    {
        $reflectionClass = new ReflectionClass($this);

        return $reflectionClass->getProperties();
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function snakeCaseToCamelCase(string $key): string
    {
        return lcfirst(str_replace('_', '', ucwords($key, '_')));
    }
}
