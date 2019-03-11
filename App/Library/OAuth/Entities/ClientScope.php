<?php declare(strict_types = 1);

namespace App\Library\OAuth\Entities;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ClientScope
 *
 * @property int id
 * @property int client_id
 * @property int scope_id
 * @property Scope scope
 * @property Client client
 *
 * @package App\Library\OAuth\Entities
 */
class ClientScope extends Model
{

    /**
     * @return BelongsTo
     */
    public function scope(): BelongsTo
    {
        return $this->belongsTo(Scope::class, 'id', 'scope_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id', 'client_id');
    }
}
