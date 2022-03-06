<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Collection;

class TagService
{
    /**
     * 根据标签内容列表获取标签实例集合，如果该标签不存在则创建
     *
     * @param array $contents
     * @return Collection
     */
    public function getOrCreateByContent(array $contents): Collection
    {
        $data = Tag::whereIn('content', $contents)->get();
        $hasTags = $data->pluck('content');
        $newTags = array_diff($contents, $hasTags->toArray());

        foreach ($newTags as $newTag) {
            $data->push(Tag::create(['content' => $newTag]));
        }

        return $data;
    }
}
