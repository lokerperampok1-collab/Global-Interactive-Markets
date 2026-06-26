<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInvestment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'plan_name',
        'amount',
        'target_return',
        'duration_days',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'target_return' => 'decimal:2',
        'duration_days' => 'integer',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(InvestmentPlan::class, 'plan_id');
    }
}
