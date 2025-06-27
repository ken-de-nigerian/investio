<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method static where(string $string, int|string|null $id)
 * @method static whereNull(string $string)
 * @property mixed $current_amount
 * @property mixed|string $status
 * @property \Illuminate\Support\Carbon|mixed $completed_at
 * @property mixed $target_amount
 * @property mixed $title
 */
class Goal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'goal_category_id',
        'title',
        'description',
        'target_amount',
        'current_amount',
        'target_date',
        'start_date',
        'priority',
        'status',
        'is_public',
        'monthly_target',
        'milestones',
        'image_url',
        'completed_at',
        'deleted_at',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'monthly_target' => 'decimal:2',
        'target_date' => 'date',
        'start_date' => 'date',
        'is_public' => 'boolean',
        'milestones' => 'array',
        'completed_at' => 'datetime',
    ];

    protected array $dates = [
        'target_date',
        'created_at',
        'updated_at',
        'deleted_at',
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
    public function category(): BelongsTo
    {
        return $this->belongsTo(GoalCategory::class, 'goal_category_id');
    }

    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount == 0) {
            return 0;
        }

        return min(100, round(($this->current_amount / $this->target_amount) * 100, 2));
    }

    public function getDaysRemainingAttribute(): int
    {
        return Carbon::now()->diffInDays($this->target_date);
    }

    public function getContributionDayAttribute(): string
    {
        return date('d', strtotime($this->start_date));
    }

    public function getProgressOffsetAttribute(): float
    {
        return 282.783 - (282.783 * $this->progress_percentage / 100);
    }

    public function getIsCompleteAttribute(): bool
    {
        return $this->completed_at || $this->progress_percentage >= 100;
    }

    public function getIsOverdueAttribute(): bool
    {
        return !$this->is_complete && now()->gt($this->target_date);
    }
}
