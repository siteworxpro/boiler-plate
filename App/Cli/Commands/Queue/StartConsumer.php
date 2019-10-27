<?php

declare(strict_types=1);

namespace App\Cli\Commands\Queue;

use App\Cli\Commands\Command;
use App\Library\Queue\Consumer;

class StartConsumer extends Command
{

    /**
     * @return string
     */
    public static function getHelp(): string
    {
        return 'start consuming tasks';
    }

    /**
     * @return int Return exit code
     */
    public function execute(): int
    {
        try {
            $consumer = new Consumer();
            $consumer->startConsumer();
        } catch (\Exception $e) {
            $this->cli->error($e->getMessage());

            return $e->getCode() ?? 1;
        }

        return 0;
    }

    /**
     * @return string
     */
    public static function commandSignature(): string
    {
        return 'start-consumer';
    }
}
