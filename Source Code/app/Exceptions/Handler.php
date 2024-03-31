<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
         if($request->expectsJson()) {
             return response()->json([
                 "success" => false,
                 "status" => "error",
                 "error" => ["code" => 401,
                     "type" => "Unauthorized",
                     "message" => "Session expired please login again"
                 ],
             ], 401);
                }
           else {
               if ($request->is('admin/store*')) {
                   return redirect()->guest(route('store.login'));
               }
               return redirect()->guest(route('login'));
           }

    }
    
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
