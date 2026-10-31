<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;

final class GuestMiddleware implements MiddlewareInterface
{
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
            return;
        }

        if (str_starts_with($request->path(), '/api/')) {
            Response::json([
                'success' => false,
                'message' => 'You are already signed in. Please log out first if you want to create a new account.',
                'data' => [
                    'redirect' => Auth::redirectHome(),
                ],
            ], 409);
        }

        $redirect = $request->query('redirect');
        Response::redirect(Auth::isSafeRedirectPath($redirect) ? $redirect : Auth::redirectHome());
    }
}
