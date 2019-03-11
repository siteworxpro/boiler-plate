<?php declare(strict_types = 1);

namespace App\Library;

use App\Library\OAuth\{AccessTokenRepository, ClientRepository, Entities\AccessToken, Entities\Client, ScopeRepository};
use Carbon\Carbon;
use League\OAuth2\Server\{AuthorizationServer, Grant\ClientCredentialsGrant, ResourceServer};
use Monolog\Handler\{RotatingFileHandler, StreamHandler};
use Monolog\Logger;
use Monolog\Processor\{MemoryUsageProcessor, WebProcessor};
use Noodlehaus\Config;
use Psr\Log\LogLevel;
use Slim\{Container as SlimContainer, Http\Request, Http\Response, Http\StatusCode};
use Whoops\{Handler\JsonResponseHandler, Handler\PrettyPageHandler, Run};


/**
 * Class Container
 *
 * @property Config config
 * @property Logger log
 * @property Client client
 * @property AccessToken token
 * @property Twig view
 * @property Response response
 * @property ResourceServer resourceServer
 * @property AuthorizationServer oAuthServer
 *
 * @package App\Library
 */
final class Container extends SlimContainer
{

    /**
     * @var bool
     */
    private $isBooted = false;

    public function __construct(array $values = [])
    {
        if ($this->isBooted === false) {
            $this->bootstrap();
        }

        parent::__construct($values);
    }

    /**
     * boot the application
     */
    private function bootstrap(): void
    {
        /*
         |--------------------------------------------------------------------------
         | Config
         |--------------------------------------------------------------------------
         */
        $this['config'] = function () {
            return new Config(__DIR__ . '/../../var/config/config.php');
        };

        /*
        |--------------------------------------------------------------------------
        | Logger
        |--------------------------------------------------------------------------
        */
        $this['log'] = function () {
            if ($this->config->get('log.std_out', false)) {
                $handler = new StreamHandler('php://stdout', $this->config->get('log.level', LogLevel::INFO));
            } else {
                $handler = new RotatingFileHandler(
                    $this->config->get('run_dir') . '/var/logs/app.log',
                    5,
                    $this->config->get('log.level', LogLevel::INFO)
                );
            }

            $logger = new Logger('app', [
                $handler
            ], [
                new MemoryUsageProcessor()
            ]);

            if ($this->config->get('cli', false) !== true) {
                $logger->pushProcessor(new WebProcessor());
            }

            return $logger;
        };

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */
        $this['view'] = function () {
            $loader = new \Twig_Loader_Filesystem($this->config->get('run_dir') . '/App/Views');
            $twig = new Twig($loader, [
                'cache' => $this->config->get('run_dir') . '/var/cache/views',
                'auto_reload' => $this->config->get('dev_mode', false)
            ]);
            $twig->addGlobal('config', $this->config);
            $twig->addGlobal('year', Carbon::now()->format('Y'));

            return $twig;
        };

        /*
        |--------------------------------------------------------------------------
        | oAuth Resource Server
        |--------------------------------------------------------------------------
        */
        $this['resourceServer'] = function () {
            $accessTokenRepository = new AccessTokenRepository();
            $privateKey = $this->config->get('run_dir') . '/authorization.key';

            return new ResourceServer(
                $accessTokenRepository,
                $privateKey
            );
        };

        /*
        |--------------------------------------------------------------------------
        | oAuth Server
        |--------------------------------------------------------------------------
        */
        $this['oAuthServer'] = function () {

            $clientRepository = new ClientRepository();
            $scopeRepository = new ScopeRepository();
            $accessTokenRepository = new AccessTokenRepository();

            $privateKey = $this->config->get('run_dir') . '/authorization.key';
            $encryptionKey = $this->config->get('encryption_key');

            if ($encryptionKey === null) {
                throw new \RuntimeException('Encryption key is missing from config');
            }

            $server = new AuthorizationServer(
                $clientRepository,
                $accessTokenRepository,
                $scopeRepository,
                $privateKey,
                $encryptionKey
            );

            try {
                $server->enableGrantType(
                    new ClientCredentialsGrant(),
                    new \DateInterval($this->config->get('dev_mode', false) ? 'P1M' : 'PT1H')
                );
            } catch (\Exception $exception) {
                $this->log->critical($exception->getMessage());
            }

            return $server;
        };

        $this->registerErrorHandlers();

        $this->isBooted = true;
    }

    /**
     * regsiter all error handlers 404/500s
     */
    private function registerErrorHandlers(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 404
        |--------------------------------------------------------------------------
        */
        $this['notFoundHandler'] = function ($container) {
            return function (Request $request, Response $response) use ($container) {

                $container->log->warning('File not found: ' . $request->getUri());

                if ($request->isXhr()) {
                    return $response->withStatus(StatusCode::HTTP_NOT_FOUND)->withJson([
                        'error' => true,
                        'errorMessage' => 'Not Found'
                    ]);
                }

                return $response->write($this->view->render('Errors/404'));
            };
        };

        /*
        |--------------------------------------------------------------------------
        | 404/Method Not Allowed
        |--------------------------------------------------------------------------
        */
        $this['notAllowedHandler'] = function ($container) {
            return function (Request $request, Response $response) use ($container) {

                $container->log->warning('File not found: ' . $request->getUri());

                return $response->withStatus(StatusCode::HTTP_NOT_FOUND)->withJson([
                    'error' => true,
                    'errorMessage' => 'Not Found'
                ]);
            };
        };

        $this['phpErrorHandler'] = function () {
            return function (Request $request, Response $response, \Throwable $error) {

                $this->log->error(
                    $error->getMessage() . ' in file ' . $error->getFile() . ' at line ' . $error->getLine()
                );

                if ($this->config->get('dev_mode')) {
                    $handler = $request->isXhr() ? new JsonResponseHandler() : new PrettyPageHandler();

                    $whoops = new Run();
                    $whoops->pushHandler($handler);
                    $whoops->handleException($error);
                }

                if ($request->isXhr()) {
                    $return = [
                        'isError' => true, 'errMessage' => 'Server Error!'
                    ];
                    $return = json_encode($return);
                    $contentType = 'application/json';
                } else {
                    $return = $this->view->render('Errors/500');
                    $contentType = 'text/html';
                }

                return $this->response->withStatus(500)->withHeader('Content-Type', $contentType)->write($return);
            };
        };

        $this['errorHandler'] = function () {
            return function (Request $request, Response $response, \Throwable $error) {

                $this->log->error(
                    $error->getMessage() . ' in file ' . $error->getFile() . ' at line ' . $error->getLine()
                );

                if ($this->config->get('dev_mode')) {
                    $handler = $request->isXhr() ? new JsonResponseHandler() : new PrettyPageHandler();

                    $whoops = new Run();
                    $whoops->pushHandler($handler);
                    $whoops->handleException($error);
                }

                if ($request->isXhr()) {
                    $return = [
                        'isError' => true, 'errMessage' => 'Server Error!'
                    ];
                    $return = json_encode($return);
                    $contentType = 'application/json';
                } else {
                    $return = $this->view->render('Errors/500');
                    $contentType = 'text/html';
                }

                return $this->response->withStatus(500)->withHeader('Content-Type', $contentType)->write($return);
            };
        };

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            $log = $this->log;

            switch ($errno) {
                case E_DEPRECATED:
                case E_NOTICE:
                    $log->debug($errstr . ' in file ' . $errfile . ' on line ' . $errline);

                    break;
                case E_RECOVERABLE_ERROR:
                case E_WARNING:
                    $log->warning($errstr . ' in file ' . $errfile . ' on line ' . $errline);

                    break;
                case E_ERROR:
                    $this->view->render('Errors/500.twig');
                    $log->error($errstr . ' in file ' . $errfile . ' on line ' . $errline);

                    break;
            }
        });
    }
}
