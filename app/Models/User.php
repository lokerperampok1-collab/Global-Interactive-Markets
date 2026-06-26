<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bank_name',
        'bank_account',
        'swift_code',
        'bank_locked_at',
        'status_kyc',
        'is_disabled',
        'disabled_at',
        'is_withdraw_unlocked',
        'country_code',
        'country_name',
        'currency_code',
        'currency_symbol',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'bank_locked_at' => 'datetime',
            'disabled_at' => 'datetime',
            'is_disabled' => 'boolean',
            'is_withdraw_unlocked' => 'boolean',
        ];
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function kycRequest(): HasOne
    {
        return $this->hasOne(KycRequest::class);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(UserInvestment::class);
    }

    public function getMaskedBankAccountAttribute(): string
    {
        if (!$this->bank_account) {
            return '';
        }
        $len = strlen($this->bank_account);
        if ($len <= 4) {
            return $this->bank_account;
        }
        return str_repeat('*', $len - 4) . substr($this->bank_account, -4);
    }
}
