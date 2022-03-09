<?php

namespace App\Http\Controllers;

use App\Enums\QualityEnum;
use App\Exceptions\CException;
use App\Http\Requests\TranslateSaveRequest;
use App\Models\Translate;

class TranslateController extends Controller
{
    public function update(TranslateSaveRequest $request, Translate $translate)
    {
        $translate->unpublished_content = $request->input('content');
        if ($translate->quality === QualityEnum::MACHINE) {
            $translate->quality = QualityEnum::ARTIFICIAL;
        }
        $translate->save();
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

    /**
     * 发布
     *
     * @param Translate $translate
     * @return void
     */
    public function publish(Translate $translate)
    {
        if (empty($translate->unpublished_content)) {
            throw new CException('没有需要发布的内容');
        }

        $translate->published_content = $translate->unpublished_content;
        $translate->unpublished_content = '';
        $translate->save();
    }

    /**
     * 撤销
     *
     * @param Translate $translate
     * @return void
     */
    public function revoke(Translate $translate)
    {
        if (empty($translate->unpublished_content)) {
            throw new CException('没有需要撤销的内容');
        }

        $translate->unpublished_content = '';
        $translate->save();
    }
}
