<?php

namespace App\Enums;

final class ErrCode extends BaseEnum
{
    /*
    |--------------------------------------------------------------------------
    | 全局错误码
    |--------------------------------------------------------------------------
    */
    /** 认证失败 */
    const UNAUTHORIZED = 401;

    /** 密码已过期 */
    const PASSWORD_EXPIRED = 402;

    /** 没有权限 */
    const HTTP_AUTHORIZATION = 403;

    /** 路由错误 */
    const HTTP_NOT_FOUND = 404;

    /** 请求方式错误 */
    const METHOD_NOT_ALLOWED = 405;

    /** 数据不存在 */
    const DATA_NOT_FOUND = 410;

    /** 字段验证失败 */
    const VALIDATION_FAILED = 422;

    /*
    |--------------------------------------------------------------------------
    | 自定义错误码
    |--------------------------------------------------------------------------
    |
    | 没有特殊情况直接使用默认code，然后自定义消息，例如：
    |    throw new CException('您有一个自定义错误消息')
    | 如果需要前端单独处理时，再按顺序增加自定义错误码。
    | 自定义错误码常量以ERROR_开头，值必须在540~599之间，不能重复。
    |
    */
    /** 业务默认错误码 */
    const DEFAULT = 510;

    public static array $descriptions = [
        self::UNAUTHORIZED => '认证失败',
        self::PASSWORD_EXPIRED => '密码已过期',
        self::HTTP_AUTHORIZATION => '没有权限',
        self::HTTP_NOT_FOUND => '路由错误',
        self::METHOD_NOT_ALLOWED => '请求方式错误',
        self::DATA_NOT_FOUND => '数据不存在',
        self::VALIDATION_FAILED => '字段验证失败',
        self::DEFAULT => '业务异常，服务端没有响应。',
    ];
}
