<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (!config('app.debug') && strpos($request->getUri(), '/api/v') !== false) {
            // create output
            $return = [
                'error' => [
                    'errorType' => (new \ReflectionClass($exception))->getShortName(),
                    'message' => $exception->getMessage(),
                ]
            ];

            // set default response: 400
            $status = 400;

            // is exception of instance of HttpException
            if ($this->isHttpException($exception)) {
                // get the HTTP status code
                $status = $exception->getStatusCode();
            }

            // return JSON with the output array and status code
            return response()->json($return, $status);
        }

        return parent::render($request, $exception);
    }
}
