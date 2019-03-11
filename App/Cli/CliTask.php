<?php declare(strict_types = 1);

namespace App\Cli;

use App\Cli\Commands\{CommandInterface, Cron\RunCron, HelloWorld, OAuth\GenerateKey, Queue\StartConsumer};
use App\Library\Container;
use League\CLImate\CLImate;
use Slim\App;

/**
 * Class CliTask
 *
 * @package App\Cli
 */
class CliTask
{

    private const COMMANDS = [
        HelloWorld::class,
        RunCron::class,
        StartConsumer::class,
        GenerateKey::class
    ];

    /**
     * @var CLImate
     */
    protected $cli;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var App
     */
    protected $app;

    /**
     * CliTask constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        /** @var Container _container */
        $this->container = $app->getContainer();
        $this->cli = new CLImate();
        $this->printCopyright();
    }

    private function printCopyright(): void
    {
        $this->cli->border('-', 65)->br();
        $this->cli->tab()->bold('<white>SiteworxPro Micro Service</white>');
        $this->cli->tab()->info('Copyright (c) ' . date('Y') . ' Siteworx Professionals LLC.');
        $this->cli->tab()->info('Ron Rise <ron@siteworxpro.com>');
        $this->cli->tab()->info('Version: ' . $this->container->config->get('settings.deployment'))->br();
        $this->cli->border('-', 65)->br();
    }

    /**
     * @param string $task
     * @param array $params
     */
    public function handle(string $task, array $params = []): void
    {
        $startTime = microtime(true);

        if ($task === '') {
            $this->cli->error('Error! Invalid arguments.');
            $this->cli->out('Usage: cli [job] [params]');
            $this->printUsage();
            exit(1);
        }

        $commandClass = null;

        /** @var CommandInterface $command */
        foreach (self::COMMANDS as $command) {
            if ($command::commandSignature() === str_replace('--', '', $task)) {
                $commandClass = $command;

                continue;
            }
        }

        if ($commandClass === null) {
            $this->cli->error('Error! Not a task. Did you register it correctly?');
            $this->printUsage();
            exit(1);
        }

        /** @var CommandInterface $job */
        $job = new $commandClass();

        $this->cli->info('Starting job....');

        try {
            $code = $job->execute();
        } catch (\Exception $exception) {
            $this->cli->error($exception->getMessage());
            $this->cli->info($exception->getTraceAsString());
            $code = $exception->getCode();

            if (!$code) {
                $code = 1;
            }
        } catch (\Throwable $exception) {
            $this->cli->error($exception->getMessage());
            $this->cli->info($exception->getTraceAsString());
            $code = $exception->getCode();

            if (!$code) {
                $code = 1;
            }
        }

        if ($code !== 0) {
            $this->cli->out('<red><blink>Error!!</blink> Job <bold>FAILED!</bold></red>')->br();
            exit($code);
        }

        $this->cli->out('<green>Job Completed <bold>Successfully!</bold></green>');

        $time = microtime(true) - $startTime;

        $this->cli->out('<blue>Total Execution Time: ' . round($time, 5) . 's')->br();
        exit($code);
    }

    private function printUsage(): void
    {
        /** @var CommandInterface $command */
        foreach (self::COMMANDS as $command) {
            $this->cli->info('--' . $command::commandSignature());
            $this->cli->tab()->info($command::getHelp())->br();
        }
    }
}
