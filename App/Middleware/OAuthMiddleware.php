<?php declare(strict_types = 1);

namespace App\Middleware;

use App\Library\App;
use App\Library\OAuth\Entities\{AccessToken, Client};
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\RequestInterface;
use Slim\Http\{Request, Response};

/**
 * Class OAuthMiddleware
 *
 * @package App\Middleware
 */
class OAuthMiddleware extends Middleware
{
    /**
     * @param Request|RequestInterface $request
     * @param Response $response
     * @param callable $next
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return \Psr\Http\Message\ResponseInterface|Response
     */
    public function __invoke(Request $request, Response $response, callable $next): Response
    {
        $server = App::di()->resourceServer;

        try {
            $request = $server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
        }

        App::di()['client'] = static function () use ($request) {
            $headers = $request->getAttributes();
            /** @var Client $client */
            $client = Client::where('client_id', $headers['oauth_client_id'])->get()->first();

            return $client;
        };

        App::di()['token'] = static function () use ($request) {
            $headers = $request->getAttributes();
            /** @var AccessToken $token */
            $token = AccessToken::where('token', '=', $headers['oauth_access_token_id'])->get()->first();

            return $token;
        };

        return $next($request, $response);
    }
}
