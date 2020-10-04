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
     * @param $key
     * @param $value
     */
    public function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return null|mixed
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? null;
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

    /**
     * @param $key
     */
    public function remove($key): void
    {
        unset($_SESSION[$key]);
    }

    public function stop(): void
    {
        session_destroy();
    }
}
