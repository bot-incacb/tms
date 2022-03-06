<?php

namespace App\Enums;

class QualityEnum extends BaseEnum
{
    /** @var int 机翻 */
    const MACHINE = 1;

    /** @var int 人工 */
    const ARTIFICIAL = 2;

    /** @var int 已校准 */
    const CALIBRATED = 3;

    public static array $descriptions = [
        self::MACHINE => '机翻',
        self::ARTIFICIAL => '人工',
        self::CALIBRATED => '已校准',
    ];
}
