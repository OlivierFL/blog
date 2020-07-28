<?php

namespace App\Core;

class Entity
{
    /** @var int */
    private $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $key = $this->formatSnakeCaseToCamelCase($key);
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }
    }

    private function formatSnakeCaseToCamelCase($key): string
    {
        return lcfirst(str_replace('_', '', ucwords($key, '_')));
    }
}
