<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;

abstract class Controller
{
    protected function response(int $code = 0, string $message = '获取成功', $response = null)
    {
        return Response::success($code, $message, $response);
    }
}
