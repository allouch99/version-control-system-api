<?php


namespace App\Traits;
use Illuminate\Http\JsonResponse;
trait ResponseTrait
{
    public $message='';
    public $status_number=200;
    public $wrap="data";
    public $data=[];
    public $error=false;


    public function message($message)
    {
        $this->message = $message;
        return $this;
    }
    public function status($status_number)
    {
        $this->status_number = $status_number;
        return $this;
    }
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }
    public function wrap($wrap)
    {
        $this->wrap = $wrap;
        return $this;
    }
    public function error($error)
    {
        $this->error = $error;
        return $this;
    }
    public function jsonResponse():JsonResponse
    {
        return response()->json([
           'status' => $this->error ? 'error' : 'success' ,
           'message' => $this->message,
           $this->wrap => $this->data ], $this->status_number);
    }

    public function successResponse(){
         return response()->json([
            'status' => $this->error ? 'error' : 'success' ,
            'message' => $this->message,
            $this->wrap => $this->data ], $this->status_number);
    }
    public function errorResponse($message, $data = [], $status = 400){
        return response()->json([ 
            'status' => 'error',
            'message' => $message,
            'data' => $data ], $status);
    }
}
