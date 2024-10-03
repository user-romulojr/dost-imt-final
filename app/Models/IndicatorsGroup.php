<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndicatorsGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_id',
        'indicator_status_id',
        'is_approved',
    ];

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function indicatorStatus(): BelongsTo
    {
        return $this->belongsTo(IndicatorStatus::class);
    }
}
