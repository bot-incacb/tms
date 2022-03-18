<?php

namespace App\Http\Controllers;

use App\Enums\QualityEnum;
use App\Http\Requests\TranslateSaveRequest;
use App\Models\Translate;

class TranslateController extends Controller
{
    public function update(TranslateSaveRequest $request, Translate $translate)
    {
        if ($request->input('content') === $translate->published_content) {
            return;
        }

        $translate->unpublished_content = $request->input('content');
        if ($translate->quality === QualityEnum::MACHINE) {
            $translate->quality = QualityEnum::ARTIFICIAL;
        }
        $translate->save();

        $translate->entry->update(['has_unpublished' => true]);
    }

    /**
     * 校准
     *
     * @param Translate $translate
     * @return void
     */
    public function calibrate(Translate $translate)
    {
        $translate->quality = QualityEnum::CALIBRATED;
        $translate->save();
    }
}
