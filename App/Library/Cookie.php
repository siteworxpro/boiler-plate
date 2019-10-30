<?php

namespace App\Library;

/**
 * Class Cookie
 *
 * @package App\Library
 */
final class Cookie
{

    public const ONE_DAY = 86400;
    public const ONE_HOUR = 3600;

    /**
     * @var Crypt
     */
    private $crypt;

    /**
     * @var bool
     */
    private $encrypt;

    /**
     * @var bool
     */
    private $forceInsecure;

    /**
     * Cookie constructor.
     */
    public function __construct()
    {
        $container = App::di();

        $this->crypt = new Crypt($container->config['encryption_key']);
        $this->encrypt = $container->config->get('cookies.encrypt', false);
        $this->forceInsecure = $container->config->get('cookies.force_insecure', false);
    }

    /**
     * @param string $key
     * @param string $default
     *
     * @return bool|string
     */
    public function get($key, $default = null)
    {
        if (!isset($_COOKIE[$key])) {
            return $default;
        }

        $cookieEnc = $_COOKIE[$key];

        if (empty($cookieEnc)) {
            return $default;
        }

        if ($this->encrypt) {
            return $this->crypt->decrypt($cookieEnc);
        }

        return $cookieEnc;
    }

    public function unset(string $key): void
    {
        unset($_COOKIE[$key]);
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $expires
     * @param bool $secure
     * @param bool $httpOnly
     * @throws \Exception
     */
    public function set(
        string $key,
        string $value,
        int $expires = self::ONE_DAY,
        bool $secure = true,
        bool $httpOnly = true
    ): void {
        if ($this->encrypt) {
            $value = $this->crypt->encrypt($value);
        }

        /** Use with caution */
        if ($this->forceInsecure) {
            $secure = false;
        }

        setcookie($key, $value, time() + $expires, '/', false, $secure, $httpOnly);
    }
}
