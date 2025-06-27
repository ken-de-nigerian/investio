<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 * @method static findOrFail(mixed $investment_id)
 */
class UserInvestment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'amount',
        'expected_profit',
        'start_date',
        'end_date',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'expected_profit' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the user that owns the investment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan associated with the investment.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * @return HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    /**
     * Get investment completion percentage (0-100)
     */
    public function getCompletionPercentageAttribute(): float
    {
        if ($this->status == 'completed' ||
            $this->status == 'liquidated' ||
            $this->status == 'cancelled'
        ){
            return 100;
        }

        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());

        if ($totalDays <= 0) return 100;

        $percentage = ($elapsedDays / $totalDays) * 100;
        return min(100, max(0, round($percentage, 2)));
    }

    /**
     * Get remaining time in human readable format
     */
    public function getRemainingTimeAttribute(): string
    {
        if (now() >= $this->end_date) {
            return 'Completed';
        }

        $interval = now()->diff($this->end_date);

        if ($interval->days > 30) {
            return $interval->m > 0
                ? $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ' . $interval->d . ' day' . ($interval->d > 1 ? 's' : '')
                : $interval->d . ' day' . ($interval->d > 1 ? 's' : '');
        }

        return $interval->format('%a days %h hours');
    }

    /**
     * Calculate Compound Annual Growth Rate (CAGR)
     */
    public function getCagrAttribute(): float
    {
        $years = $this->start_date->diffInDays($this->end_date) / 365;
        if ($years <= 0) return 0;

        $totalReturn = ($this->expected_profit / $this->amount) * 100;
        $cagr = (pow(1 + ($totalReturn/100), 1/$years) - 1) * 100;
        return round($cagr, 1);
    }
}
