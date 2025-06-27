<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $user
 * @property mixed $id
 * @property mixed $user_id
 * @property mixed $type
 * @property mixed $status
 * @method static where(string $string, string $string1)
 * @method static findOrFail($id)
 */
class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'date_of_birth',
        'id_proof_type',
        'id_front_proof_url',
        'id_back_proof_url',
        'country',
        'state',
        'city',
        'address',
        'address_proof_type',
        'address_front_proof_url',
        'address_back_proof_url',
        'status',
        'rejection_reason',
        'approved_at'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'approved_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
