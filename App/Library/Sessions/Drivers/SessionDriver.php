<?php

declare(strict_types=1);

namespace App\Library\Sessions\Drivers;

/**
 * Class SessionDriver
 * @package App\Library\Sessions\Drivers
 */
abstract class SessionDriver implements SessionDriverInterface
{

    /**
     * @var string
     */
    protected $sessionKey;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @param mixed $sessionKey
     */
    public function setSessionIdentifier($sessionKey): void
    {
        $this->sessionKey = $sessionKey;
    }

    /**
     * @return mixed
     */
    public function getSessionIdentifier(): string
    {
        return $this->sessionKey;
    }

    /**
     * @return mixed
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param string $key
     */
    public function setUserAgent(string $key): void
    {
        $this->userAgent = $key;
    }
}
