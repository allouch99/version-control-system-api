<?php


namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
trait ValidatorTrait
{
    protected function validator(Request $request,array $rule): array
    {
        return Validator::make($request->all(), $rule)->errors()->all();
    }
}
