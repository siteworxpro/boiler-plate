<?php

declare(strict_types=1);

namespace App\Library\Sessions;

use App\Library\App;
use App\Library\Cookie;
use App\Library\Sessions\Drivers\SessionDriverInterface;
use App\Library\Utilities\Helpers;

/**
 * Class Session
 *
 * @package App\Library
 */
final class Session
{

    /**
     * @var SessionDriverInterface
     */
    private $driver;

    /**
     * @var string
     */
    private $key;

    /**
     * @var Cookie
     */
    private $cookie;

    /**
     * Session constructor.
     * @param SessionDriverInterface $driver
     * @throws \Exception
     */
    public function __construct(SessionDriverInterface $driver)
    {
        $this->driver = $driver;
        $this->cookie = new Cookie();
        $this->generateSessionKey();
        $this->driver->setIp(App::di()->request->getServerParam('REMOTE_ADDR'));
        $this->driver->setUserAgent(App::di()->request->getServerParam('HTTP_USER_AGENT') ?? 'No user agent');
    }

    /**
     * Purge session
     * @throws \Exception
     */
    public function purge(): void
    {
        $this->cookie->unset(App::di()->config['app_name']);
        $this->driver->delete();
    }

    /**
     * @param string $key
     * @param null   $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return  $this->driver->get($key, $default);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->driver->toArray();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->key;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value): void
    {
        $this->driver->set($key, $value);
        $this->driver->save();
    }

    /**
     * @throws \Exception
     */
    private function generateSessionKey(): void
    {
        $this->key = $this->cookie->get(App::di()->config['app_name'], false);

        if (!$this->key) {
            $this->key = Helpers::GUIDv4();
            $this->cookie->set(App::di()->config['app_name'], $this->key, Cookie::ONE_DAY * 30);
        } else {
            $this->key = $this->cookie->get(App::di()->config['app_name']);
        }

        $this->driver->setSessionIdentifier($this->key);
    }
}
