<?php declare(strict_types = 1);

namespace App\Library\OAuth\Entities;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TokenScope
 *
 * @property int id
 * @property int token_id
 * @property int scope_id
 * @property Scope scope
 * @property AccessToken token
 *
 *
 * @package App\Library\OAuth\Entities
 */
class TokenScope extends Model
{
    /**
     * @return BelongsTo
     */
    public function scope(): BelongsTo
    {
        return $this->belongsTo(Scope::class, 'id', 'scope_id');
    }

    /**
     * @return BelongsTo
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(AccessToken::class, 'id', 'token_id');
    }
}
