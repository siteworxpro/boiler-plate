<?php

/*
|--------------------------------------------------------------------------
| Get Ready....
|--------------------------------------------------------------------------
*/

use App\Library\App;
use App\Library\Container;
use Interop\Container\Exception\ContainerException;
use Whoops\Handler\PrettyPageHandler;

require '../vendor/autoload.php';

$container = new Container();
$app = new App($container);

/*
|--------------------------------------------------------------------------
| Set.....
|--------------------------------------------------------------------------
*/

if ((!$_SERVER['HTTPS'] || !isset($_SERVER['HTTPS'])) && $container->config->get('force_ssl', true) === true) {
    $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $url);
    exit;
}

if ($container->config->get('dev_mode', false) === false) {
    ini_set('display_errors', 0);
}

date_default_timezone_set($container->config->get('settings.timezone', 'America/New_York'));

/*
|--------------------------------------------------------------------------
| Go!
|--------------------------------------------------------------------------
*/
try {
    $app->run();
} catch (\Exception $exception) {
    $container->log->emergency(
        $exception->getMessage() . ' in file ' . $exception->getFile() . ' on line ' . $exception->getLine()
    );

    if ($container->config->get('dev_mode')) {
        $handler = new PrettyPageHandler();
        $whoops = new Whoops\Run();
        $whoops->appendHandler($handler);
        $whoops->handleException($exception);

        exit;
    }

    echo 'Server Error.';

} catch (ContainerException $e) {
    if ($container->config->get('dev_mode')) {
        $handler = new PrettyPageHandler();
        $whoops = new Whoops\Run();
        $whoops->appendHandler($handler);
        $whoops->handleException($exception);
        exit;
    }

    echo 'Server Error.';
}
