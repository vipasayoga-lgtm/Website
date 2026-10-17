<?php

namespace App\Controllers\Api;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Notification;

final class NotificationController extends Controller
{
    public function index(Request $request): void
    {
        $this->success([
            'notifications' => Notification::forUser(Auth::id(), 8),
            'unread_count' => Notification::unreadCountForUser(Auth::id()),
        ]);
    }

    public function delete(Request $request, int $id): void
    {
        $notification = Notification::find($id);
        if (!$notification) {
            $this->json(['success' => false, 'error' => 'Notification not found.'], 404);
            return;
        }

        // Only admin is authorized to delete notifications
        if (!Auth::hasRole('admin')) {
            $this->json(['success' => false, 'error' => 'Only administrators can delete notifications.'], 403);
            return;
        }

        Notification::delete($id);

        $this->success(['message' => 'Notification deleted successfully.']);
    }
}
