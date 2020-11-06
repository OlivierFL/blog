<?php

namespace App\Exceptions;

class CommentException extends \Exception
{
    /**
     * @return static
     */
    public static function create(): self
    {
        return new self('Erreur lors de la création du commentaire.');
    }

    /**
     * @param string $commentId
     *
     * @return static
     */
    public static function update(string $commentId): self
    {
        return new self('Erreur lors de la mise à jour du commentaire : '.$commentId);
    }

    /**
     * @param string $commentId
     *
     * @return static
     */
    public static function invalidStatus(string $commentId): self
    {
        return new self('Erreur lors de la mise à jour du commentaire '.$commentId.' : statut invalide.');
    }
}
