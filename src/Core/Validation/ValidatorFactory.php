<?php

namespace App\Core\Validation;

use App\Core\Manager;
use Exception;

class ValidatorFactory
{
    public const SIGN_UP = 'sign_up';
    public const LOGIN = 'login';
    public const UPDATE_USER = 'update_user';
    public const POST_CREATE = 'post_create';

    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * @param string       $context
     * @param array        $data
     * @param null|Manager $manager
     *
     * @throws Exception
     *
     * @return Validator
     */
    public static function create(string $context, array $data, Manager $manager = null): Validator
    {
        switch ($context) {
            case self::SIGN_UP:
                return (new SignUpValidator($data, $manager))->getValidator();
            case self::LOGIN:
                return (new LoginValidator($data, $manager))->getValidator();
            case self::UPDATE_USER:
                return (new UserUpdateValidator($data, $manager))->getValidator();
            case self::POST_CREATE:
                return (new PostCreateValidator($data))->getValidator();
            default:
                break;
        }
    }
}
