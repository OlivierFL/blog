<?php

namespace App\Core\Validation;

final class SignUpValidator extends Validator implements ValidatorInterface
{
    /**
     * @throws \Exception
     *
     * @return $this
     */
    public function getValidator(): self
    {
        foreach ($this->data as $key => $value) {
            $this->required($key)
                ->notBlank($key)
            ;
        }

        return $this->length('email', 5, 255)
            ->length('first_name', 1, 255)
            ->length('last_name', 1, 255)
            ->length('user_name', 3, 255)
            ->length('password', 8)
            ->email('email')
            ->password('password')
            ->unique('user_name')
            ->unique('email')
        ;
    }
}
