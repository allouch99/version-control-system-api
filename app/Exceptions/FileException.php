<?php
 
namespace App\Exceptions;
 
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
 
class FileException extends Exception
{
    public function __construct($message,$code=400)
    { 
        parent::__construct($message,$code);
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        // ...
    }
 
    /**
     * Render the exception into an HTTP response.
     */
    public function render(Request $request)
    {
        return response()->json([ 
            'status' => 'error',
            'message' => $this->message,
            'data' => [] ], $this->code);
        
    }
}