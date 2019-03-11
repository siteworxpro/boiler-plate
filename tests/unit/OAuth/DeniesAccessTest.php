<?php

namespace Tests\Unit;

use Slim\Http\StatusCode;
use Tests\Fixtures\Client;

/**
 * Class ExampleTest
 * @package Tests\Unit
 */
class DeniesAccessTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     * @throws \Exception
     */
    public function testMiddlewareDeniesAccess(): void
    {
        $request = $this->tester->getMockRequest([
            'uri' => '/api/v1/client'
        ]);

        $this->tester->processRequest($request, StatusCode::HTTP_UNAUTHORIZED);

        $client = Client::generate();

        $accessToken = $this->tester->getAccessToken($client->getModel());

        $request = $request->withHeader('Authorization', 'Bearer ' . $accessToken);

        $response = $this->tester->processRequest($request);

        $this->assertContains($client->getModel()->client_id, $response->getBody()->getContents());

        $request = $this->tester->getMockRequest([
            'uri' => '/oauth/access_token',
            'method' => 'DELETE'
        ])->withHeader('Authorization', 'Bearer ' . $accessToken);

        $this->tester->processRequest($request);

        $request = $this->tester->getMockRequest([
            'uri' => '/api/v1/client',
        ])->withHeader('Authorization', 'Bearer ' . $accessToken);

        $this->tester->processRequest($request, StatusCode::HTTP_UNAUTHORIZED);
    }

}