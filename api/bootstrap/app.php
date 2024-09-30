<?php

use App\Http\Middleware\Role;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => Role::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (PostTooLargeException $e) {
            return response()->json([
                'key' => 'POST_TOO_LARGE',
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (ModelNotFoundException $e) {
            return response()->json([
                'key' => 'RESOURCE_MISSING',
                'meta' => [
                    'type' => $e->getModel(),
                    'id' => $e->getIds(),
                ],
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        });

        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json([
                'key' => 'UNAUTHORIZED',
                'message' => $e->getMessage(),
            ]);
        });

        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'key' => 'ROUTE_NOT_DEFINED',
                'message' => $e->getMessage(),
            ]);
        });
    })->create();
