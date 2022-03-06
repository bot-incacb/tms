<?php

namespace App\Exceptions;

use App\Enums\ErrCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CException extends HttpException
{
    public function __construct($code, string $message = '')
    {
        if (is_string($code) && $code) {
            $message = $code;
            $code = ErrCode::DEFAULT;
        }

        // 获取消息
        if (!$message && ErrCode::hasValue($code)) {
            $message = ErrCode::getDescription($code);
        }
        parent::__construct($code, $message);
    }
}
