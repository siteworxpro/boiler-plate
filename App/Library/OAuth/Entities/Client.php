<?php declare(strict_types = 1);

namespace App\Library\OAuth\Entities;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use League\OAuth2\Server\Entities\{ClientEntityInterface, Traits\ClientTrait, Traits\EntityTrait};

/**
 * Class Client
 *
 * @property int                    id
 * @property string                 client_id
 * @property string                 client_secret
 * @property string                 client_name
 * @property string                 rand_string
 * @property string                 grant_type
 *
 * @property Collection             scopes
 * @package App\Library\OAuth\Entities
 */
class Client extends Model implements ClientEntityInterface
{
    use EntityTrait;
    use ClientTrait;

    public function scopes(): HasManyThrough
    {
        return $this->hasManyThrough(
            Scope::class,
            ClientScope::class,
            'client_id',
            'id',
            'id',
            'scope_id'
        );
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->client_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->client_name;
    }
}
