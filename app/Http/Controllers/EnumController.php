<?php

namespace App\Http\Controllers;

use App\Enums\LangEnum;
use App\Enums\QualityEnum;

class EnumController extends Controller
{
    public function langs(): array
    {
        return [
            'items' => LangEnum::getList(),
        ];
    }

    public function qualities(): array
    {
        return [
            'items' => QualityEnum::getList(),
        ];
    }
}
