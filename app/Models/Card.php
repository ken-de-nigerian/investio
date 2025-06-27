<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, int|string|null $id)
 * @property mixed $user
 */
class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_balance',
        'serial_key',
        'card_number',
        'card_name',
        'card_expiration',
        'card_security',
        'card_type',
        'card_status'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
