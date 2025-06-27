<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $planData)
 * @method static findOrFail(mixed $plan_id)
 */
class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_category_id',
        'name',
        'slug',
        'description',
        'min_amount',
        'interest_rate',
        'duration_days',
        'is_active'
    ];

    /**
     * Get formatted duration display
     */
    public function getDurationDisplayAttribute(): string
    {
        $duration = $this->duration_days;

        if ($duration >= 365) {
            $years = floor($duration / 365);
            return $years . ' Year' . ($years > 1 ? 's' : '');
        }

        if ($duration >= 30) {
            $months = floor($duration / 30);
            return $months . ' Month' . ($months > 1 ? 's' : '');
        }

        return $duration . ' Day' . ($duration > 1 ? 's' : '');
    }

    /**
     * Get formatted returns period display
     */
    public function getReturnsPeriodAttribute(): string
    {
        return match ($this->category->liquidity) {
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'term' => 'At Maturity',
            default => ucfirst($this->category->liquidity),
        };
    }

    /**
     * Calculate projected return based on compounding frequency
     */
    public function getProjectedReturnAttribute(): float
    {
        $principal = $this->min_amount;
        $annualRate = $this->interest_rate / 100;
        $days = $this->duration_days;

        switch($this->category->liquidity) {
            case 'daily':
                // Daily compounding (365 periods/year)
                return $principal * pow(1 + ($annualRate/365), $days);

            case 'weekly':
                // Weekly compounding (exact weeks)
                $weeks = $days / 7;
                return $principal * pow(1 + ($annualRate/52), $weeks);

            case 'monthly':
                // Monthly compounding (exact months)
                $months = $days / (365/12); // Average 30.416 days/month
                return $principal * pow(1 + ($annualRate/12), $months);

            case 'term':
                // Simple interest (no compounding)
                return $principal * (1 + $annualRate * ($days/365));

            default:
                // Annual compounding
                return $principal * pow(1 + $annualRate, $days/365);
        }
    }

    /**
     * Get the category that owns the plan.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PlanCategory::class, 'plan_category_id');
    }

    /**
     * @return HasMany
     */
    public function investments(): HasMany
    {
        return $this->hasMany(UserInvestment::class);
    }
}
