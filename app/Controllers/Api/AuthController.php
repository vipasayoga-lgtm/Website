<?php

namespace App\Controllers\Api;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Mailer;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\EmailVerification;
use App\Models\Instructor;
use App\Models\PasswordReset;
use App\Models\User;

final class AuthController extends Controller
{
    public function login(Request $request): void
    {
        $email = (string) $request->input('email', '');
        $password = (string) $request->input('password', '');

        if ($email === '' || $password === '') {
            $this->fail('Please enter your email and password.', 422);
        }

        $user = Auth::validateCredentials($email, $password);

        if (!$user) {
            $this->fail('Incorrect credentials. Please try again.', 401);
        }

        if ($user['status'] === 'inactive' || $user['status'] === 'suspended') {
            $this->fail('Your account is currently inactive. Access denied. Please contact the studio administrator.', 403);
        }

        if ($user['role'] === 'instructor') {
            $instructor = Instructor::findByUserId((int) $user['id']);
            if ($instructor && $instructor['status'] === 'inactive') {
                $this->fail('Your instructor account is currently inactive. Access denied. Please contact the studio administrator.', 403);
            }
        }

        Auth::login($user, (bool) $request->input('remember', false));

        ActivityLog::log((int) $user['id'], 'login', 'user', (int) $user['id']);
        Mailer::sendLoginNotification($user, 'Email / Password');

        $redirect = (string) $request->input('redirect', '');
        $destination = Auth::isSafeRedirectPath($redirect) ? $redirect : Auth::redirectHome();

        $this->success(['redirect' => $destination], 'Welcome back!');
    }

    public function signup(Request $request): void
    {
        $name = trim((string) $request->input('name', ''));
        $email = trim((string) $request->input('email', ''));
        $phone = trim((string) $request->input('phone', ''));
        $password = (string) $request->input('password', '');
        $passwordConfirmation = (string) $request->input('password_confirmation', '');

        $errors = [];

        if ($name === '') {
            $errors['name'] = ['Please enter your name.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Please enter a valid email address.'];
        }

        if ($phone !== '' && !preg_match('/^[0-9+\s\-()]{7,20}$/', $phone)) {
            $errors['phone'] = ['Please enter a valid phone number.'];
        }

        if (strlen($password) < 8) {
            $errors['password'] = ['Password must be at least 8 characters.'];
        }

        if ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] = ['Passwords do not match.'];
        }

        if (!empty($errors)) {
            $this->fail('Please fix the errors and try again.', 422, $errors);
        }

        if (User::existsWithEmailOrPhone($email, $phone !== '' ? $phone : null)) {
            $this->fail('An account with this email or phone number already exists.', 422, [
                'email' => ['This email is already registered.'],
            ]);
        }

        $userId = User::create($name, $email, $phone !== '' ? $phone : null, $password);

        $user = User::find($userId);
        if ($user) {
            Auth::login($user);
        }

        ActivityLog::log($userId, 'account_created', 'user', $userId);

        try {
            $this->sendVerificationEmail($userId, $email);
        } catch (\Throwable $e) {
            // Logged/suppressed to ensure smooth auto-login flow even if mail transport fails locally
        }

        $redirect = (string) $request->input('redirect', '');
        $destination = Auth::isSafeRedirectPath($redirect) ? $redirect : Auth::redirectHome();

        $this->success(['redirect' => $destination], 'Account created successfully! Welcome to Vipasa Yoga.');
    }

    private function sendVerificationEmail(int $userId, string $email): void
    {
        $token = EmailVerification::createToken($userId);
        $config = require dirname(__DIR__, 2) . '/Config/app.php';
        $link = $config['url'] . '/verify-email?token=' . urlencode($token);

        Mailer::send(
            $email,
            'Verify your Vipasa Yoga email',
            "<p>Welcome to Vipasa Yoga! Please verify your email address to activate your account.</p>"
            . "<p><a href=\"{$link}\">Verify your email</a></p>"
            . "<p>This link expires in 24 hours. If you didn't sign up, you can safely ignore this email.</p>"
        );
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        $this->success(null, 'Logged out.');
    }

    public function forgotPassword(Request $request): void
    {
        $email = (string) $request->input('email', '');
        $password = (string) $request->input('password', '');
        $passwordConfirmation = (string) $request->input('password_confirmation', '');

        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ['Please enter a valid email address.'];
        }

        if (strlen($password) < 8) {
            $errors['password'] = ['Password must be at least 8 characters.'];
        }

        if ($password !== $passwordConfirmation) {
            $errors['password_confirmation'] = ['Passwords do not match.'];
        }

        if (!empty($errors)) {
            $this->fail('Please fix the errors and try again.', 422, $errors);
        }

        $user = User::findBy('email', $email);

        if (!$user) {
            $this->fail('No account was found with that email address.', 404, [
                'email' => ['No account found for this email.'],
            ]);
        }

        if ($user['role'] === 'instructor') {
            $this->fail('Trainer credentials (email & password) are managed exclusively by the studio administrator in the Admin Dashboard. Trainers cannot use self-serve password reset.', 403, [
                'email' => ['Trainer password reset is disabled. Please contact your studio administrator to update your credentials.'],
            ]);
        }

        User::setPassword((int) $user['id'], $password);
        ActivityLog::log((int) $user['id'], 'password_reset', 'user', (int) $user['id']);

        $this->success(['redirect' => '/login'], 'Password updated. Please sign in with your new password.');
    }

    public function resetPassword(Request $request): void
    {
        $token = (string) $request->input('token', '');
        $userId = $token !== '' ? PasswordReset::verifyByToken($token) : null;

        if (!$userId) {
            $this->fail('This reset link is invalid or has expired. Please request a new one.', 419);
        }

        $targetUser = User::find($userId);
        if ($targetUser && $targetUser['role'] === 'instructor') {
            $this->fail('Instructors cannot reset passwords self-serve. Only the studio administrator can update instructor email and password.', 403);
        }

        $password = (string) $request->input('password', '');
        if (strlen($password) < 8) {
            $this->fail('Password must be at least 8 characters.', 422, ['password' => ['Too short.']]);
        }

        if ($password !== $request->input('password_confirmation')) {
            $this->fail('Passwords do not match.', 422, ['password_confirmation' => ['Passwords do not match.']]);
        }

        if (!PasswordReset::consume($token)) {
            $this->fail('This reset link is invalid or has expired. Please request a new one.', 419);
        }

        User::setPassword($userId, $password);
        ActivityLog::log($userId, 'password_reset', 'user', $userId);

        $this->success(['redirect' => '/login'], 'Password updated. Please sign in.');
    }
}
