<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\MiddlewareInterface;
use App\Core\Request;
use App\Core\Response;

final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $isInactive = ($user['status'] ?? '') === 'inactive' || ($user['status'] ?? '') === 'suspended';

            if (!$isInactive && ($user['role'] ?? '') === 'instructor') {
                $instructor = \App\Models\Instructor::findByUserId((int) $user['id']);
                if ($instructor && ($instructor['status'] ?? '') === 'inactive') {
                    $isInactive = true;
                }
            }

            if ($isInactive) {
                Auth::logout();
                if (str_starts_with($request->path(), '/api/')) {
                    Response::json(['success' => false, 'message' => 'Your account is inactive. Please contact administrator.'], 403);
                }
                Response::redirect('/login?error=account_inactive');
            }

            return;
        }

        if (str_starts_with($request->path(), '/api/')) {
            Response::json(['success' => false, 'message' => 'Authentication required.'], 401);
        }

        Response::redirect('/login?redirect=' . urlencode($request->fullPath()));
    }
}
