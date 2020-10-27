<?php

namespace App\Exceptions;

use Exception;

class PostException extends Exception
{
    /**
     * @return static
     */
    public static function create(): self
    {
        return new self('Erreur lors de la création de l\'article.');
    }

    /**
     * @param string $postId
     *
     * @return static
     */
    public static function update(string $postId): self
    {
        return new self('Erreur lors de la suppression de l\'article : '.$postId);
    }

    /**
     * @param string $postId
     *
     * @return static
     */
    public static function delete(string $postId): self
    {
        return new self('Erreur lors de la suppression de l\'article : '.$postId);
    }
}
