<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator',
        'operational_definition',
        'end_year',
        'hnrda_id',
        'priority_id',
        'sdg_id',
        'strategic_pillar_id',
        'thematic_area_id',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function majorFinalOutputs(): HasMany
    {
        return $this->hasMany(MajorFinalOutput::class);
    }

    public function hnrda(): BelongsTo
    {
        return $this->belongsTo(Hnrda::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function sdg(): BelongsTo
    {
        return $this->belongsTo(Sdg::class);
    }

    public function strategicPillar(): BelongsTo
    {
        return $this->belongsTo(StrategicPillar::class);
    }

    public function thematicArea(): BelongsTo
    {
        return $this->belongsTo(ThematicArea::class);
    }

    public function indicatorStatus(): BelongsTo
    {
        return $this->belongsTo(IndicatorStatus::class);
    }
}
