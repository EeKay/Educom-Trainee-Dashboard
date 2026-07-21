<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usage extends Model
{
    protected $table = 'Usage';
    protected $fillable = ['date', 'model', 'spend', 'tokens'];

    public function actual(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
