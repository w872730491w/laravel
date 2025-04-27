<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function success(int $code = 0, string $message = '获取成功', $response = null)
    {
        return response()->json(compact('code', 'message', 'response'));
    }
}
