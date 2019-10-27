<?php

declare(strict_types=1);

namespace App\Library;

/**
 * Class Crypt
 *
 * @package App\Library
 */
final class Crypt
{

    /**
     * @var string
     */
    private $key;

    /**
     * Crypt constructor.
     *
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = sodium_hex2bin($key);
    }

    /**
     * @param $password
     *
     * @return string
     */
    public static function encryptPassword(string $password): string
    {
        return sodium_crypto_pwhash_str(
            $password,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE
        );
    }

    /**
     * @param $password
     * @param $passwordHash
     *
     * @return bool
     */
    public static function verifyPassword(string $password, string $passwordHash): bool
    {
        if (sodium_crypto_pwhash_str_verify($passwordHash, $password)) {
            sodium_memzero($password);

            return true;
        }

        return false;
    }

    /**
     * @param $string
     *
     * @return string
     * @throws \Exception
     */
    public function encrypt($string): string
    {
        $nonce = random_bytes(24);

        $encryptionBin = sodium_crypto_secretbox($string, $nonce, $this->key);

        return sodium_bin2hex($nonce) . sodium_bin2hex($encryptionBin);
    }

    /**
     * @param $encString
     *
     * @return string
     */
    public function decrypt($encString): string
    {
        $nonce = sodium_hex2bin(substr($encString, 0, 48));
        $cipherText = sodium_hex2bin(substr($encString, 48));

        return sodium_crypto_secretbox_open($cipherText, $nonce, $this->key);
    }
}
