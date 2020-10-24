<?php

namespace App\Core;

class Session
{
    private array $session;

    /**
     * Session constructor.
     *
     * @param array $session
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

    /**
     * @param array|string $messages
     */
    public function addMessages($messages): void
    {
        $flashMessages = [];
        if (\is_array($messages)) {
            foreach ($messages as $message) {
                $flashMessages[] = $message;
            }
        } else {
            $flashMessages[] = $messages;
        }
        $this->set('messages', $flashMessages);
    }

    /**
     * @return null|array
     */
    public function getMessages(): ?array
    {
        $return = $this->get('messages');
        $this->remove('messages');

        return $return;
    }
}
