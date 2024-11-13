<?php

namespace App\Services;

use App\Traits\ValidatorTrait;
use App\Traits\ResponseTrait;

abstract class Service
{
    use ValidatorTrait,ResponseTrait;
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
