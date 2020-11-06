<?php

namespace App\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    /**
     * @return DatabaseException
     */
    public static function create(): self
    {
        return new self('Erreur lors de la création des données.');
    }

    /**
     * @return DatabaseException
     */
    public static function update(): self
    {
        return new self('Erreur lors de la mise à jour des données.');
    }

    /**
     * @param string $param
     * @param        $expected
     * @param        $value
     *
     * @return DatabaseException
     */
    public static function invalidParameter(string $param, $expected, $value): self
    {
        return new self('Paramètre "'.$param.'" invalide : "'.$value.'". "'.$param.'" devrait être "'.$expected.'".');
    }
}
