<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $validated)
 * @method static where(string $string, string $string1)
 * @property mixed $id
 * @property mixed $loan_amount
 * @property mixed $monthly_emi
 * @property mixed $total_payment
 * @property mixed $status
 * @property mixed $paid_emi
 * @property Carbon|mixed $completed_at
 * @property mixed|null $next_due_date
 * @property mixed $created_at
 * @property mixed $tenure_months
 */
class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'loan_amount', 'interest_rate', 'tenure_months',
        'monthly_emi', 'total_interest', 'total_payment', 'loan_reason',
        'loan_collateral', 'paid_emi', 'status', 'approved_at', 'disbursed_at',
        'completed_at', 'next_due_date', 'loan_end_date', 'remarks'
    ];

    protected $casts = [
        'tenure_months' => 'integer',
        'loan_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'monthly_emi' => 'decimal:2',
        'total_interest' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'approved_at' => 'datetime',
        'disbursed_at' => 'datetime',
        'completed_at' => 'datetime',
        'next_due_date' => 'date',
        'loan_end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
