<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Library\App;
use App\Library\Container;
use App\Library\OAuth\Entities\Client;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

class Unit extends \Codeception\Module
{
    private $app;

    /**
     * Define custom actions here
     * @param array $options
     * @return Request|\Psr\Http\Message\RequestInterface
     */
    public function getMockRequest(array $options = []): Request
    {
        $env = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => $options['method'] ?? 'GET',
            'REQUEST_URI' => $options['uri'] ?? '/',
            'QUERY_STRING' =>$options['query'] ?? '',
            'SERVER_NAME' => 'example.com',
            'REMOTE_ADDR' => $options['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);

        $request = Request::createFromEnvironment($env);
        $this->assertInstanceOf(Request::class, $request);

        return $request;
    }

    /**
     * @param Client $client
     * @return string
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function getAccessToken(Client $client): string
    {
        $request = $this->getMockRequest([
            'uri' => '/oauth/access_token',
            'method' => 'POST'
        ])->withParsedBody([
            'grant_type' => 'client_credentials',
            'client_id' => $client->client_id,
            'client_secret' => $client->client_secret,
            'scope' => 'default'
        ]);

        $response = App::di()->oAuthServer->respondToAccessTokenRequest($request, new Response());
        $body = $response->getBody();
        $body->rewind();
        $token = json_decode($body->getContents())->access_token;
        $this->assertNotEmpty($token);

        return $token;
    }

    /**
     * @param Request $request
     * @param int $expectedStatus
     * @return Response|\Psr\Http\Message\ResponseInterface
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function processRequest(Request $request, int $expectedStatus = StatusCode::HTTP_OK): Response
    {
        $this->app = $this->createApplication();
        $response = $this->app->process($request, new Response());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedStatus, $response->getStatusCode());
        $response->getBody()->rewind();
        return $response;
    }

    /**
     * @return App
     */
    public function createApplication(): App
    {
        global $container, $app;
        $container = new Container();
        $app = new App($container);
        return $app;
    }

}
