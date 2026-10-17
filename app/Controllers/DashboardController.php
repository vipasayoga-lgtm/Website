<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\Booking;
use App\Models\BookingRescheduleRequest;
use App\Models\ClientProfile;
use App\Models\Favorite;
use App\Models\Instructor;
use App\Models\InstructorAvailability;
use App\Models\InstructorReview;
use App\Models\InstructorService;
use App\Models\MeetingLink;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Payment;
use App\Models\ServiceType;

final class DashboardController extends Controller
{
    private const PROFILE_MILESTONES = [20, 40, 65, 80, 100];

    private const WELLNESS_QUOTES = [
        'Flow like a river — steady, unhurried, and always moving forward.',
        'The body benefits from movement, and the mind benefits from stillness.',
        'Progress is not linear. Show up for yourself today, exactly as you are.',
        'Breath is the bridge between the body and the mind.',
        'Small, consistent practice beats occasional intensity.',
        'You are not behind. You are exactly where your practice needs you to be.',
        'Every session is a return to yourself.',
    ];

    /** Maps a client's yoga goals to a plain-text program recommendation — a simple rule, not a real matching engine. */
    private const PROGRAM_RECOMMENDATIONS = [
        'weight_loss' => 'Weight Loss Flow',
        'flexibility' => 'Flexibility & Mobility',
        'strength' => 'Strength Building',
        'pain_relief' => 'Therapeutic Pain Relief',
        'meditation' => 'Meditation & Mindfulness',
        'stress_relief' => 'Stress Relief Sessions',
        'better_sleep' => 'Restorative Evening Practice',
        'prenatal' => 'Prenatal Yoga',
        'postnatal' => 'Postnatal Recovery',
        'general_wellness' => 'General Wellness',
    ];

    public function index(Request $request): void
    {
        if (Auth::hasRole('admin')) {
            $this->redirect('/admin');
            return;
        }
        if (Auth::hasRole('instructor')) {
            $this->redirect('/trainer/dashboard');
            return;
        }

        Booking::autoExpirePastConfirmedBookings();
        $bookings = Booking::forClient(Auth::id());

        $nextSession = null;
        $completedCount = 0;

        foreach ($bookings as $booking) {
            if ($booking['status'] === 'completed') {
                $completedCount++;
            }

            $isFuture = strtotime($booking['slot_date'] . ' ' . $booking['start_time']) > time();
            $isBookable = $isFuture && in_array($booking['status'], ['awaiting_trainer_approval', 'confirmed'], true);

            if ($isBookable && ($nextSession === null || $booking['slot_date'] . $booking['start_time'] < $nextSession['slot_date'] . $nextSession['start_time'])) {
                $nextSession = $booking;
            }
        }

        if ($nextSession !== null) {
            $nextSession['instructor'] = Instructor::findWithName($nextSession['instructor_id']);
            $nextSession['package'] = Package::find($nextSession['package_id']);
            $nextSession['meeting_link'] = MeetingLink::forBooking((int) $nextSession['id']);
        }

        $unreviewedBooking = Booking::findUnreviewedCompletedForClient(Auth::id());
        $latestCompleted = Booking::findLatestCompletedForClient(Auth::id());

        $quote = self::WELLNESS_QUOTES[(int) date('z') % count(self::WELLNESS_QUOTES)];
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;
        $ha = $profile['health_assessment'] ?? [];
        $ha = is_array($ha) ? $ha : [];

        $goals = $ha['goals']['selected'] ?? [];
        $recommendations = array_values(array_unique(array_map(
            static fn ($g) => self::PROGRAM_RECOMMENDATIONS[$g] ?? null,
            $goals
        )));
        $recommendations = array_filter($recommendations);

        $conditions = array_filter((array) ($ha['medicalHistory']['conditions'] ?? []), static fn ($c) => $c !== 'none_of_these');
        $painAreas = array_filter((array) ($ha['painAssessment']['areas'] ?? []), static fn ($a) => $a !== 'no_pain');

        // In-dashboard "insight" panels so clients can start a booking or scan
        // instructors without leaving the dashboard for the public marketing
        // pages. Kept to a small featured slice — these are previews, the full
        // lists still live at /services and /instructors.
        $featuredPackages = array_slice(Package::featured(), 0, 3);
        $featuredInstructors = array_slice(Instructor::searchActive(), 0, 4);

        $this->view('dashboard/index', [
            'featuredPackages' => $featuredPackages,
            'featuredInstructors' => $featuredInstructors,
            'defaultPackageId' => $featuredPackages[0]['id'] ?? null,
            'nextSession' => $nextSession,
            'unreviewedBooking' => $unreviewedBooking,
            'latestCompleted' => $latestCompleted,
            'completedCount' => $completedCount,
            'upcomingCount' => count(array_filter($bookings, static fn (array $b): bool => in_array($b['status'], ['awaiting_trainer_approval', 'confirmed'], true) && strtotime($b['slot_date'] . ' ' . $b['start_time']) > time())),
            'favoriteCount' => count(Favorite::forClient(Auth::id())),
            'weekStreak' => $this->computeWeekStreak($bookings),
            'wellnessQuote' => $quote,
            'completionPercent' => $this->profileCompletionAndCelebrate($profile),
            'goals' => $goals,
            'recommendations' => $recommendations,
            'healthSummary' => [
                'conditionsCount' => count($conditions),
                'painAreasCount' => count($painAreas),
            ],
        ], 'dashboard');
    }

    /**
     * Computes the profile-completion % and, the first time it crosses one
     * of the milestone thresholds, fires a one-time celebration (confetti +
     * a persisted notification). The last-seen milestone lives in-session
     * so refreshing the dashboard doesn't refire the same celebration.
     */
    private function profileCompletionAndCelebrate(?array $profile): array
    {
        $percent = ClientProfile::completionPercent($profile);
        $stage = ClientProfile::stageReached($profile);

        $lastSeen = (int) Session::get('_profile_milestone_seen', 0);
        $justReached = null;

        foreach (self::PROFILE_MILESTONES as $milestone) {
            if ($percent >= $milestone && $lastSeen < $milestone) {
                $justReached = $milestone;
            }
        }

        if ($justReached !== null) {
            Session::set('_profile_milestone_seen', $justReached);
            $alreadyExists = Notification::whereFirst([
                'user_id' => Auth::id(),
                'type' => 'profile_milestone',
                'title' => "Profile {$justReached}% complete!",
            ]);
            if (!$alreadyExists) {
                Notification::create(
                    Auth::id(),
                    'profile_milestone',
                    "Profile {$justReached}% complete!",
                    'Nice progress — keep going to unlock your full wellness profile.'
                );
            }
        }

        return ['percent' => $percent, 'justReached' => $justReached, 'stage' => $stage];
    }

    /**
     * Consecutive weeks (Mon-Sun) with at least one completed session, walking
     * backward from today. The current week doesn't have to be done yet — an
     * in-progress week just isn't counted, so it can't falsely break a streak.
     */
    private function computeWeekStreak(array $bookings): int
    {
        $weeksWithSession = [];

        foreach ($bookings as $booking) {
            if ($booking['status'] === 'completed') {
                $weeksWithSession[date('o-W', strtotime($booking['slot_date']))] = true;
            }
        }

        if (empty($weeksWithSession)) {
            return 0;
        }

        $cursor = time();
        if (!isset($weeksWithSession[date('o-W', $cursor)])) {
            $cursor = strtotime('-7 days', $cursor);
        }

        $streak = 0;
        while (isset($weeksWithSession[date('o-W', $cursor)])) {
            $streak++;
            $cursor = strtotime('-7 days', $cursor);
        }

        return $streak;
    }

    public function notifications(Request $request): void
    {
        $notifications = Notification::forUser(Auth::id(), 100);

        foreach ($notifications as $notification) {
            if (!$notification['is_read']) {
                Notification::markRead((int) $notification['id']);
            }
        }

        $this->view('dashboard/notifications', ['notifications' => $notifications], 'dashboard');
    }

    /**
     * "Book a Session" rendered inside the dashboard shell (same package grid
     * as the public /services page) so the sidebar link keeps the client in
     * their dashboard instead of bouncing out to the marketing site.
     */
    public function bookSession(Request $request): void
    {
        $this->view('services/index', [
            'packages' => Package::featured(),
            'instructorsBase' => '/dashboard/instructors',
        ], 'dashboard');
    }

    /**
     * "Instructors" rendered inside the dashboard shell — mirrors the public
     * InstructorController::index data so the sidebar link stays in-dashboard.
     */
    public function instructorsList(Request $request): void
    {
        $packageId = $request->query('package');
        $serviceTypeId = $request->query('service');
        $featured = Package::featured();

        $filters = [];
        if ($packageId) {
            $filters['package_id'] = (int) $packageId;
        }
        if ($serviceTypeId) {
            $filters['service_type_id'] = (int) $serviceTypeId;
        }

        $instructors = Instructor::searchActive($filters);

        $calcPackageId = $packageId;
        if (!$calcPackageId && $serviceTypeId) {
            $pkg = Package::forServiceType((int) $serviceTypeId)[0] ?? null;
            $calcPackageId = $pkg['id'] ?? null;
        }

        if ($calcPackageId) {
            foreach ($instructors as &$instructor) {
                $instructor['effective_price'] = InstructorService::effectivePrice($instructor['id'], (int) $calcPackageId);
            }
            unset($instructor);
        }

        $this->view('instructors/index', [
            'instructors' => $instructors,
            'serviceTypes' => ServiceType::activeOrdered(),
            'selectedPackageId' => $packageId,
            'selectedServiceTypeId' => $serviceTypeId,
            'defaultPackageId' => $featured[0]['id'] ?? null,
            'instructorsBase' => '/dashboard/instructors',
        ], 'dashboard');
    }

    /**
     * View an instructor profile inside the dashboard layout shell so the client
     * stays inside the portal shell rather than navigating out to the public site.
     */
    public function instructorDetail(Request $request, string $id): void
    {
        $instructor = Instructor::findWithName((int) $id);

        if (!$instructor || $instructor['status'] !== 'active') {
            $this->redirect('/dashboard/instructors');
        }

        $this->view('instructors/show', [
            'instructor' => $instructor,
            'reviews' => InstructorReview::forInstructor($instructor['id']),
            'availability' => InstructorAvailability::forInstructor($instructor['id']),
            'packageId' => $request->query('package'),
            'isFavorited' => Favorite::isFavorited(Auth::id(), (int) $instructor['id']),
            'instructorsBase' => '/dashboard/instructors',
        ], 'dashboard');
    }

    public function bookings(Request $request): void
    {
        Booking::autoExpirePastConfirmedBookings();
        $bookings = Booking::forClient(Auth::id());

        foreach ($bookings as &$booking) {
            $booking['instructor'] = Instructor::findWithName($booking['instructor_id']);
            $booking['package'] = Package::find($booking['package_id']);
            $booking['meeting_link'] = MeetingLink::forBooking($booking['id']);
            $booking['can_rate'] = $booking['status'] === 'completed' && !InstructorReview::existsForBooking($booking['id']);
            $booking['has_pending_reschedule'] = $booking['status'] === 'confirmed' && BookingRescheduleRequest::pendingForBooking($booking['id']) !== null;
        }
        unset($booking);

        $upcoming = [];
        $history = [];

        foreach ($bookings as $booking) {
            $isFuture = strtotime($booking['slot_date'] . ' ' . $booking['start_time']) > time();
            $isUpcoming = $isFuture && in_array($booking['status'], ['pending_payment', 'awaiting_trainer_approval', 'confirmed'], true);

            if ($isUpcoming) {
                $upcoming[] = $booking;
            } else {
                $history[] = $booking;
            }
        }

        $this->view('dashboard/bookings', ['upcoming' => $upcoming, 'history' => $history], 'dashboard');
    }

    public function payments(Request $request): void
    {
        $payments = Payment::forClient(Auth::id());

        $this->view('dashboard/payments', ['payments' => $payments], 'dashboard');
    }

    public function invoices(Request $request): void
    {
        $bookings = array_values(array_filter(
            Booking::forClient(Auth::id()),
            static fn (array $b): bool => in_array($b['status'], ['awaiting_trainer_approval', 'confirmed', 'completed'], true)
        ));

        foreach ($bookings as &$booking) {
            $booking['instructor'] = Instructor::findWithName($booking['instructor_id']);
            $booking['package'] = Package::find($booking['package_id']);
        }
        unset($booking);

        $this->view('dashboard/invoices', ['bookings' => $bookings], 'dashboard');
    }

    public function favorites(Request $request): void
    {
        $this->view('dashboard/favorites', ['favorites' => Favorite::forClient(Auth::id())], 'dashboard');
    }
}
