<?php

namespace App\Core\Validation;

use App\Core\Manager;

class ValidatorFactory
{
    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * @param string       $context
     * @param array        $data
     * @param null|Manager $manager
     *
     * @throws \Exception
     *
     * @return Validator
     */
    public static function create(string $context, array $data, Manager $manager = null): Validator
    {
        switch ($context) {
            case 'sign_up':
                return (new SignUpValidator($data, $manager))->getValidator();
            case 'user_edit':
                return (new UserEditValidator($data, $manager))->getValidator();
            default:
                break;
        }
    }
}
