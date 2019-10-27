<?php

declare(strict_types=1);

namespace App\Cli\Commands;

/**
 * Class HelloWorld
 * @package App\Cli\Commands
 */
class HelloWorld extends Command
{

    /**
     * @return string
     */
    public static function getHelp(): string
    {
        return 'Says <blue>hello</blue> to the <underline>world</underline>!';
    }

    /**
     * @return int Return exit code
     */
    public function execute(): int
    {
        $this->cli->info('Hello world it\'s good to see you!')->br();
        $this->cli->yellow('Thank you for trying this Siteworx Pro Boilerplate!')->br();

        return 0;
    }

    public static function commandSignature(): string
    {
        return 'hello-world';
    }
}
