<?php declare(strict_types=1);

namespace Tests\Fixtures;

use App\Library\OAuth\Entities\Client as ApiClient;
use App\Library\OAuth\Entities\ClientScope;
use App\Library\OAuth\Entities\Scope;
use App\Library\Utilities\Helpers;

class Client extends Fixture
{
    /**
     * @param array $params
     * @return self
     * @throws \Exception
     */
    public static function generate(array $params = []): FixtureInterface
    {
        $fixture = new static();

        $client = new ApiClient();
        $client->grant_type = $params['grant_type'] ?? 'client_credentials';
        $client->client_secret = $params['client_secret'] ?? Helpers::generateRandString(64);
        $client->client_id = $params['client_secret'] ?? Helpers::generateRandString(32);
        $client->save();

        $scope = new Scope();
        $scope->scope_name = 'default';
        $scope->scope_description = 'default';
        $scope->save();

        $clientScope = new ClientScope();
        $clientScope->client_id = $client->id;
        $clientScope->scope_id = $scope->id;
        $clientScope->save();

        $fixture->setModel($client);

        return $fixture;
    }
}
