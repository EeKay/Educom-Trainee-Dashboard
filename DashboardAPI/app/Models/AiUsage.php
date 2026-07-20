<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsage extends Model
{
    protected $table = 'AiUsage';
    protected $fillable = ['date', 'model', 'spend', 'tokens'];

    public function actual(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
