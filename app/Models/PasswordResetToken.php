<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static updateOrCreate(array $array, array $array1)
 * @method static where(string $string, mixed $email)
 */
class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $fillable = ['email', 'token', 'created_at'];
    public $timestamps = false;
    protected array $dates = ['created_at'];
}
