<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, mixed $id)
 * @method static create(array $array)
 * @method static sum(string $string)
 */
class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_investment_id',
        'from_id',
        'to_id',
        'amount',
        'percent'
    ];

    /**
     * @return BelongsTo
     */
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * @return BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    /**
     * @return BelongsTo
     */
    public function investment(): BelongsTo
    {
        return $this->belongsTo(UserInvestment::class, 'user_investment_id');
    }
}
