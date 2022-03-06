<?php

namespace App\Enums;

class LangEnum extends BaseEnum
{
    /** @var string 英语 */
    const EN = 'en';

    /** @var string 中文（简体） */
    const ZH_CN = 'zh-CN';

    /** @var string 中文（繁体） */
    const ZH_TW = 'zh-TW';

    /** @var string 泰语 */
    const TH = 'th';

    /** @var string 越南语 */
    const VI = 'vi';

    public static array $descriptions = [
        self::EN => 'English',
        self::ZH_CN => '中文(简体)',
        self::ZH_TW => '中文（繁體）',
        self::TH => 'ไทย',
        self::VI => 'Tiếng Việt',
    ];
}
