<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
{
    /**
     * @param $id
     *
     * @return static
     */
    public static function create($id): self
    {
        return new self('Erreur lors de la création de l\'utilisateur : '.$id);
    }

    /**
     * @param $id
     *
     * @return static
     */
    public static function update($id): self
    {
        return new self('Erreur lors de la mise à jour de l\'utilisateur : '.$id);
    }

    /**
     * @param $id
     *
     * @return static
     */
    public static function delete($id): self
    {
        return new self('Erreur lors de la suppression de l\'utilisateur : '.$id);
    }
}
