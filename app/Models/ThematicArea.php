<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThematicArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }
}
