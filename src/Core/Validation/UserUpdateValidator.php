<?php

namespace App\Core\Validation;

use Exception;

final class UserUpdateValidator extends Validator implements ValidatorInterface
{
    /**
     * @throws Exception
     *
     * @return $this
     */
    public function getValidator(): self
    {
        foreach ($this->data as $key => $value) {
            if ($value) {
                $this->addValidations($key);
            }
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @throws Exception
     */
    private function addValidations(string $key): void
    {
        if ('user_name' === $key) {
            $this->length('user_name', 3, 255);
        }

        if ('last_name' === $key || 'first_name' === $key) {
            $this->length($key, 1, 255);
        }

        if ('email' === $key) {
            $this->length('email', 5, 255)
                ->email('email')
            ;
        }

        if ('role' === $key) {
            $this->role('role');
        }
    }
}
