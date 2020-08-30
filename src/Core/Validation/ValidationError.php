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
        'required' => 'Le champ %s est obligatoire',
        'not_blank' => 'Le champ %s ne doit pas être vide',
        'unique' => 'Le champ %s existe déjà',
        'range' => 'Le champ %s doit comporter de %d à %d caractères',
        'minimum_length' => 'Le champ %s doit contenir au minimum %d caractères',
        'maximum_length' => 'Le champ %s doit contenir au maximum %d caractères',
        'datetime' => 'Le champ %s doit être une date valide (\'%s\')',
        'email' => 'Le champ %s doit être un email valide',
        'password' => 'Le champ %s doit contenir au moins une lettre minuscule, une majuscule, un chiffre et un caractère spécial',
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
