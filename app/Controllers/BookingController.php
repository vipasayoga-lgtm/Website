<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Payments\PaymentGatewayFactory;
use App\Core\Request;
use App\Models\Booking;
use App\Models\ClientProfile;
use App\Models\Consent;
use App\Models\Instructor;
use App\Models\InstructorReview;
use App\Models\MeetingLink;
use App\Models\Package;
use App\Models\Payment;

final class BookingController extends Controller
{
    public function schedulePage(Request $request): void
    {
        $instructorId = (int) $request->query('instructor_id');
        $packageId = (int) $request->query('package_id');

        $instructor = Instructor::findWithName($instructorId);
        $package = Package::find($packageId);

        if (!$instructor || !$package) {
            $this->redirect('/instructors');
        }

        // Stage 2 (health & safety questionnaire) is required before the
        // first booking, but never interrupts a client until they actually
        // try to book — this is the one place that check belongs.
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;
        $stage = ClientProfile::stageReached($profile);

        if ($stage < 2) {
            $this->redirect('/onboarding/health-assessment?redirect=' . urlencode($request->fullPath()));
        }

        // Stage 4 (advanced health) is required before a second booking —
        // same "only interrupt when they act" pattern as Stage 2, gated on
        // whether they already have any prior booking on record at all.
        if ($stage < 4 && count(Booking::forClient(Auth::id())) >= 1) {
            $this->redirect('/onboarding/advanced-health?redirect=' . urlencode($request->fullPath()));
        }

        $this->view('booking/schedule', [
            'instructor' => $instructor,
            'package' => $package,
        ], 'dashboard');
    }

    public function confirmPage(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);
        $gateway = PaymentGatewayFactory::make();
        $config = require dirname(__DIR__) . '/Config/app.php';

        $this->view('booking/confirm', [
            'booking' => $booking,
            'instructor' => Instructor::findWithName($booking['instructor_id']),
            'package' => Package::find($booking['package_id']),
            'payment' => Payment::forBooking((int) $booking['id']),
            'client' => \App\Models\User::find(Auth::id()),
            'paymentConfigured' => $gateway->isConfigured(),
            'paymentProvider' => $gateway->name(),
            'paymentMode' => $config['payments']['mode'],
            'upiDetails' => [
                'id' => \App\Models\Setting::get('upi_id', 'vijayaparameshh@oksbi'),
                'name' => \App\Models\Setting::get('upi_name', 'Vijaya Parameswaran'),
                'phone' => \App\Models\Setting::get('upi_phone', '+91 70127 75990'),
                'qr_image' => \App\Models\Setting::get('upi_qr_image', '/assets/img/payment-qr.jpg'),
            ],
        ], 'dashboard');
    }

    public function successPage(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);

        if (!in_array($booking['status'], ['awaiting_trainer_approval', 'confirmed', 'completed'], true)) {
            $this->redirect('/booking/confirm/' . $ref);
        }

        $this->view('booking/success', [
            'booking' => $booking,
            'instructor' => Instructor::findWithName($booking['instructor_id']),
            'package' => Package::find($booking['package_id']),
        ], 'dashboard');
    }

    public function failedPage(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);

        $this->view('booking/failed', ['booking' => $booking], 'dashboard');
    }

    public function invoicePage(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);

        if (!in_array($booking['status'], ['awaiting_trainer_approval', 'confirmed', 'completed'], true)) {
            $this->redirect('/booking/confirm/' . $ref);
        }

        $this->view('booking/invoice', [
            'booking' => $booking,
            'instructor' => Instructor::findWithName($booking['instructor_id']),
            'package' => Package::find($booking['package_id']),
            'payment' => Payment::forBooking($booking['id']),
            'meetingLink' => MeetingLink::forBooking($booking['id']),
        ], 'dashboard');
    }

    /**
     * The mandatory "before Join Session" consent gate. The app never
     * renders the real meeting URL until a consent row exists for this
     * exact booking — the confirm-and-reveal happens client-side only
     * after `Api\ConsentController::store()` succeeds (see booking/join.php).
     */
    public function joinPage(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);

        if (!in_array($booking['status'], ['confirmed', 'completed'], true)) {
            $this->redirect('/dashboard/bookings');
        }

        $meetingLink = MeetingLink::forBooking((int) $booking['id']);
        $hasConsent = Consent::existsForBooking(Auth::id(), (int) $booking['id']);
        $existingReview = InstructorReview::forBooking((int) $booking['id']);

        $this->view('booking/join', [
            'booking' => $booking,
            'instructor' => Instructor::findWithName($booking['instructor_id']),
            'meetingLink' => $meetingLink,
            'hasConsent' => $hasConsent,
            'existingReview' => $existingReview,
        ], 'dashboard');
    }

    private function ownedBooking(string $ref): array
    {
        $booking = Booking::findByRef($ref);

        if (!$booking || (int) $booking['client_id'] !== Auth::id()) {
            $this->redirect('/services');
        }

        return $booking;
    }
}
