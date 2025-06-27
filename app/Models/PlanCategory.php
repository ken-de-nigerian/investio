<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @method static create(string[] $category)
 * @method static where(string $string, string $string1)
 */
class PlanCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'risk_level',
        'liquidity',
        'is_active'
    ];

    /**
     * Get the plans for this category.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function getTotalInvestedAttribute()
    {
        return $this->investments()->where('user_id', Auth::id())->sum('amount') / 1000;
    }

    public function getPercentageAttribute()
    {
        $total = UserInvestment::where('user_id', Auth::id())->sum('amount') / 1000;
        return $total > 0 ? ($this->total_invested / $total) * 100 : 0;
    }

    public function investments()
    {
        return $this->hasManyThrough(UserInvestment::class, Plan::class, 'plan_category_id', 'plan_id');
    }
}
