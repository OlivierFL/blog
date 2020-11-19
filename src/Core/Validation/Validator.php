<?php

namespace App\Core\Validation;

use App\Core\Manager;
use Exception;

class Validator
{
    /**
     * @var ValidatorConstraints
     */
    private ValidatorConstraints $validator;
    private array $data;

    /**
     * Validator constructor.
     *
     * @param              $data
     * @param null|Manager $manager
     */
    public function __construct($data, Manager $manager = null)
    {
        $this->data = $data;
        $this->validator = new ValidatorConstraints($this->data, $manager);
    }

    /**
     * @return ValidatorConstraints
     */
    public function getBaseValidator(): ValidatorConstraints
    {
        $this->addBaseValidation();

        return $this->validator;
    }

    /**
     *@throws Exception
     *
     *@return ValidatorConstraints
     */
    public function getSignUpValidator(): ValidatorConstraints
    {
        $this->addBaseValidation();
        $this->addSignUpValidation();

        return $this->validator;
    }

    /**
     * @throws Exception
     *
     * @return ValidatorConstraints
     */
    public function getUserUpdateValidator(): ValidatorConstraints
    {
        $this->addUserUpdateValidation();

        return $this->validator;
    }

    /**
     * @return ValidatorConstraints
     */
    public function getPostUpdateValidator(): ValidatorConstraints
    {
        foreach ($this->data as $key => $value) {
            if (!\in_array($key, ['cover_img', 'alt_cover_img'], true)) {
                $this->validator->required($key)
                    ->notBlank($key)
                ;
            }
        }

        return $this->validator;
    }

    /**
     * @throws Exception
     *
     * @return ValidatorConstraints
     */
    public function getContactValidator(): ValidatorConstraints
    {
        $this->addBaseValidation();
        $this->addContactValidation();

        return $this->validator;
    }

    private function addBaseValidation(): void
    {
        foreach ($this->data as $key => $value) {
            $this->validator->required($key)
                ->notBlank($key)
            ;
        }
    }

    /**
     * @throws Exception
     */
    private function addSignUpValidation(): void
    {
        $this->validator->length('email', 5, 255)
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

    /**
     * @throws Exception
     */
    private function addUserUpdateValidation(): void
    {
        $this->validator->length('user_name', 3, 255)
            ->length('first_name', 1, 255)
            ->length('last_name', 1, 255)
            ->length('email', 5, 255)
            ->email('email')
            ->role('role')
        ;

        if ('admin' === $this->data['role']) {
            $this->validator->length('description', 1, 255)
                ->length('alt_url_avatar', 1, 255)
            ;
        }
    }

    /**
     * @throws Exception
     */
    private function addContactValidation(): void
    {
        $this->validator->length('last_name', 1, 255)
            ->length('first_name', 1, 255)
            ->length('from', 5, 255)
            ->length('subject', 1, 78)
            ->email('from')
        ;
    }
}
