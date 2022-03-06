<?php

namespace App\Exceptions;

use App\Enums\ErrCode;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use RedisException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
    }

    public function report(Throwable $e): void
    {
        //remove stack trace
        if ($e instanceof TokenBlacklistedException) {
            return;
        }
        parent::report($e);
    }

    public function render($request, Throwable $e)
    {
        /** @var Response $response */
        $response = parent::render($request, $e);
        $msg = $e->getMessage();
        if (!$msg) {
            $msg = ErrCode::getDescription($response->getStatusCode());
        }

        $result = [
            'code' => $response->getStatusCode(),
            'msg' => $msg,
            'data' => (object)null,
        ];

        switch (true) {
            // 字段验证异常
            case $e instanceof ValidationException:
                /** @var ValidationException $e */
                $errors = $e->errors();
                $error = reset($errors);
                if ($error) {
                    $result['msg'] = $error[0];
                }
                break;

            // 模型不存在异常
            case $e instanceof ModelNotFoundException:
                $result['code'] = ErrCode::DATA_NOT_FOUND;
                $result['msg'] = ErrCode::getDescription(ErrCode::DATA_NOT_FOUND);
                break;

            // 业务异常
            case $e instanceof CException:
                $msg = $e->getMessage();
                if ($msg) {
                    $result['msg'] = $msg;
                }
                break;

            case $e instanceof RedisException:
                return response('redis connection error', 500);

            case $e instanceof AuthenticationException:
            case $e instanceof JWTException:
                $result['code'] = ErrCode::UNAUTHORIZED;
                $result['msg'] = ErrCode::getDescription(ErrCode::UNAUTHORIZED);
                break;

            case $e instanceof NotFoundHttpException:
            case $e instanceof MethodNotAllowedHttpException:
            case $e instanceof HttpException:
                break;

            default:
                if (config('app.debug')) {
                    $result['trace'] = $e->getTrace();
                    $msg = $e->getMessage();
                    if ($msg) {
                        $result['msg'] = $e->getMessage();
                    }
                }
                break;
        }

        return response()->json($result);
    }
}
