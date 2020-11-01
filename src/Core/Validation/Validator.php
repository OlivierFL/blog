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
     * @return ValidatorConstraints
     */
    public function getLoginValidator(): ValidatorConstraints
    {
        return $this->getBaseValidator();
    }

    /**
     * @throws Exception
     *
     * @return ValidatorConstraints
     */
    public function getUserUpdateValidator(): ValidatorConstraints
    {
        foreach ($this->data as $key => $value) {
            if ($value) {
                $this->addUserUpdateValidations($key);
            }
        }

        return $this->validator;
    }

    /**
     * @return ValidatorConstraints
     */
    public function getPostCreateValidator(): ValidatorConstraints
    {
        return $this->getLoginValidator();
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

    public function getCommentValidator(): ValidatorConstraints
    {
        return $this->getPostCreateValidator();
    }

    /**
     * @return ValidatorConstraints
     */
    private function getBaseValidator(): ValidatorConstraints
    {
        $this->addBaseValidation();

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
     * @param string $key
     *
     * @throws Exception
     */
    private function addUserUpdateValidations(string $key): void
    {
        if ('user_name' === $key) {
            $this->validator->length('user_name', 3, 255);
        }

        if ('last_name' === $key || 'first_name' === $key) {
            $this->validator->length($key, 1, 255);
        }

        if ('email' === $key) {
            $this->validator->length('email', 5, 255)
                ->email('email')
            ;
        }

        if ('role' === $key) {
            $this->validator->role('role');
        }
    }
}
