<?php

namespace App\Core\Validation;

final class UserEditValidator extends Validator implements ValidatorInterface
{
    /**
     * @throws \Exception
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
     * @param int $key
     *
     * @throws \Exception
     */
    private function addValidations(int $key): void
    {
        switch ($key) {
            case 'user_name':
                $this->length('user_name', 3)
                    ->unique('user_name')
                ;

                break;
            case 'last_name':
            case 'first_name':
                $this->length($key, 1);

                break;
            case 'email':
                $this->length('email', 5)
                    ->email('email')
                    ->unique('email')
                ;

                break;
            case 'role':
                $this->role('role');

                break;
            default:
                break;
        }
    }
}
