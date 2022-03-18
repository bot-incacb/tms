<?php

namespace App\Services;

use App\Models\Entry;
use Illuminate\Support\Facades\DB;

class EntryService
{
    public function store(string $key, string $lang, string $content, array $tags)
    {
        // 获取翻译数据
        $translateData = (new TranslateService)->autoGoogleTranslate($lang, $content);

        // 获取标签
        $tagData = (new TagService)->getOrCreateByContent($tags);

        DB::transaction(function () use ($key, $translateData, $tagData) {
            $entry = Entry::create(compact('key'));
            $entry->translates()->createMany($translateData);
            $entry->tags()->sync($tagData);
        });
    }

    public function update(Entry $entry, string $key)
    {
        $entry->update(compact('key'));
    }

    public function storeTags(Entry $entry, array $tags)
    {
        // 获取标签
        $tagData = (new TagService)->getOrCreateByContent($tags);
        $entry->tags()->syncWithoutDetaching($tagData);
    }

    /**
     * 发布
     *
     * @param Entry $entry
     */
    public function publish(Entry $entry)
    {
    }

    /**
     * 撤销
     *
     * @param Entry $entry
     * @return void
     */
    public function revoke(Entry $entry)
    {

    }
}
