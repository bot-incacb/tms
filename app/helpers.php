<?php

use App\Enums\ErrCode;
use App\Enums\LangEnum;
use Google\Cloud\Translate\V2\TranslateClient;
use Illuminate\Contracts\Cache\Lock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * laravel-s 进程模式调试方法
 *
 * @param string $message 消息
 * @param array $context 扩展内容
 */
function s(string $message, array $context = [])
{
    Log::channel('stderr')->debug($message, $context);
}

/**
 * 记录sql的开始点
 */
function sql_start()
{
    DB::flushQueryLog();
    DB::enableQueryLog();
}

/**
 * 获取从开始点记录的所有sql
 */
function sql(): array
{
    return DB::getQueryLog();
}

/**
 * 添加表注释
 *
 * @param string $table_name 表名
 * @param string $comment 注释内容
 */
function table_comment(string $table_name, string $comment)
{
    DB::statement('ALTER TABLE `' . $table_name . '` comment "' . $comment . '"');
}

/**
 * 获取原子锁，用于防止并发
 *
 * 使用示例：
 *  ```php
 *  $lock = lock('customize_lock_key');
 *  if (!$lock->get()) {
 *      throw new DefaultException('操作频繁，请稍后再试');
 *  }
 *  # 业务逻辑代码...
 *  $lock->release(); // 释放锁
 *  ```
 *
 * @param string $key 原子锁字符串key
 * @param int $seconds 死锁时间（单位秒），需要大于逻辑执行时间。
 * @param mixed $owner
 * @return mixed
 */
function lock(string $key, int $seconds = 60, $owner = null): Lock
{
    return Cache::lock('lock:' . $key, $seconds, $owner);
}

/**
 * IP白名单
 *
 * @param string|string[] $ips
 */
function whitelist($ips)
{
    if (config('app.debug')) {
        return;
    }

    if (!is_array($ips)) {
        $ips = func_get_args();
    }

    if (!in_array(request()->ip(), $ips)) {
        abort(ErrCode::HTTP_AUTHORIZATION, 'Forbidden');
    }
}

/**
 * 获取翻译器实例
 *
 * @return TranslateClient
 */
function translator(): TranslateClient
{
    return new TranslateClient(['key' => config('services.google.translate_key')]);
}

/**
 * 谷歌翻译
 *
 * @param string $str
 * @param string $targetLang
 * @param string $sourceLang
 * @return string
 */
function google_translate(string $str, string $targetLang = '', string $sourceLang = ''): string
{
    $translate = translator();

    // 正则替换变量
    preg_match_all('/{[a-z_\d]*}/i', $str, $m);
    $isReplace = isset($m[0]) && $m[0];
    if ($isReplace) {
        $replaces = [];
        foreach ($m[0] as $k => $m0) {
            $replaces[] = '[' . ($k + 1) . ']';
        }
        $str = str_replace($m[0], $replaces, $str);
    }

    // 如果没有设定语言，则中英文自动翻译
    if (empty($targetLang)) {
        $detect = $translate->detectLanguage($str);
        if (($detect['languageCode'] ?? LangEnum::EN) === LangEnum::EN) {
            $targetLang = LangEnum::ZH_CN;
        } else {
            $targetLang = LangEnum::EN;
        }
    }

    // 翻译内容
    $options = ['target' => $targetLang];
    if ($sourceLang) {
        $options['source'] = $sourceLang;
    }
    $result = $translate->translate($str, $options);
    $str = $result['text'] ?? '';

    // 还原变量
    if ($isReplace) {
        $str = str_replace($replaces, $m[0], $str);
    }

    return $str;
}
