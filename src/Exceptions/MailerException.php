<?php

namespace App\Exceptions;

use Exception;

class MailerException extends Exception
{
    /**
     * @param string $error
     *
     * @return MailerException
     */
    public static function send(string $error): self
    {
        return new self('Erreur lors de l\'envoi de l\'email : '.$error);
    }
}
