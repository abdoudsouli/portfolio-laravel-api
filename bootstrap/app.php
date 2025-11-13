<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckPassword;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
        'role' => CheckRole::class,
        'checkpassword' => CheckPassword::class,
    ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
        $response['statusCode'] = 403;
        $response['message'] = 'Unauthorized';
        return response()->json([
            'success' => false,
             'message' => "Unauthorized, you don't have permission to access this resource."
            ],403);
    });
    })->create();
