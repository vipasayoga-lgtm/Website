<?php

namespace App\Controllers\Api;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\ClientProfile;
use App\Models\Consent;

final class ConsentController extends Controller
{
    /**
     * Handles both Stage 5's one-time general consent (no `booking_ref`)
     * and the per-booking "before Join Session" consent (`booking_ref`
     * present) — the latter is checked ownership-first so a client can
     * never record consent against someone else's booking.
     */
    public function store(Request $request): void
    {
        $accepted = (bool) $request->input('accepted', false);

        if (!$accepted) {
            $this->fail('Please confirm all three statements to continue.', 422);
        }

        $userId = Auth::id();
        $bookingRef = (string) $request->input('booking_ref', '');
        $bookingId = null;

        if ($bookingRef !== '') {
            $booking = Booking::findByRef($bookingRef);
            if (!$booking || (int) $booking['client_id'] !== $userId) {
                $this->fail('Booking not found.', 404);
            }
            $bookingId = (int) $booking['id'];
        }

        $profile = ClientProfile::findByUserId($userId);
        $timezone = (string) $request->input('timezone', '') ?: ($profile['timezone'] ?? 'UTC');

        $id = Consent::record($userId, $bookingId, $request->ip(), $request->userAgent(), $timezone);
        ActivityLog::log($userId, 'consent_accepted', $bookingId ? 'booking' : 'user', $bookingId ?? $userId);

        $this->success(['id' => $id], 'Consent recorded.');
    }
}
