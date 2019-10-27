<?php

declare(strict_types=1);

namespace App\Library\Utilities;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;

/**
 * Class Helpers
 *
 * @package App\Library\Utilities
 */
final class Helpers
{
    /**
     * Generates a random string. Function is not for crypto use
     *
     * @param int $length
     *
     * @return string
     * @throws \Exception
     */
    public static function generateRandString(int $length = 25): string
    {
        $allowedChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxwy0123456789';
        $return = '';

        while (\strlen($return) < $length) {
            $rand = random_int(0, \strlen($allowedChars));
            $return .= $allowedChars[$rand] ?? '';
        }

        return $return;
    }

    /**
     * @param int $length
     * @return int
     * @throws \Exception
     */
    public static function generateRandNumber(int $length = 5): int
    {
        $allowedChars = '0123456789';
        $return = '';

        while (\strlen($return) < $length) {
            $rand = random_int(0, \strlen($allowedChars));
            $return .= $allowedChars[$rand] ?? '';
            $return = (int) $return;
        }

        return (int) $return;
    }

    /**
     * Returns a GUIDv4 string
     *
     * Fun Fact: Since there are about 7×10^22 stars in the universe,
     * and just under 2^128 possible GUIDs, then there are approximately
     * 4.86×10^15 (almost five quadrillion) GUIDs for every single star in
     * the known universe. That's a lot.
     *
     * @return string
     * @throws \Exception
     */
    public static function GUIDv4(): string
    {
        $uuidFactory = new UuidFactory();
        $uuidFactory->setRandomGenerator(new SodiumRandomGenerator());
        Uuid::setFactory($uuidFactory);

        return Uuid::uuid4()->toString();
    }

    /**
     * Returns a GUIDv5 String
     *
     * @param string $name
     *
     * @return string
     */
    public static function GUIDv5(string $name): string
    {
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, $name)->toString();
    }

    /**
     * Converts database snake_case column names to camelCase
     *
     * @param string $string
     * @return string
     */
    public static function convertToCamelCase(string $string): string
    {
        $string = str_replace('_', '', ucwords($string, '_'));

        return lcfirst($string); // lowercase first letter before returning
    }

    /**
     * Converts camelCase string to snake_case
     * Does not handle improper camelCase usage such as simpleXML -> simple_x_m_l
     *
     * @param string $string
     * @return string
     */
    public static function convertToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}
