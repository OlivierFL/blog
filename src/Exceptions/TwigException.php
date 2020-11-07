<?php

namespace App\Exceptions;

use Exception;

class TwigException extends Exception
{
    public const LOADER_ERROR_MESSAGE = 'Erreur lors du chargement du template : ';
    public const SYNTAX_ERROR_MESSAGE = 'Erreur lors de la compilation du template : ';
    public const RUNTIME_ERROR_MESSAGE = 'Erreur lors du rendu du template : ';
}
