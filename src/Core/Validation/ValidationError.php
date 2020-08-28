<?php

namespace App\Core\Validation;

class ValidationError
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $rule;
    /**
     * @var array
     */
    private $messages = [
        'required' => 'The field %s is required',
        'not_blank' => 'The field %s must not be empty',
        'unique' => 'The field %s already exists in database',
        'range' => 'The field %s must be in %d and %d characters',
        'minimum_length' => 'The field %s must contain at least %d characters',
        'maximum_length' => 'The field %s must be under %d characters',
        'datetime' => 'The field %s must be a valid date (\'%s\')',
        'email' => 'The field %s must be a valid email address',
        'password' => 'The field %s must contain at least one lower case, one capital, one digit, and one special character',
    ];
    /**
     * @var array
     */
    private $attributes;

    /**
     * ValidationError constructor.
     *
     * @param string $key
     * @param string $rule
     * @param array  $attributes
     */
    public function __construct(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);

        return (string) sprintf(...$params);
    }
}
