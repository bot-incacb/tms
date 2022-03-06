<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class PublicController extends Controller
{
    /**
     * 健康检查
     *
     * @return Application|ResponseFactory|Response|string
     */
    public function healthy()
    {
        // 检查redis
        try {
            $redis = Redis::connection();
            $redis->disconnect();
        } catch (Throwable $e) {
            Log::error('redis connection error', config('database.redis'));
            return response('redis connection error', 500);
        }

        // 检查mysql
        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            Log::error('mysql connection error', config('database.connections.mysql'));
            return response('mysql connection error', 500);
        }

        return response('ok');
    }

    /**
     * 服务信息调试
     *
     * @param Request $request
     * @return array
     */
    public function serverInfo(Request $request): array
    {
        Log::info('debug-server', [
            'time' => now()->toDateTimeString(),
            'ip' => $request->ip(),
            'clientIp' => $request->getClientIp(),
            'clientIps' => $request->getClientIps(),
            'server' => $_SERVER,
        ]);

        if (!config('app.debug')) {
            throw new NotFoundHttpException;
        }

        return [
            'time' => now()->toDateTimeString(),
            'ip' => $request->ip(),
            'clientIp' => $request->getClientIp(),
            'clientIps' => $request->getClientIps(),
            'server' => $_SERVER,
        ];
    }

    public function test()
    {
    }
}
