<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

// Ensure public_path() points to the project root for flat-root Hostinger deployment
if (file_exists(dirname(__DIR__) . '/index.php') && (!isset($_SERVER['SCRIPT_FILENAME']) || realpath($_SERVER['SCRIPT_FILENAME']) !== realpath(dirname(__DIR__) . '/public/index.php'))) {
    $app->usePublicPath(dirname(__DIR__));
}

// Ensure critical storage framework directories exist to prevent Blade InvalidArgumentException
$storageDirs = [
    dirname(__DIR__) . '/storage/framework/views',
    dirname(__DIR__) . '/storage/framework/cache/data',
    dirname(__DIR__) . '/storage/framework/sessions',
    dirname(__DIR__) . '/storage/logs',
];
foreach ($storageDirs as $storageDir) {
    if (!is_dir($storageDir)) {
        @mkdir($storageDir, 0755, true);
    }
}

return $app;
