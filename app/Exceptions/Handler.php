<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return $this->renderJson($request, $e);
    }

    public function renderJson($request, Exception $e)
    {
        $rendered = parent::render($request, $e);
        $success = false;
        $status = $rendered->getStatusCode();
        $data = [];
        
        if ($status === 422) {
            $data['data'] = $e->errors();
        }

        if ($this->isDebugMode()) {
            $data['debug'] = [
                'exception' => get_class($e),
                'line' => $e->getLine(),
                'file' => basename( $e->getFile() )
            ];
        }

        return response()->json(array_merge([
            'success' => $success,
            'response_code' => $status,
            'message' => $e->getMessage()
        ], $data), $status);
    }

    /**
     * Determine if the application is in debug mode.
     *
     * @return Boolean
     */
    public function isDebugMode()
    {
        return (boolean) env('APP_DEBUG');
    }
}
