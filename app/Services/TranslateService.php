<?php

namespace App\Services;

use App\Enums\LangEnum;
use App\Enums\QualityEnum;
use Illuminate\Support\Collection;

class TranslateService
{
    /**
     * 根据一种语言使用谷歌翻译其他多种语言
     *
     * @param string $lang
     * @param string $content
     * @return Collection
     */
    public function autoGoogleTranslate(string $lang, string $content): Collection
    {
        $data = collect();
        $data->push([
            'lang' => $lang,
            'unpublished_content' => $content,
            'published_content' => $content,
            'quality' => QualityEnum::CALIBRATED,
            'is_original' => true,
        ]);

        $langs = array_filter(LangEnum::getValues(), function ($v) use ($lang) {
            return $v !== $lang;
        });
        foreach ($langs as $targetLang) {
            $content = google_translate($content, $targetLang, $lang);
            $data->push([
                'lang' => $targetLang,
                'unpublished_content' => $content,
                'quality' => QualityEnum::MACHINE,
                'is_original' => false,
            ]);
        }

        return $data;
    }
}
