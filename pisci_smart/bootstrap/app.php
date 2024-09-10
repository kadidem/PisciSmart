<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        header('Access-Control-Allow-Origin: localhost:3000');
        header("Access-Control-Allow-Methods", "GET,PUT,PATCH,POST,DELETE");
        header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
