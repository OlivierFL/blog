<?php

namespace App\Exceptions;

class SocialNetWorkException extends \Exception
{
    /**
     * @return static
     */
    public static function create(): self
    {
        return new self('Erreur lors de la création du réseau social.');
    }

    /**
     * @param $id
     *
     * @return static
     */
    public static function update($id): self
    {
        return new self('Erreur lors de la mise à jour du réseau social : '.$id);
    }

    /**
     * @param $id
     *
     * @return static
     */
    public static function delete($id): self
    {
        return new self('Erreur lors de la suppression du réseau social : '.$id);
    }
}
