<?php

namespace App\Core\Validation;

final class LoginValidator extends Validator implements ValidatorInterface
{
    /**
     * @return $this
     */
    public function getValidator(): self
    {
        foreach ($this->data as $key => $value) {
            $this->required($key)
                ->notBlank($key)
                ;
        }

        return $this;
    }
}
