<?php declare(strict_types = 1);

namespace App\Library\OAuth;

use App\Library\OAuth\Entities\Client;
use League\OAuth2\Server\{Entities\ClientEntityInterface, Repositories\ClientRepositoryInterface};

class ClientRepository implements ClientRepositoryInterface
{

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     * @param string $grantType The grant type used
     * @param null|string $clientSecret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return ClientEntityInterface | null
     */
    public function getClientEntity(
        $clientIdentifier,
        $grantType,
        $clientSecret = null,
        $mustValidateSecret = true
    ): ?ClientEntityInterface {
        $builder = Client::where('client_id', $clientIdentifier)
            ->where('grant_type', 'LIKE', '%' . $grantType . '%');

        if ($mustValidateSecret) {
            $builder->where('client_secret', $clientSecret);
        }

        /** @var Client $client */
        $client = $builder->get()->first();

        if ($client !== null) {
            return $client;
        }

        return null;
    }
}
