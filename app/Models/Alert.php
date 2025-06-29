<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_name',
        'sender_bank',
        'amount',
        'date',
        'trans_type',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime',
        'trans_type' => 'string',
        'status' => 'string',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
