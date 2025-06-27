<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $id)
 * @property mixed $user
 * @property mixed $id
 * @property mixed $amount
 * @property mixed $acct_name
 * @property mixed $transfer_id
 * @property mixed $account_number
 */
class InterBankTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_id',
        'transfer_id',
        'amount',
        'acct_name',
        'account_number',
        'trans_status'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
