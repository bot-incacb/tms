<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\App;

class Entry extends Model
{
    protected $casts = [
        'has_unpublished' => 'boolean',
    ];

    /**
     * 获取当前语言翻译
     *
     * @return HasOne
     */
    public function currentTranslate(): HasOne
    {
        return $this->hasOne(Translate::class)
            ->where('lang', App::currentLocale());
    }

    public function translates(): HasMany
    {
        return $this->hasMany(Translate::class)->orderByDesc('is_original');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
