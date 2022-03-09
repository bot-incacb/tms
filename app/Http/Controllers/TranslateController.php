<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslateSaveRequest;
use App\Models\Translate;

class TranslateController extends Controller
{
    public function update(TranslateSaveRequest $request, Translate $translate)
    {
        //
    }

    /**
     * 校准
     *
     * @param Translate $translate
     * @return void
     */
    public function calibrate(Translate $translate)
    {

    }

    /**
     * 发布
     *
     * @param Translate $translate
     * @return void
     */
    public function publish(Translate $translate)
    {
    }

    /**
     * 取消发布
     *
     * @param Translate $translate
     * @return void
     */
    public function cancelPublish(Translate $translate)
    {

    }
}
