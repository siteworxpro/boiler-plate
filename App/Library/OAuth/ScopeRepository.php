<?php

declare(strict_types=1);

namespace App\Library\OAuth;

use App\Library\OAuth\Entities\{Client, Scope};
use League\OAuth2\Server\{Entities\ClientEntityInterface,
    Entities\ScopeEntityInterface,
    Repositories\ScopeRepositoryInterface};

class ScopeRepository implements ScopeRepositoryInterface
{

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface
    {
        return Scope::where('scope_name', $identifier)->get()->first();
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of
     * scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[]|Scope $scopes
     * @param string $grantType
     * @param ClientEntityInterface|Client $clientEntity
     * @param null|string $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array {

        $allowedScopes = [];

        /** @var Scope $scope */
        foreach ($clientEntity->scopes as $scope) {
            $allowedScopes[] = $scope;
        }

        return $allowedScopes;
    }
}
