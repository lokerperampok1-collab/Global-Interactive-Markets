<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'id_front_path',
        'id_back_path',
        'selfie_path',
        'status',
        'note',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
