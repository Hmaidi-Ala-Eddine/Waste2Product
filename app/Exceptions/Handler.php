<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle 404 errors (Model not found, Route not found)
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            // If it's an API request, return JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'error' => 'The requested resource could not be found.'
                ], 404);
            }
            
            // For web requests, show 404 page
            return response()->view('front.pages.404', [], 404);
        }

        // Handle authentication errors
        if ($e instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'error' => 'You must be logged in to access this resource.'
                ], 401);
            }
            
            // Redirect to appropriate login page
            if ($request->is('admin/*')) {
                return redirect()->route('admin.sign-in')->with('error', 'Please login to continue');
            }
            
            return redirect()->route('front.login')->with('error', 'Please login to continue');
        }

        // Let parent handler handle all other exceptions
        return parent::render($request, $e);
    }
}
