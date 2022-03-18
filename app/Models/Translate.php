<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translate extends Model
{
    protected $hidden = [
        'entry_id',
        'unpublished_content',
        'published_content',
    ];

    protected $casts = [
        'quality' => 'integer',
        'is_original' => 'boolean',
    ];

    protected $appends = [
        'is_unpublished',
        'content',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    /**
     * @return bool
     */
    public function getIsUnpublishedAttribute(): bool
    {
        return !empty($this->attributes['unpublished_content']);
    }

    public function getContentAttribute(): string
    {
        if (!empty($this->attributes['unpublished_content'])) {
            return $this->attributes['unpublished_content'];
        }

        return $this->attributes['published_content'] ?? '';
    }
}
