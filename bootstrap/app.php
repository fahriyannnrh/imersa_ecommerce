<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            return route('login');
        });

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && $user->isAdmin() && ($request->is('admin') || $request->is('admin/*'))) {
                return route('admin.dashboard');
            }
            return route('home');
        });

        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->is('admin/*') || $request->routeIs('admin.*')) {
                return redirect()->route('admin.login')->withErrors([
                    'email' => 'Sesi login telah kedaluwarsa. Silakan login kembali.',
                ]);
            }

            return redirect()->back()->withErrors([
                'error' => 'Sesi formulir telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.',
            ]);
        });
    })->create();
