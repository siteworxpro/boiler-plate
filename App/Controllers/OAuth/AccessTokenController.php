<?php declare(strict_types = 1);

namespace App\Controllers\OAuth;

use App\Controllers\Controller;
use League\OAuth2\Server\Exception\OAuthServerException;
use Slim\Http\{Request, Response};

/**
 * Class AccessTokenController
 * @package App\Controllers\OAuth
 */
final class AccessTokenController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function postAction(Request $request, Response $response, array $params): Response
    {
        /* @var \League\OAuth2\Server\AuthorizationServer $server */
        $server = self::di()->oAuthServer;

        try {
            //Strip body from response and update Slim response object
            $response_return = $server->respondToAccessTokenRequest($request, $response);
            $body = $response_return->getBody();
            $body->rewind();
            $body->getContents();

            return $response->withStatus($response_return
                ->getStatusCode())
                ->withHeader('Content-Type', 'application/json')
                ->write($body->getContents());
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            return $response->withStatus(500)->write($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws \Exception
     */
    public function deleteAction(Request $request, Response $response, array $params): Response
    {
        self::di()->token->is_revoked = true;
        self::di()->token->save();

        return $this->formatResponse($response);
    }
}
