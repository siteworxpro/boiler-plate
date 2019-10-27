<?php

declare(strict_types=1);

namespace App\Library\Sessions\Drivers;

/**
 * Class Memcached
 * @package App\Library\Sessions\Drivers
 */
final class Memcached extends SessionDriver
{

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        // TODO: Implement set() method.
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        // TODO: Implement get() method.
    }

    /**
     * Persist session data
     */
    public function save(): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        // TODO: Implement toJson() method.
    }

    /**
     * Ends and deletes session
     */
    public function delete(): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * Set to expire session
     * @param bool $expire
     */
    public function setSessionExpiration(bool $expire): void
    {
        // TODO: Implement setSessionExpiration() method.
    }
}