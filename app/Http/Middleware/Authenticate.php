<?php

namespace App\Http\Middleware;

use App\Enums\ErrCode;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\JWTAuth;

class Authenticate extends Middleware
{
}
