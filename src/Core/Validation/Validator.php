<?php

namespace App\Core\Validation;

class Validator
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var string[]
     */
    private $errors = [];

    /**
     * Validator constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Check if fields are in the data array.
     *
     * @param string ...$keys
     *
     * @return $this
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            if (!\array_key_exists($key, $this->data)) {
                $this->addError($key, 'required', );
            }
        }

        return $this;
    }

    /**
     * @param string ...$keys
     *
     * @return $this
     */
    public function notBlank(string ...$keys): self
    {
        foreach ($keys as $key) {
            if (empty($this->data[$key]) || null === $this->data[$key]) {
                $this->addError($key, 'not_blank', );
            }
        }

        return $this;
    }

    /**
     * @param string ...$keys
     * @param        $manager
     *
     * @return $this
     */
    public function unique($manager, string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (false === $manager->preventReuse([$key => $value])) {
                $this->addError($key, 'unique');
            }
        }

        return $this;
    }

    /**
     * @param string   $key
     * @param null|int $min
     * @param null|int $max
     *
     * @return $this
     */
    public function length(string $key, ?int $min, ?int $max = null): self
    {
        $length = \strlen($this->getValue($key));
        if (
            null !== $min &&
            null !== $max &&
            ($length < $min ||
                $length > $max)
        ) {
            $this->addError($key, 'range', [$min, $max]);

            return $this;
        }

        if (null !== $min && $length < $min) {
            $this->addError($key, 'minimum_length', [$min]);

            return $this;
        }

        if (null !== $max && $length > $max) {
            $this->addError($key, 'maximum_length', [$max]);

            return $this;
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function email(string $key): self
    {
        $email = $this->getValue($key);

        if (!preg_match('/(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][\d])|1[\d]{2}|[1-9]?[\d]))\.){3}(?:(2(5[0-5]|[0-4][\d])|1[\d]{2}|[1-9]?[\d])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)])/', $email)) {
            $this->addError($key, 'email');

            return $this;
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function password(string $key): self
    {
        $password = $this->getValue($key);

        if (!preg_match('/.*^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/', $password)) {
            $this->addError($key, 'password');

            return $this;
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $format
     *
     * @return Validator
     */
    public function dateTime(string $key, string $format = 'Y-m-d H:i:s'): self
    {
        $date = \DateTime::createFromFormat($format, $this->getValue($key));
        if (
            false === $date ||
            0 < \DateTime::getLastErrors()['error_count'] ||
            0 < \DateTime::getLastErrors()['warning_count']
        ) {
            $this->addError($key, 'datetime', [$format]);

            return $this;
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * @param string $key
     * @param string $rule
     * @param array  $attributes
     */
    private function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    private function getValue(string $key)
    {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }
}
