<?php

namespace App\Core\Validation;

use App\Core\Manager;

class ValidatorFactory
{
    public const SIGN_UP = 'sign_up';
    public const UPDATE_USER = 'update_user';

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
            case self::SIGN_UP:
                return (new SignUpValidator($data, $manager))->getValidator();
            case self::UPDATE_USER:
                return (new UserUpdateValidator($data, $manager))->getValidator();
            default:
                break;
        }
    }
}
