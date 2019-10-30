<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class Session
 * @property string key
 * @property string ip
 * @property string user_agent
 * @property array session
 * @property bool   remember

 * @package App\Models
 */
final class Session extends Model
{

    public $incrementing = false;

    protected $casts = [
        'remember' => 'boolean'
    ];

    protected $primaryKey = 'key';

    public function getSessionAttribute($value): array
    {
        return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
    }

    public function setSessionAttribute($value): void
    {
        $this->attributes['session'] = json_encode($value, JSON_THROW_ON_ERROR, 512);
    }
}
