<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property mixed $role
 * @property mixed $profile
 * @property mixed $avatar
 * @property mixed $first_name
 * @property mixed $last_name
 * @property mixed $social_login_provider
 * @property mixed $balance
 * @property mixed $kyc
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return HasOne|User
     */
    public function profile(): HasOne|User
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * @return BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_by');
    }

    /**
     * @return HasMany
     */
    public function referrals()
    {
        return $this->HasMany(User::class, 'ref_by');
    }

    /**
     * @return HasOne|Kyc
     */
    public function kyc(): HasOne|Kyc
    {
        return $this->hasOne(Kyc::class);
    }

    /**
     * @return HasMany
     */
    public function deposit(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return HasMany
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * @return HasMany
     */
    public function interbank_transfers(): HasMany
    {
        return $this->hasMany(InterBankTransfer::class);
    }

    /**
     * @return HasMany
     */
    public function domestic_transfers(): HasMany
    {
        return $this->hasMany(DomesticTransfer::class);
    }

    /**
     * @return HasMany
     */
    public function wire_transfers(): HasMany
    {
        return $this->hasMany(WireTransfer::class);
    }

    /**
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * @return User|HasMany
     */
    public function loans(): User|HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * @return HasMany
     */
    public function investments(): HasMany
    {
        return $this->hasMany(UserInvestment::class);
    }
}
