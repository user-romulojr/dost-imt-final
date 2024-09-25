<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    protected $table = 'agencies';

    protected $fillable = [
        'agency',
        'acronym',
        'agency_group_id',
        'contact',
        'website',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function agencyGroup(): BelongsTo
    {
        return $this->belongsTo(AgencyGroup::class);
    }
}
