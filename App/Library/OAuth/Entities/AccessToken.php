<?php

declare(strict_types=1);

namespace App\Library\OAuth\Entities;

use App\Models\Model;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\OAuth2\Server\Entities\{AccessTokenEntityInterface,
    ClientEntityInterface,
    ScopeEntityInterface,
    Traits\AccessTokenTrait,
    Traits\EntityTrait,
    Traits\TokenEntityTrait};

/**
 * Class AccessToken
 *
 * @property int id
 * @property int client_id
 * @property string token
 * @property boolean is_revoked
 * @property string expires
 * @property Client client
 *
 * @property bool is_user_token
 *
 * @package App\Library\OAuth\Entities
 */
class AccessToken extends Model implements AccessTokenEntityInterface
{
    use TokenEntityTrait;
    use EntityTrait;
    use AccessTokenTrait;


    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @param ScopeEntityInterface|Scope $scope
     */
    public function addScope(ScopeEntityInterface $scope): void
    {
        $this->scopes[$scope->getIdentifier()] = $scope->id;
    }


    /**
     * @param \DateTimeImmutable $dateTime
     */
    public function setExpiryDateTime(\DateTimeImmutable $dateTime): void
    {
        $this->expires = $dateTime->format('Y-m-d H:i:s');
    }

    /**
     * @param ClientEntityInterface|Client $client
     */
    public function setClient(ClientEntityInterface $client): void
    {
        $this->client = $client;
        $this->client_id = $client->id;
    }

    /**
     * @return DateTime
     * @throws \Exception
     */
    public function getExpiryDateTime(): DateTime
    {
        return new \DateTime($this->expires);
    }

    /**
     * @return ClientEntityInterface
     */
    public function getClient(): ClientEntityInterface
    {
        return $this->client;
    }

    /**
     * @param $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->token = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->token;
    }
}
