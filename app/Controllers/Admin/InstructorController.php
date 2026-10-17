<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Mailer;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\Instructor;
use App\Models\PasswordReset;
use App\Models\User;

final class InstructorController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('admin/instructors', [
            'instructors' => Instructor::adminList(),
        ], 'dashboard');
    }

    public function store(Request $request): void
    {
        $validator = $this->validate($request, $this->rules());

        if ($validator->fails()) {
            $this->fail($validator->firstError() ?? 'Validation failed.', 422, $validator->errors());
        }

        $name = (string) $request->input('name');
        $email = (string) $request->input('email');
        $phone = $request->input('phone') ?: null;
        $password = (string) $request->input('password', '');

        if (User::existsWithEmailOrPhone($email, $phone)) {
            $this->fail('An account with this email or phone already exists.', 409);
        }

        /* The admin sets the trainer's password here, so the account can sign in
           at /login immediately (Auth::redirectHome() sends role=instructor
           straight to /trainer/dashboard). Leaving it blank falls back to the
           original behaviour — a null hash plus an emailed set-password link —
           which still matters on installs where the admin would rather the
           trainer choose their own and SMTP is configured. */
        $userId = User::insert([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password_hash' => $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null,
            'role' => 'instructor',
            'status' => 'active',
        ]);

        $instructorId = Instructor::insert([
            'user_id' => $userId,
            'headline' => $request->input('headline') ?: null,
            'bio' => $request->input('bio') ?: null,
            'experience_years' => (int) $request->input('experience_years', 0),
            'certificates' => $this->toJsonList($request->input('certificates')),
            'specialties' => $this->toJsonList($request->input('specialties')),
            'timezone' => (string) $request->input('timezone', 'UTC'),
            'status' => 'active',
        ]);

        if ($password !== '') {
            $this->sendAccountReadyEmail($email, $name);
        } else {
            $this->sendInviteEmail($userId, $email, $name);
        }

        ActivityLog::log(Auth::id(), 'instructor_created', 'instructor', $instructorId, [
            'name' => $name,
            'password_set_by_admin' => $password !== '',
        ]);

        $this->success(
            ['id' => $instructorId],
            $password !== ''
                ? 'Trainer added. They can sign in at /login with the email and password you set.'
                : 'Trainer added. A set-password link has been emailed to them.'
        );
    }

    public function update(Request $request, int $id): void
    {
        $instructor = Instructor::find($id);

        if (!$instructor) {
            $this->fail('Instructor not found.', 404);
        }

        $validator = $this->validate($request, $this->rules(false));

        if ($validator->fails()) {
            $this->fail($validator->firstError() ?? 'Validation failed.', 422, $validator->errors());
        }

        $userId = (int) $instructor['user_id'];
        $name = (string) $request->input('name');
        $email = (string) $request->input('email');
        $phone = $request->input('phone') ?: null;
        $password = (string) $request->input('password', '');

        if ($email !== '' && User::existsWithEmailOrPhoneExceptUser($email, $phone, $userId)) {
            $this->fail('Another account with this email or phone already exists.', 409);
        }

        Instructor::update($id, [
            'headline' => $request->input('headline') ?: null,
            'bio' => $request->input('bio') ?: null,
            'experience_years' => (int) $request->input('experience_years', 0),
            'certificates' => $this->toJsonList($request->input('certificates')),
            'specialties' => $this->toJsonList($request->input('specialties')),
            'timezone' => (string) $request->input('timezone', 'UTC'),
        ]);

        $userPayload = [
            'name' => $name,
            'email' => $email !== '' ? $email : ($instructor['email'] ?? ''),
            'phone' => $phone,
        ];

        if ($password !== '') {
            $userPayload['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        User::update($userId, $userPayload);

        ActivityLog::log(Auth::id(), 'instructor_updated', 'instructor', $id, [
            'name' => $name,
            'email_updated' => $email !== '',
            'password_updated' => $password !== '',
        ]);

        $this->success(null, 'Instructor updated.');
    }

    /**
     * Hard-delete a trainer, but only while nothing of record depends on them.
     *
     * `bookings.instructor_id` is ON DELETE CASCADE, and `payments.booking_id`
     * cascades from bookings in turn — so deleting a trainer who has ever been
     * booked would silently take that booking and payment history with them.
     * Anyone with bookings is therefore refused here and must be deactivated
     * instead (which is what the Deactivate button is for). With no bookings,
     * the remaining cascades only clear rows that belong to the trainer alone:
     * availability, blocked dates, gallery images, certificate files.
     */
    public function delete(Request $request, int $id): void
    {
        $instructor = Instructor::findWithName($id);

        if (!$instructor) {
            $this->fail('Instructor not found.', 404);
        }

        $db = Database::connection();
        $userId = (int) $instructor['user_id'];
        $name = (string) ($instructor['name'] ?? 'Instructor');

        // Deleting the user row cascades to instructors and all related records
        $db->prepare('DELETE FROM users WHERE id = :id AND role = :role')
            ->execute(['id' => $userId, 'role' => 'instructor']);

        ActivityLog::log(Auth::id(), 'instructor_deleted', 'instructor', $id, ['name' => $name]);

        $this->success(null, sprintf('%s has been deleted from the database.', $name));
    }

    public function toggleActive(Request $request, int $id): void
    {
        $instructor = Instructor::find($id);

        if (!$instructor) {
            $this->fail('Instructor not found.', 404);
        }

        $newStatus = $instructor['status'] === 'active' ? 'inactive' : 'active';
        Instructor::update($id, ['status' => $newStatus]);
        User::update((int) $instructor['user_id'], ['status' => $newStatus]);

        ActivityLog::log(Auth::id(), $newStatus === 'active' ? 'instructor_activated' : 'instructor_deactivated', 'instructor', $id);

        $this->success(['status' => $newStatus], $newStatus === 'active' ? 'Instructor activated.' : 'Instructor deactivated.');
    }

    private function rules(bool $forCreate = true): array
    {
        $rules = [
            'name' => 'required|min:2|max:150',
            'phone' => 'phone',
            'headline' => 'max:200',
            'experience_years' => 'integer|min:0',
            'timezone' => 'required',
            'email' => 'required|email|max:190',
        ];

        if ($forCreate) {
            $rules['password'] = 'min:8|max:200|confirmed';
        } else {
            $rules['password'] = 'min:8|max:200|confirmed';
        }

        return $rules;
    }

    private function toJsonList(mixed $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $items = array_values(array_filter(
            array_map('trim', explode(',', $value)),
            static fn (string $v): bool => $v !== ''
        ));

        return !empty($items) ? json_encode($items) : null;
    }

    /**
     * Sent when the admin set the password themselves. Deliberately does NOT
     * contain the password — mail is not a safe channel for one, and the admin
     * already has it to hand over directly.
     */
    private function sendAccountReadyEmail(string $email, string $name): void
    {
        $config = require dirname(__DIR__, 2) . '/Config/app.php';
        $loginUrl = $config['url'] . '/login';

        Mailer::send(
            $email,
            "Your instructor account at Vipasa Yoga is ready",
            "<p>Hi {$name},</p><p>An instructor account has been created for you at Vipasa Yoga. "
            . "Sign in at <a href=\"{$loginUrl}\">{$loginUrl}</a> using this email address and the "
            . "password the studio admin assigned to you.</p><p>If you need your credentials updated in the future, please contact your studio administrator.</p>"
        );
    }

    private function sendInviteEmail(int $userId, string $email, string $name): void
    {
        $token = PasswordReset::createToken($userId);
        $config = require dirname(__DIR__, 2) . '/Config/app.php';
        $link = $config['url'] . '/reset-password?token=' . urlencode($token);

        Mailer::send(
            $email,
            "You've been added as an instructor at Vipasa Yoga",
            "<p>Hi {$name},</p><p>You've been added as an instructor at Vipasa Yoga. Please click the link below to set your password and sign in:</p><p><a href=\"{$link}\">{$link}</a></p><p>This link expires in 30 minutes.</p>"
        );
    }
}
