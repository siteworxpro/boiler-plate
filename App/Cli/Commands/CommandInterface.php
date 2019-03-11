<?php declare(strict_types = 1);

namespace App\Cli\Commands;

/**
 * Interface CommandInterface
 * @package App\Cli\Commands
 */
interface CommandInterface
{
    /**
     * @return string
     */
    public static function getHelp(): string;

    /**
     * @return int Return exit code
     */
    public function execute(): int;

    /**
     * @return string
     */
    public static function commandSignature(): string;
}
