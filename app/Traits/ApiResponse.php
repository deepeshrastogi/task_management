<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ApiResponse
{
    protected function success($content, $status = 200, $message='',)
    {
        return response(['error' => [], 'content' => $content, 'message' => $message], $status);
    }

    protected function error($error, $status = 200)
    {
        return response(['error' => $error, 'content' => [], 'message' => ''], $status);
    }

    protected function loginUser(Request $request){
        return $request->user();
    }
}
