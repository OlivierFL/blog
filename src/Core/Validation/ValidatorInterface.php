<?php

namespace App\Core\Validation;

interface ValidatorInterface
{
    /**
     * @return $this
     */
    public function getValidator(): self;
}
