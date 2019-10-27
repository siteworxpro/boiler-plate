<?php

declare(strict_types=1);

namespace App\Library\Sessions\Drivers;

/**
 * Class Redis
 * @package App\Library\Sessions\Drivers
 */
final class Redis extends SessionDriver
{

    private $client;

    public function __construct(array $config)
    {
        $this->client = new \Redis();

        if (!$this->client->connect($config['host'])) {
            throw new \RuntimeException('Unable to connect to redis server');
        }
    }

    private function namespaceKey($key): string
    {
        return $this->getSessionIdentifier() . '.' . $key;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->client->set($this->namespaceKey($key), $value);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->client->get($this->namespaceKey($key)) ?? $default;
    }

    /**
     * Persist session data
     */
    public function save(): void
    {
        // nothing to save
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->client->get($this->namespaceKey('*'));
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR, 512);
    }

    /**
     * Ends and deletes session
     */
    public function delete(): void
    {
        $this->client->del($this->namespaceKey('*'));
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
