<?php

namespace App\Traits;

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
}
