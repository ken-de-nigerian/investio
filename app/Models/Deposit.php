<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $id)
 * @property mixed $amount
 * @property mixed $id
 * @property mixed $user
 */
class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'payment_method',
        'amount',
        'converted_amount',
        'status',
        'description'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
