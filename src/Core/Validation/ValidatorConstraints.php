<?php

namespace App\Core\Validation;

use App\Core\Manager;
use Exception;

class ValidatorConstraints
{
    /**
     * @var array
     */
    protected array $data;
    /**
     * @var string[]
     */
    protected array $errors = [];
    /**
     * @var Manager
     */
    protected Manager $manager;

    /**
     * ValidatorConstraints constructor.
     *
     * @param array $data
     * @param       $manager
     */
    public function __construct(array $data, $manager = null)
    {
        $this->data = $data;
        if ($manager) {
            $this->manager = $manager;
        }
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
     *
     * @throws Exception
     *
     * @return $this
     */
    public function unique(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (false === $this->manager->preventReuse([$key => $value])) {
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

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
     *
     * @return $this
     */
    public function role(string $key): self
    {
        $role = $this->getValue($key);
        if (!\in_array($role, ['user', 'admin'], true)) {
            $this->addError($key, 'role');

            return $this;
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $format
     *
     * @return ValidatorConstraints
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
    public function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[$key] = new ValidationError($key, $rule, $attributes);
    }

    /**
     * @param string $key
     *
     * @return null|mixed
     */
    public function getValue(string $key)
    {
        return $this->data[$key] ?? null;
    }
}
