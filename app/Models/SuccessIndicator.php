<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuccessIndicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'target',
        'accomplished',
    ];

    public function majorFinalOutput(): BelongsTo
    {
        return $this->belongsTo(MajorFinalOutput::class);
    }
}
