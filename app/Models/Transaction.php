<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static latest()
 * @method static where(string $string, string $string1)
 * @method static selectRaw(string $string)
 * @method static findOrFail(string $id)
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_id',
        'amount',
        'bank_name',
        'account_number',
        'trans_type',
        'receiver_name',
        'description',
        'acct_type',
        'trans_status'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
