<?php

namespace App\Http\Controllers;

use App\Enums\ErrCode;
use App\Exceptions\CException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class OpenapiController extends Controller
{
    /**
     * 登录
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array
    {
        /** @var JWTAuth $auth */
        $auth = Auth::guard('openapi');

        $auth->factory()->setTTL(config('jwt.openapi_ttl'));

        if (!$token = $auth->attempt($request->only(['username', 'password']))) {
            throw new CException(ErrCode::UNAUTHORIZED, '授权失败，应用信息错误！');
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expired_at' => now()->addSeconds($auth->factory()->getTTL() * 60)->timestamp,
        ];
    }
}
