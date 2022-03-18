<?php

namespace App\Http\Controllers;

use App\Enums\ErrCode;
use App\Exceptions\CException;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * 登录
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array
    {
        $credentials = $request->only(['username', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            throw new CException(ErrCode::UNAUTHORIZED, '登录失败，用户名或密码错误！');
        }

        return $this->formatToken($token);
    }

    /**
     * 获取个人信息
     *
     */
    public function me(): ?Authenticatable
    {
        return auth()->user();
    }

    /**
     * 退出
     *
     */
    public function logout()
    {
        auth()->logout();
    }

    /**
     * 刷新
     *
     */
    public function refresh(): array
    {
        return $this->formatToken(auth()->refresh());
    }

    /**
     * 格式化token
     *
     * @param string $token
     *
     * @return array
     */
    protected function formatToken(string $token): array
    {
        /** @var JWTAuth $auth */
        $auth = auth();
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expired_at' => now()->addSeconds($auth->factory()->getTTL() * 60)->timestamp,
        ];
    }

    /**
     * 修改密码
     *
     * @param ChangePasswordRequest $request
     * @return void
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        Auth::user()->update(['password' => $request->input('password')]);
    }
}
