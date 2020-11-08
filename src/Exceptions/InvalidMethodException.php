<?php

namespace App\Exceptions;

use Exception;

class InvalidMethodException extends Exception
{
    /**
     * @param string $invalidMethod
     *
     * @return InvalidMethodException
     */
    public static function methodNotAllowed(string $invalidMethod): self
    {
        return new self('Méthode non autorisée : '.$invalidMethod);
    }
}
