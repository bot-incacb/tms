<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Anchor extends Model
{
    public function entries(): BelongsToMany
    {
        return $this->belongsToMany(Entry::class);
    }
}
