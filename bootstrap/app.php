<?php

use Illuminate\Foundation\Application;
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
        // هون بتحطي الاسم المستعار يلي رح تستخدميه بالراوت
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'professor' => \App\Http\Middleware\ProfessorMiddleware::class,
        'student' => \App\Http\Middleware\StudentMiddleware::class,
        'adminOrProfessor' => \App\Http\Middleware\AdminOrProfessorMiddleware::class,
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
