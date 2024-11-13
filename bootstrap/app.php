<?php

use App\Http\Middleware\AuthMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->is("api/*")) {

                // HttpException (termasuk 404, 500, dll.)
                if ($e instanceof HttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        'errors' => null
                    ], $e->getStatusCode());
                }

                // UnauthorizedException (pengguna tidak terautentikasi)
                if ($e instanceof UnauthorizedException) {
                    return response()->json([
                        'success' => false,
                        'message' => "Unauthorized, please login to continue",
                        'errors' => null
                    ], 401);
                }

                // NotFoundHttpException (rute tidak ditemukan)
                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => "Route not found",
                        'errors' => null
                    ], 404);
                }

                // AuthenticationException (pengguna tidak terautentikasi)
                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'message' => "Unauthenticated, please relogin to continue",
                        'errors' => null
                    ], 401);
                }

                // ValidationException (kesalahan validasi input)
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => "Validation failed",
                        'errors' => $e->errors()
                    ], 422);
                }

                // ModelNotFoundException (model tidak ditemukan)
                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'success' => false,
                        'message' => "Model not found",
                        'errors' => null
                    ], 404);
                }

                // QueryException (kesalahan SQL atau query)
                if ($e instanceof QueryException) {
                    return response()->json([
                        'success' => false,
                        'message' => "Database query error",
                        'errors' => null
                    ], 500);
                }

                // MethodNotAllowedHttpException (method HTTP tidak diizinkan)
                if ($e instanceof MethodNotAllowedHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => "Method not allowed for this route",
                        'errors' => null
                    ], 405);
                }

                // ThrottledRequestException (rate limiting / throttling)
                if ($e instanceof RateLimited) {
                    return response()->json([
                        'success' => false,
                        'message' => "Too many requests. Please try again later.",
                        'errors' => null
                    ], 429);
                }

                // Jika pengecualian lainnya
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: "An error occurred",
                    'errors' => null
                ], 500);
            }

            // Jika bukan request API, biarkan pengecualian default ditangani
            return null;
        });
    })

    ->create();
