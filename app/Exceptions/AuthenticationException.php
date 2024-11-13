<?php
 
namespace App\Exceptions;
 
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException as DefaultAuthenticationException;
use App\Traits\ResponseTrait;
class AuthenticationException extends Exception
{
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
    public function render(DefaultAuthenticationException $e ,Request $request): Response|bool
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Record not found.'
                ], 404);
        }
        return false;
    }
}