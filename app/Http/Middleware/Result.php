<?php

namespace App\Http\Middleware;

use App\Enums\LangEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class Result
{
    public function handle(Request $request, Closure $next)
    {
        // 获取语言
        $lang = $request->header('x-lang');
        if ($lang && LangEnum::hasValue($lang)) {
            App::setLocale($lang);
        } else {
            App::setLocale(config('app.locale'));
        }

        // 强制请求为json类型
        $request->headers->set('Accept', 'application/json');

        /** @var Response $response */
        $response = $next($request);

        // 过滤
        if ($this->filter($request, $response)) {
            return $response;
        }

        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => (object) json_decode($response->getContent()),
        ];

        return response()->json($result);
    }

    /**
     * 过滤不需要处理的返回
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    private function filter(Request $request, Response $response): bool
    {
        if (property_exists($response, 'exception') && $response->exception) {
            return true;
        }

        if ($request->is(config('app.filter_urls.result'))) {
            return true;
        }

        if ($response instanceof BinaryFileResponse) {
            return true;
        }

        return false;
    }
}
