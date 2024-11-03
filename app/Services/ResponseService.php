<?php

namespace App\Services;

class ResponseService
{
    public $message=null;
    public $status_number=200;
    public $wrap="data";
    public $data=null;

    public function jsonResponse()
    {
        return response()->json([
            'message'=>$this->message,
            $this->wrap =>$this->data
        ],$this->status_number);
    }


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

}
