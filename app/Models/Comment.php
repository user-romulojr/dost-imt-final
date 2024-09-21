<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'user_id',
    ];

    public function majorFinalOutput(): BelongsTo
    {
        return $this->belongsTo(MajorFinalOutput::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
