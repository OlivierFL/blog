<?php

namespace App\Core\Validation;

use App\Core\Manager;
use Exception;

class ValidatorFactory
{
    public const SIGN_UP_VALIDATOR = 'SignUpValidator';
    public const LOGIN_VALIDATOR = 'LoginValidator';
    public const USER_UPDATE_VALIDATOR = 'UserUpdateValidator';
    public const POST_CREATE_VALIDATOR = 'PostCreateValidator';
    public const POST_UPDATE_VALIDATOR = 'PostUpdateValidator';

    /**
     * @param string       $validatorName
     * @param array        $data
     * @param null|Manager $manager
     *
     * @throws Exception
     *
     * @return Validator
     */
    public static function create(string $validatorName, array $data, Manager $manager = null): Validator
    {
        $validatorName = 'App\\Core\\Validation\\'.$validatorName;

        return (new $validatorName($data, $manager))->getValidator();
    }
}
