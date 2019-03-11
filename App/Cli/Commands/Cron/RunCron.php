<?php declare(strict_types = 1);

namespace App\Cli\Commands\Cron;

use App\Cli\Commands\Command;
use App\Library\App;
use App\Library\Cron\JobInterface;

/**
 * Class RunCron
 * @package App\Cli\Commands\Cron
 */
class RunCron extends Command
{

    /**
     * @return string
     */
    public static function getHelp(): string
    {
        return 'run scheduled jobs';
    }

    /**
     * @return int Return exit code
     */
    public function execute(): int
    {
        $path = App::di()->config->get('run_dir') . '/App/Library/Cron/Jobs';

        $files = scandir($path, SORT_ASC);

        $skip = [
            '.', '..', 'Job', 'JobInterface'
        ];

        foreach ($files as $file) {
            $className = str_replace('.php', '', $file);

            if (\in_array($className, $skip, true)) {
                continue;
            }

            $fullClassName = 'App\\Library\\Cron\\Jobs\\' . $className;

            /** @var JobInterface $class */
            $class = new $fullClassName();

            $class->checkAndRun();
        }

        return 0;
    }

    /**
     * @return string
     */
    public static function commandSignature(): string
    {
        return 'cron';
    }
}
