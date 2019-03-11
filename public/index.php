<?php

/*
|--------------------------------------------------------------------------
| Get Ready....
|--------------------------------------------------------------------------
*/
require '../vendor/autoload.php';

$container = new \App\Library\Container;
$app = new \App\Library\App($container);

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

    $container->log->emergency($exception->getMessage() . ' in file ' . $exception->getFile() . ' on line ' . $exception->getLine());

    if ($container->config->get('dev_mode')) {
        $handler = new \Whoops\Handler\PrettyPageHandler();
        $whoops = new Whoops\Run();
        $whoops->pushHandler($handler);
        $whoops->handleException($exception);
        exit();
    }

    echo 'Server Error.';

} catch (\Interop\Container\Exception\ContainerException $e) {

    if ($container->config->get('dev_mode')) {
        $handler = new \Whoops\Handler\PrettyPageHandler();
        $whoops = new Whoops\Run();
        $whoops->pushHandler($handler);
        $whoops->handleException($exception);
        exit();
    }

    echo 'Server Error.';
}

