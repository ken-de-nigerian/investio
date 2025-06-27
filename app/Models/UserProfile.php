<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static updateOrCreate(array $array, array $data)
 * @method static where(string $string, mixed $referralCode)
 */
class UserProfile extends Model
{
    protected $table = 'user_profiles';
    protected $guarded = ['id'];

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
