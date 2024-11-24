<?php

namespace App\Services;

use App\Traits\ValidatorTrait;
use App\Services\ResponseService;

abstract class Service
{
    use ValidatorTrait;
   
    protected $responseService;
    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

}
