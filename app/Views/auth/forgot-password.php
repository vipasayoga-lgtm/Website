<?php

use App\Core\View;

$pageTitle = 'Reset password — Vipasa Yoga';
$pageCss = 'auth';
?>
<div class="auth-page auth-page--photo">
    <div class="auth-card">
        <div class="auth-card__logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" aria-hidden="true">
                <path d="M12 2C12 2 7 8 7 13a5 5 0 0 0 10 0c0-5-5-11-5-11Z"/>
            </svg>
        </div>
        <h1 class="auth-card__title">Forgot your password?</h1>
        <p class="auth-card__subtitle">Enter your registered email address to set a new password. <br><span class="text-muted" style="font-size:0.75rem;display:block;margin-top:4px">(Self-serve reset is for Clients and Admins. Trainer credentials are managed exclusively by the studio administrator.)</span></p>
        <form id="forgot-form" novalidate>
            <div class="form-group">
                <input type="email" id="email" name="email" class="form-group__control" placeholder=" " required autocomplete="email">
                <label class="form-group__label" for="email">Email address</label>
                <div class="form-group__hint"></div>
                <div class="form-group__error"></div>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-group__control" placeholder=" " required minlength="8" autocomplete="new-password">
                <label class="form-group__label" for="password">New password</label>
                <div class="form-group__hint">At least 8 characters.</div>
                <div class="form-group__error"></div>
            </div>
            <div class="form-group">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-group__control" placeholder=" " required minlength="8" autocomplete="new-password">
                <label class="form-group__label" for="password_confirmation">Confirm new password</label>
                <div class="form-group__hint"></div>
                <div class="form-group__error"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg" id="forgot-submit">Update password</button>
        </form>
        <p class="auth-card__footer"><a href="/login">Back to login</a></p>
    </div>
</div>
<script type="module">
import { apiPost } from '/assets/js/modules/api.js';
import toast from '/assets/js/modules/toast.js';
import { showFieldError, clearFieldError } from '/assets/js/modules/validation.js';

const form = document.getElementById('forgot-form');
const submitBtn = document.getElementById('forgot-submit');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const confirmInput = document.getElementById('password_confirmation');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clearFieldError(emailInput);
    clearFieldError(passwordInput);
    clearFieldError(confirmInput);

    if (passwordInput.value !== confirmInput.value) {
        showFieldError(confirmInput, 'Passwords do not match.');
        return;
    }

    submitBtn.classList.add('is-loading');
    submitBtn.disabled = true;

    try {
        const result = await apiPost('/api/auth/forgot-password', {
            email: emailInput.value.trim(),
            password: passwordInput.value,
            password_confirmation: confirmInput.value,
        });
        toast.success(result.message);
        setTimeout(() => { window.location.href = result.data.redirect; }, 1200);
    } catch (err) {
        if (err.errors) {
            Object.entries(err.errors).forEach(([field, messages]) => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) showFieldError(input, messages[0]);
            });
        }
        toast.error(err.message);
        submitBtn.classList.remove('is-loading');
        submitBtn.disabled = false;
    }
});
</script>
