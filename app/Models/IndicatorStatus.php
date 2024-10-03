<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndicatorStatus extends Model
{
    use HasFactory;

    protected $table = 'indicator_statuses';

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }

    public function indicatorsGroups(): HasMany
    {
        return $this->hasMany(IndicatorsGroup::class);
    }
}
