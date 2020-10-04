<?php

namespace App\Core;

class Session
{
    private array $session;

    /**
     * Session constructor.
     *
     * @param $session
     */
    public function __construct(array $session)
    {
        $this->session = $session;
    }

    /**
     * @param $name
     * @param $value
     */
    public function set($name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param $name
     *
     * @return null|mixed
     */
    public function get($name)
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getSession(): array
    {
        return $this->session;
    }

    /**
     * @return bool
     */
    public function hasSession(): bool
    {
        return isset($_SESSION) && !empty($_SESSION);
    }

    public function stop(): void
    {
        session_destroy();
    }
}
