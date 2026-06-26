<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier',
        'name',
        'description',
        'price',
        'target_return',
        'duration_days',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'target_return' => 'decimal:2',
        'status' => 'boolean',
        'sort_order' => 'integer',
        'duration_days' => 'integer',
    ];

    public function userInvestments(): HasMany
    {
        return $this->hasMany(UserInvestment::class, 'plan_id');
    }
}
