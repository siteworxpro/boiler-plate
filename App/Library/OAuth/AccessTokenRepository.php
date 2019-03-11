<?php declare(strict_types = 1);

namespace App\Library\OAuth;

use App\Library\OAuth\Entities\{AccessToken, TokenScope};
use League\OAuth2\Server\Entities\{AccessTokenEntityInterface, ClientEntityInterface, ScopeEntityInterface};
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * Class AccessTokenRepository
 *
 * @package App\Library\OAuth
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{

    /**
     * Create a new access token
     *
     * @param ClientEntityInterface $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ): AccessTokenEntityInterface {
        return new AccessToken();
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param AccessTokenEntityInterface | AccessToken $accessTokenEntity
     *
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity): void
    {
        $accessTokenEntity->save();

        //Iterate through scopes for token and save to db
        foreach ($accessTokenEntity->getScopes() as $scope) {
            $tokenScope = new TokenScope();
            $tokenScope->token_id = $accessTokenEntity->id;
            $tokenScope->scope_id = $scope;
            $tokenScope->save();
        }
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId): void
    {
        /** @var AccessToken $token */
        $token = AccessToken::where('token', $tokenId)->get()->first();

        $token->is_revoked = true;
        $token->save();
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        /** @var AccessToken $token */
        $token = AccessToken::where('token', $tokenId)->get()->first();

        return $token === null || $token->is_revoked;
    }
}
