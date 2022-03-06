<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 *
 */
abstract class BaseEnum extends Enum
{
    /**
     * @var array 枚举说明
     */
    public static array $descriptions = [];

    public static function getDescription($value): string
    {
        return static::$descriptions[$value] ?? parent::getDescription($value);
    }

    /**
     * 获取完整的 key value 数组
     *
     * @return array
     */
    public static function getKeyValue(): array
    {
        return static::$descriptions;
    }

    public static function getList(): array
    {
        $list = [];
        foreach (static::$descriptions as $k => $d) {
            $list[] = [
                'value' => (string)$k,
                'description' => $d,
            ];
        }

        return $list;
    }
}
