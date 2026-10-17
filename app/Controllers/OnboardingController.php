<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\ClientProfile;
use App\Models\User;

final class OnboardingController extends Controller
{
    public function page(Request $request): void
    {
        if (Auth::hasRole('admin')) {
            $this->redirect('/admin/settings');
            return;
        }
        if (Auth::hasRole('instructor')) {
            $this->redirect('/trainer/profile');
            return;
        }

        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;

        $this->view('onboarding/profile-wizard', [
            'profile' => $profile,
            'user' => User::find(Auth::id()),
        ], 'dashboard');
    }

    /**
     * The Stage-1 "Get Started" intake — the only thing standing between a
     * first-time client and the Dashboard. Deliberately rendered in the
     * bare layout (no sidebar/chrome) so it reads as a single quick task,
     * not another page inside the app.
     */
    public function getStarted(Request $request): void
    {
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;

        $this->view('onboarding/get-started', [
            'profile' => $profile,
            'user' => User::find(Auth::id()),
        ], 'bare');
    }

    /**
     * Stage 2 — the conversational health & safety questionnaire shown just
     * before a client's first booking (never on dashboard entry). `redirect`
     * carries the booking URL the client was trying to reach, so completing
     * the assessment sends them straight back into that booking attempt.
     */
    public function healthAssessment(Request $request): void
    {
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;

        $redirect = (string) $request->query('redirect', '');

        $this->view('onboarding/health-assessment', [
            'profile' => $profile,
            'category' => $profile['client_category'] ?? null,
            'redirectTo' => Auth::isSafeRedirectPath($redirect) ? $redirect : '/dashboard',
        ], 'bare');
    }

    /**
     * Stage 3 — lifestyle questionnaire, surfaced only via a dashboard card
     * once a client has completed their first session (never a forced gate,
     * unlike Stage 2/4). Reached by choice, so it always returns to the
     * dashboard when finished.
     */
    public function wellnessProfile(Request $request): void
    {
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;

        $this->view('onboarding/wellness-profile', [
            'profile' => $profile,
            'category' => $profile['client_category'] ?? null,
        ], 'bare');
    }

    /**
     * Stage 4 — advanced health check-in, gated before a client's second
     * booking (same "only interrupt when they act" pattern as Stage 2).
     * `redirect` carries the booking URL to return to once done.
     */
    public function advancedHealth(Request $request): void
    {
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;

        $redirect = (string) $request->query('redirect', '');

        $this->view('onboarding/advanced-health', [
            'profile' => $profile,
            'category' => $profile['client_category'] ?? null,
            'redirectTo' => Auth::isSafeRedirectPath($redirect) ? $redirect : '/dashboard',
        ], 'bare');
    }

    /**
     * Stage 5 — final details, review, and mandatory legal consent. Never a
     * gate — surfaced only via a "Complete your profile" dashboard card
     * once Stage 4 is done, matching Stage 3's non-blocking pattern.
     */
    public function completeProfile(Request $request): void
    {
        $profile = ClientProfile::findByUserId(Auth::id());
        $profile = $profile ? ClientProfile::hydrate($profile) : null;

        $this->view('onboarding/complete-profile', [
            'profile' => $profile,
            'user' => User::find(Auth::id()),
            'category' => $profile['client_category'] ?? null,
        ], 'bare');
    }
}
