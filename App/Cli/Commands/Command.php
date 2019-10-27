<?php

declare(strict_types=1);

namespace App\Cli\Commands;

use League\CLImate\CLImate;

/**
 * Class Command
 * @package App\Cli\Commands
 */
abstract class Command implements CommandInterface
{

    protected $cli;

    public function __construct()
    {
        $this->cli = new CLImate();
    }
}
