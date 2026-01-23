<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\EnsureAdminPort::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\UpdateUserActivity::class,
            \App\Http\Middleware\EnsureUserIsActive::class,
        ])->alias([
                    'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
                ]);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin', 'admin/*') || $request->getPort() == 8001) {
                return route('admin.login');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->respond(function (\Illuminate\Http\Response $response) {
            if ($response->getStatusCode() === 419) {
                return back()->with([
                    'message' => 'The page expired, please try again.',
                ]);
            }
            return $response;
        });

        // Custom Inertia Error Page
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            $response = new \Symfony\Component\HttpFoundation\Response();

            // If it's an HttpException (404, 403, 500 etc)
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                $status = $e->getStatusCode();
            } else {
                // If it's a generic exception, treat as 500
                $status = 500;
            }

            // Only hijack Inertia requests
            if ($request->inertia()) {
                return \Inertia\Inertia::render('Error', [
                    'status' => $status,
                    'message' => $e->getMessage(),
                ])->toResponse($request)->setStatusCode($status);
            }

            return null; // Fallback to default Laravel response if not Inertia
        });
    })->create();
