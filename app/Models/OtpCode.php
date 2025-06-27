<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static updateOrCreate(array $array, array $array1)
 * @method static where(string $string, mixed $id)
 */
class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime'
        ];
    }
}
