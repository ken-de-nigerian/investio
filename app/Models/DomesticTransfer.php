<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static latest()
 * @method static where(string $string, mixed $id)
 * @property mixed $id
 * @property mixed $amount
 */
class DomesticTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_id',
        'amount',
        'bank_name',
        'acct_name',
        'account_number',
        'trans_type',
        'acct_remarks',
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
