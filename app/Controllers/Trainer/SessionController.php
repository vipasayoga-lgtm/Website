<?php

namespace App\Controllers\Trainer;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\ClientProfile;
use App\Models\Instructor;
use App\Models\MeetingLink;
use App\Models\Notification;
use App\Models\Package;
use App\Models\SessionFeedback;
use App\Models\SessionResource;
use App\Models\User;

final class SessionController extends Controller
{
    private const MAX_RESOURCE_BYTES = 10 * 1024 * 1024;
    private const ALLOWED_RESOURCE_EXTENSIONS = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'mp4'];

    public function show(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);
        $client = User::find((int) $booking['client_id']);
        $profileRow = ClientProfile::findByUserId((int) $booking['client_id']);
        $profile = $profileRow ? ClientProfile::hydrate($profileRow) : null;

        $this->view('trainer/session', [
            'booking' => $booking,
            'client' => ['name' => $client['name'], 'email' => $client['email'], 'phone' => $client['phone']],
            'safety' => ClientProfile::safetySummary($profile),
            'package' => Package::find($booking['package_id']),
            'meetingLink' => MeetingLink::forBooking((int) $booking['id']),
            'feedback' => SessionFeedback::forBooking((int) $booking['id']),
            'resources' => SessionResource::forBooking((int) $booking['id']),
        ], 'dashboard');
    }

    public function save(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);
        $instructor = Instructor::findByUserId(Auth::id());

        $data = [
            'booking_id' => (int) $booking['id'],
            'client_id' => (int) $booking['client_id'],
            'instructor_id' => (int) $instructor['id'],
            'session_notes' => trim((string) $request->input('session_notes', '')) ?: null,
            'recommendation' => trim((string) $request->input('recommendation', '')) ?: null,
            'homework' => trim((string) $request->input('homework', '')) ?: null,
            'attendance' => in_array($request->input('attendance'), ['present', 'absent'], true) ? $request->input('attendance') : null,
            'rating' => $request->input('rating') !== null && $request->input('rating') !== '' ? max(1, min(5, (int) $request->input('rating'))) : null,
            'feedback_text' => trim((string) $request->input('feedback_text', '')) ?: null,
        ];

        $existing = SessionFeedback::forBooking((int) $booking['id']);

        if ($existing) {
            SessionFeedback::update($existing['id'], $data);
        } else {
            SessionFeedback::insert($data);
        }

        if ($booking['status'] === 'confirmed' && strtotime($booking['slot_date'] . ' ' . $booking['end_time']) < time()) {
            Booking::update($booking['id'], ['status' => 'completed']);
            // Logged against the client's own user_id (not the trainer, who
            // triggered this save) so it appears on the client's timeline.
            ActivityLog::log((int) $booking['client_id'], 'session_completed', 'booking', (int) $booking['id'], ['booking_ref' => $booking['booking_ref']]);
        }

        ActivityLog::log(Auth::id(), 'session_notes_saved', 'booking', (int) $booking['id']);

        $this->success(null, 'Session updated.');
    }

    public function saveMeetingLink(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);
        $provider = in_array($request->input('provider'), ['google_meet', 'zoom'], true)
            ? (string) $request->input('provider')
            : 'google_meet';
        $url = trim((string) $request->input('url', ''));

        if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
            $this->fail('Please enter a valid meeting URL (e.g. Google Meet or Zoom link).', 422);
        }

        MeetingLink::upsertForBooking((int) $booking['id'], $provider, $url, Auth::id());
        ActivityLog::log(Auth::id(), 'meeting_link_saved', 'booking', (int) $booking['id'], ['provider' => $provider, 'url' => $url]);

        $dateLabel = date('d M Y', strtotime($booking['slot_date']));
        $timeLabel = date('g:i A', strtotime($booking['start_time']));

        Notification::create(
            (int) $booking['client_id'],
            'booking',
            'Meeting Link Available — ' . $booking['booking_ref'],
            'Your instructor has shared a meeting link for your session on ' . $dateLabel . ' at ' . $timeLabel . '. Click to join!'
        );

        $this->success(null, 'Meeting link sent to student.');
    }

    public function complete(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);
        if ($booking['status'] !== 'confirmed') {
            $this->fail('Only confirmed sessions can be marked as completed.', 422);
        }

        Booking::update((int) $booking['id'], ['status' => 'completed']);
        ActivityLog::log(Auth::id(), 'session_completed_by_trainer', 'booking', (int) $booking['id'], [
            'booking_ref' => $booking['booking_ref'],
        ]);

        Notification::create(
            (int) $booking['client_id'],
            'booking',
            'Session Completed — ' . $booking['booking_ref'],
            'Your session with your instructor has finished! Please share your rating and feedback on your dashboard.'
        );

        $this->success(null, 'Session marked as finished. Feedback prompt sent to student.');
    }

    public function uploadResource(Request $request, string $ref): void
    {
        $booking = $this->ownedBooking($ref);
        $instructor = Instructor::findByUserId(Auth::id());

        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->fail('Please choose a file to upload.', 422);
        }

        $file = $_FILES['file'];

        if ($file['size'] > self::MAX_RESOURCE_BYTES) {
            $this->fail('File must be smaller than 10MB.', 422);
        }

        $extension = strtolower((string) pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, self::ALLOWED_RESOURCE_EXTENSIONS, true)) {
            $this->fail('Unsupported file type.', 422);
        }

        $dir = dirname(__DIR__, 3) . '/public/uploads/session-resources';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = $booking['id'] . '-' . time() . '.' . $extension;
        $destination = $dir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->fail('Could not save the uploaded file. Please try again.', 500);
        }

        $title = trim((string) $request->input('title', '')) ?: pathinfo($file['name'], PATHINFO_FILENAME);

        $id = SessionResource::insert([
            'booking_id' => (int) $booking['id'],
            'instructor_id' => (int) $instructor['id'],
            'title' => $title,
            'file_path' => '/uploads/session-resources/' . $filename,
        ]);

        ActivityLog::log(Auth::id(), 'session_resource_uploaded', 'booking', (int) $booking['id']);

        $this->success(['id' => $id], 'Resource uploaded.');
    }

    private function ownedBooking(string $ref): array
    {
        $instructor = Instructor::findByUserId(Auth::id());
        $booking = Booking::findByRef($ref);

        if (!$booking || (int) $booking['instructor_id'] !== (int) $instructor['id']) {
            $this->fail('Session not found.', 404);
        }

        if (!in_array($booking['status'], ['confirmed', 'completed'], true)) {
            $this->fail('This session is not confirmed yet.', 422);
        }

        return $booking;
    }
}
