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
        $this->key = $this->translateKey($key, $this->getTranslations());
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    public function __toString()
    {
        $params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);

        return sprintf(...$params);
    }

    /**
     * @return array
     */
    private function getTranslations(): array
    {
        return yaml_parse_file(__DIR__.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'translations'.\DIRECTORY_SEPARATOR.'french.yaml');
    }

    /**
     * @param string $key
     * @param array  $translations
     *
     * @return string
     */
    private function translateKey(string $key, array $translations): string
    {
        if (\array_key_exists($key, $translations)) {
            $key = $translations[$key];
        }

        return $key;
    }
}
