<?php

declare(strict_types=1);

namespace App\Library\Utilities;

use Ramsey\Uuid\Generator\RandomGeneratorInterface;

/**
 * SodiumRandomGenerator provides functionality to generate strings of random
 * binary data using the PECL libsodium extension
 *
 * @link http://pecl.php.net/package/libsodium
 * @link https://paragonie.com/book/pecl-libsodium
 */
class SodiumRandomGenerator implements RandomGeneratorInterface
{
    /**
     * Generates a string of random binary data of the specified length
     *
     * @param integer $length The number of bytes of random binary data to generate
     * @return string A binary string
     * @throws \Exception
     */
    public function generate($length): string
    {
        return random_bytes($length);
    }
}
