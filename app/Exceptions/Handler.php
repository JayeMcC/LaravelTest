<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array<int, class-string<Throwable>>
   */
  protected $dontReport = [
    // Add exceptions that should not be reported here.
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * Report or log an exception.
   */
  public function report(Throwable $exception): void
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   */
  public function render($request, Throwable $exception): Response
  {
    // If the request expects a JSON response (API requests)
    if ($request->expectsJson()) {
      return $this->handleApiException($request, $exception);
    }

    // For non-API requests, use the parent method for rendering the exception
    return parent::render($request, $exception);
  }


  /**
   * Handle exceptions for API requests.
   */
  protected function handleApiException($request, Throwable $exception)
  {
    $statusCode = $this->getStatusCode($exception);
    $errorMessage = $this->getErrorMessage($exception);

    return response()->json([
      'error' => $errorMessage,
    ], $statusCode);
  }

  /**
   * Get the appropriate status code based on the exception type.
   */
  protected function getStatusCode(Throwable $exception)
  {
    if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
      return 404; // Not Found
    }

    if ($exception instanceof AuthenticationException) {
      return 401; // Unauthorized
    }

    return 500; // Internal Server Error
  }

  /**
   * Get the appropriate error message based on the exception type.
   */
  protected function getErrorMessage(Throwable $exception)
  {
    if ($exception instanceof ModelNotFoundException) {
      return 'Resource not found.';
    }

    if ($exception instanceof AuthenticationException) {
      return 'Unauthorized.';
    }

    if ($exception instanceof NotFoundHttpException) {
      return 'Endpoint not found.';
    }

    return 'An unexpected error occurred.';
  }

  /**
   * Convert an authentication exception into an unauthenticated response.
   */
  protected function unauthenticated($request, AuthenticationException $exception)
  {
    if ($request->expectsJson()) {
      return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    return redirect()->guest(route('login'));
  }
}
