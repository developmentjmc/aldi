<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Otp extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'code',
        'expired_at',
        'is_used',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'is_used' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function isExpired(): bool
    {
        return $this->expired_at < now();
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }
}
