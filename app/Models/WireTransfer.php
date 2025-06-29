<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $id
 * @property mixed $amount
 * @method static create(array $array)
 * @method static latest()
 * @method static where(string $string, mixed $id)
 */
class WireTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_id',
        'acct_name',
        'account_number',
        'bank_name',
        'amount',
        'acct_remarks',
        'acct_type',
        'acct_country',
        'acct_swift',
        'acct_routing',
        'trans_type',
        'trans_status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
