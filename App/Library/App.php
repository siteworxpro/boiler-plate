<?php declare(strict_types = 1);

namespace App\Library;

use App\Controllers\Api\V1\ClientController;
use App\Controllers\OAuth\AccessTokenController;
use App\Controllers\Web\IndexController;
use App\Middleware\OAuthMiddleware;
use App\Middleware\ValidationMiddleware;
use Slim\App as SlimApp;

/**
 * Class App
 *
 * @package App\Library
 */
class App extends SlimApp
{

    /**
     * App constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        $this->registerApiV1();
        $this->registerOAuth();
        $this->registerWeb(); // remove if only api
    }

    private function registerOAuth(): void
    {
        $this->group('/oauth', function () {
            $this->group('/access_token', function () {
                $this->post('', AccessTokenController::class . ':postAction');
                $this->delete('', AccessTokenController::class . ':deleteAction')
                ->add(new OAuthMiddleware());
            });
        });

        $this->group('/oauth', function () {
            $this->group('/access_token', function () {
            });
        });
    }

    private function registerWeb(): void
    {
        $this->get('/', IndexController::class . ':getAction');
    }

    private function registerApiV1(): void
    {
        $this->group('/api', function () {
            $this->group('/v1', function () {
                $this->group('/client', function () {
                    $this->get('', ClientController::class . ':getAction');
                });
            });
        })
            ->add(new ValidationMiddleware())
            ->add(new OAuthMiddleware());
    }

    /**
     * @return Container|\Psr\Container\ContainerInterface
     */
    public static function di(): Container
    {
        return self::getApp()->getContainer();
    }

    /**
     * @return App
     */
    public static function getApp(): App
    {
        /** @var App $app */
        return $GLOBALS['app'];
    }
}
