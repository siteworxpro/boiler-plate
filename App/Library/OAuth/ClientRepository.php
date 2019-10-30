<?php

declare(strict_types=1);

namespace App\Library\OAuth;

use App\Library\OAuth\Entities\Client;
use League\OAuth2\Server\{Entities\ClientEntityInterface, Repositories\ClientRepositoryInterface};

class ClientRepository implements ClientRepositoryInterface
{

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     *                                        is confidential
     *
     * @return ClientEntityInterface | null
     */
    public function getClientEntity(
        $clientIdentifier
    ): ?ClientEntityInterface {
        $builder = Client::where('client_id', $clientIdentifier);

        /** @var Client $client */
        $client = $builder->get()->first();

        if ($client !== null) {
            return $client;
        }

        return null;
    }

    /**
     * Validate a client's secret.
     *
     * @param string $clientIdentifier The client's identifier
     * @param null|string $clientSecret The client's secret (if sent)
     * @param null|string $grantType The type of grant the client is using (if sent)
     *
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        $client = Client::where('client_id', $clientIdentifier)
            ->where('grant_type', 'LIKE', '%' . $grantType . '%')
            ->where('client_secret', $clientSecret)
            ->get()
            ->first();

        return $client instanceof Client;
    }
}
