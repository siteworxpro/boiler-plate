<?php

declare(strict_types=1);

namespace App\Library\Sessions\Drivers;

/**
 * Interface SessionDriverInterface
 * @package App\Library\Sessions\Drivers
 */
interface SessionDriverInterface
{
    /**
     * @param string $key
     */
    public function setSessionIdentifier(string $key): void;

    /**
     * @return string
     */
    public function getSessionIdentifier(): string;

    /**
     * @return string
     */
    public function getIp(): string;

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void;

    /**
     * @param string $key
     */
    public function setUserAgent(string $key): void;

    /**
     * @return string
     */
    public function getUserAgent(): string;

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Persist session data
     */
    public function save(): void;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return string
     */
    public function toJson(): string;

    /**
     * Ends and deletes session
     */
    public function delete(): void;

    /**
     * Set to expire session
     * @param bool $expire
     */
    public function setSessionExpiration(bool $expire): void;
}
