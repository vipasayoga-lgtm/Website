<?php

namespace App\Controllers\Api;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Validator;
use App\Models\ClientProfile;
use App\Models\ProfileEditHistory;
use App\Models\User;

final class ProfileController extends Controller
{
    private const GENDERS = ['female', 'male', 'non_binary', 'prefer_not_to_say'];
    private const CLIENT_CATEGORIES = ['child', 'teenager', 'adult', 'senior_citizen', 'pregnant_woman', 'postpartum_mother'];
    private const MAX_AVATAR_BYTES = 2 * 1024 * 1024;
    private const ALLOWED_AVATAR_MIMES = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    public function show(Request $request): void
    {
        $user = User::find(Auth::id());
        $profile = ClientProfile::findByUserId(Auth::id());

        $this->success([
            'user' => ['name' => $user['name'], 'email' => $user['email'], 'phone' => $user['phone']],
            'profile' => $profile ? ClientProfile::hydrate($profile) : null,
            'google_linked' => !empty($user['google_id']),
        ]);
    }

    /**
     * Handles both the Stage-1 "Get Started" save and later Edit Profile
     * saves — same endpoint, different subsets of fields. `name`, `phone`,
     * and `date_of_birth` are locked forever once first set (Progressive
     * Profiling spec) — silently dropped from the update rather than
     * erroring, since a well-behaved client never sends them once the
     * fields render disabled anyway; this is just defense in depth.
     */
    public function update(Request $request): void
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:150',
            'phone' => 'phone',
            'date_of_birth' => 'date',
            'height_cm' => 'integer',
            'weight_kg' => 'integer',
            'gender' => 'in:' . implode(',', self::GENDERS),
            'country' => 'max:100',
            'city' => 'max:100',
            'bio' => 'max:200',
            'timezone' => 'required|max:60',
            'medical_notes' => 'max:2000',
            'emergency_contact_name' => 'max:150',
            'emergency_contact_phone' => 'phone',
            'emergency_contact_relationship' => 'max:50',
            'client_category' => 'in:' . implode(',', self::CLIENT_CATEGORIES),
        ]);

        if ($validator->fails()) {
            $this->fail($validator->firstError() ?? 'Validation failed.', 422, $validator->errors());
        }

        $userId = Auth::id();
        $user = User::find($userId);
        $profile = ClientProfile::findByUserId($userId);

        $userUpdate = [];
        if (empty($user['name'])) {
            $userUpdate['name'] = $request->input('name');
        }
        if (empty($user['phone']) && $request->input('phone')) {
            $userUpdate['phone'] = $request->input('phone');
        }
        if ($userUpdate !== []) {
            User::update($userId, $userUpdate);
        }

        $dateOfBirth = empty($profile['date_of_birth']) ? ($request->input('date_of_birth') ?: null) : null;

        $data = array_filter([
            'date_of_birth' => $dateOfBirth,
            'height_cm' => $this->intOrNull($request->input('height_cm')),
            'weight_kg' => $this->intOrNull($request->input('weight_kg')),
            'gender' => $request->input('gender') ?: null,
            'country' => $request->input('country', ''),
            'city' => $request->input('city', ''),
            'bio' => $request->input('bio', ''),
            'timezone' => $request->input('timezone'),
            'medical_notes' => $request->input('medical_notes', ''),
            'emergency_contact_name' => $request->input('emergency_contact_name', ''),
            'emergency_contact_phone' => $request->input('emergency_contact_phone', ''),
            'emergency_contact_relationship' => $request->input('emergency_contact_relationship', ''),
            'client_category' => $request->input('client_category') ?: null,
        ], static fn ($v) => $v !== null);

        $effectiveDob = $profile['date_of_birth'] ?? $dateOfBirth;
        if ($effectiveDob) {
            $data['age'] = $this->ageFromDob($effectiveDob);
        }

        if ((bool) $request->input('complete', false)) {
            $data['onboarding_completed_at'] = date('Y-m-d H:i:s');
        }

        ClientProfile::upsertForUser($userId, $data);
        $this->logProfileChanges($userId, $user, $profile ?? [], $userUpdate, $data, $request);

        $this->success(['redirect' => '/dashboard'], 'Profile saved.');
    }

    /**
     * Field-level version history — one row per field that actually
     * changed. `age`/`onboarding_completed_at` are derived/system markers,
     * not meaningful user edits, so they're excluded.
     */
    private function logProfileChanges(int $userId, array $userBefore, array $profileBefore, array $userChanges, array $profileChanges, Request $request): void
    {
        $userAgent = $request->userAgent();
        $timezone = (string) $request->input('timezone', $profileBefore['timezone'] ?? 'UTC');
        $note = trim((string) $request->input('note', '')) ?: null;

        foreach ($userChanges as $field => $newValue) {
            $oldValue = $userBefore[$field] ?? null;
            if ((string) $oldValue !== (string) $newValue) {
                ProfileEditHistory::record($userId, $userId, $field, $oldValue, $newValue, $userAgent, $timezone, $note);
            }
        }

        foreach ($profileChanges as $field => $newValue) {
            if (in_array($field, ['age', 'onboarding_completed_at'], true)) {
                continue;
            }

            $oldValue = $profileBefore[$field] ?? null;
            if ((string) $oldValue !== (string) $newValue) {
                ProfileEditHistory::record($userId, $userId, $field, $oldValue, $newValue, $userAgent, $timezone, $note);
            }
        }
    }

    private function intOrNull(mixed $value): ?int
    {
        return ($value !== null && $value !== '') ? (int) $value : null;
    }

    private function ageFromDob(string $dob): int
    {
        return (new \DateTimeImmutable($dob))->diff(new \DateTimeImmutable())->y;
    }

    public function changePassword(Request $request): void
    {
        $user = User::find(Auth::id());
        if ($user && $user['role'] === 'instructor') {
            $this->fail('Instructors cannot change passwords directly. Please contact your studio administrator to update your password.', 403);
        }

        $currentPassword = (string) $request->input('current_password', '');
        $newPassword = (string) $request->input('new_password', '');

        if (strlen($newPassword) < 8) {
            $this->fail('New password must be at least 8 characters.', 422, ['new_password' => ['Too short.']]);
        }

        if (!empty($user['password_hash'])) {
            if ($currentPassword === '' || !password_verify($currentPassword, $user['password_hash'])) {
                $this->fail('Current password is incorrect.', 422, ['current_password' => ['Incorrect password.']]);
            }
        }

        User::setPassword(Auth::id(), $newPassword);

        $this->success(null, 'Password updated.');
    }

    public function uploadAvatar(Request $request): void
    {
        if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $this->fail('Please choose a photo to upload.', 422);
        }

        $file = $_FILES['avatar'];
        $mime = mime_content_type($file['tmp_name']);

        if (!isset(self::ALLOWED_AVATAR_MIMES[$mime])) {
            $this->fail('Please upload a JPG, PNG, or WEBP image.', 422);
        }

        if ($file['size'] > self::MAX_AVATAR_BYTES) {
            $this->fail('Image must be smaller than 2MB.', 422);
        }

        $userId = Auth::id();
        $dir = dirname(__DIR__, 3) . '/public/uploads/avatars';

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = $userId . '-' . time() . '.' . self::ALLOWED_AVATAR_MIMES[$mime];
        $destination = $dir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->fail('Could not save the uploaded photo. Please try again.', 500);
        }

        $existing = ClientProfile::findByUserId($userId);
        if ($existing && !empty($existing['avatar_path'])) {
            $oldPath = dirname(__DIR__, 3) . '/public' . $existing['avatar_path'];
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $publicPath = '/uploads/avatars/' . $filename;
        ClientProfile::upsertForUser($userId, ['avatar_path' => $publicPath]);

        $this->success(['avatar_path' => $publicPath], 'Photo updated.');
    }
}
