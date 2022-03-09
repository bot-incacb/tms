<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translate extends Model
{
    protected $hidden = [
        'entry_id',
    ];

    protected $casts = [
        'quality' => 'integer',
        'is_original' => 'boolean',
    ];

    protected $appends = [
        'is_published',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    /**
     * @return bool
     */
    public function getIsPublishedAttribute(): bool
    {
        return empty($this->attributes['unpublished_content']);
    }
}
