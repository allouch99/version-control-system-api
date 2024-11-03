<?php

namespace App\Services;

abstract class Service
{
    protected function arrayData($message = '', $data = [], $status = 200,$error = false,$wrap = 'data'): array
    {
        return [
            'message' => $message,
            $wrap => $data,
            'status' => $status,
            'error' => $error,
        ];
    }

}
