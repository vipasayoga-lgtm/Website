<?php

use App\Core\Router;

return static function (Router $router): void {
    // ---- Web (server-rendered) pages -------------------------------------
    $router->get('/', 'PageController@splash');
    $router->get('/home', 'PageController@home');
    $router->get('/privacy-policy', 'PageController@privacy');
    $router->get('/terms-of-service', 'PageController@terms');
    $router->get('/refund-policy', 'PageController@refunds');

    $router->get('/login', 'AuthController@loginPage', ['guest']);
    $router->get('/signup', 'AuthController@signupPage', ['guest']);
    $router->get('/forgot-password', 'AuthController@forgotPasswordPage', ['guest']);
    $router->get('/reset-password', 'AuthController@resetPasswordPage', ['guest']);
    $router->post('/logout', 'AuthController@logout', ['auth']);
    $router->get('/verify-email', 'AuthController@verifyEmail');
    $router->get('/auth/google', 'AuthController@googleRedirect', ['guest']);
    $router->get('/auth/google/callback', 'AuthController@googleCallback', ['guest']);

    $router->get('/onboarding/profile', 'OnboardingController@page', ['auth']);
    $router->get('/onboarding/get-started', 'OnboardingController@getStarted', ['auth', 'role:client']);
    $router->get('/onboarding/health-assessment', 'OnboardingController@healthAssessment', ['auth', 'role:client']);
    $router->get('/onboarding/wellness-profile', 'OnboardingController@wellnessProfile', ['auth', 'role:client']);
    $router->get('/onboarding/advanced-health', 'OnboardingController@advancedHealth', ['auth', 'role:client']);
    $router->get('/onboarding/complete-profile', 'OnboardingController@completeProfile', ['auth', 'role:client']);

    // Public marketing/browse pages — no login required. Only the actual
    // booking action (below) requires authentication.
    $router->get('/services', 'ServiceController@index');
    $router->get('/instructors', 'InstructorController@index');
    $router->get('/instructors/{id}', 'InstructorController@show');

    $router->get('/booking/schedule', 'BookingController@schedulePage', ['auth', 'role:client']);
    $router->get('/booking/confirm/{ref}', 'BookingController@confirmPage', ['auth', 'role:client']);
    $router->get('/booking/{ref}/success', 'BookingController@successPage', ['auth', 'role:client']);
    $router->get('/booking/{ref}/failed', 'BookingController@failedPage', ['auth', 'role:client']);
    $router->get('/booking/{ref}/invoice', 'BookingController@invoicePage', ['auth', 'role:client']);
    $router->get('/booking/{ref}/join', 'BookingController@joinPage', ['auth', 'role:client']);

    $router->get('/dashboard', 'DashboardController@index', ['auth']);
    $router->get('/dashboard/book', 'DashboardController@bookSession', ['auth', 'role:client']);
    $router->get('/dashboard/instructors', 'DashboardController@instructorsList', ['auth', 'role:client']);
    $router->get('/dashboard/instructors/{id}', 'DashboardController@instructorDetail', ['auth', 'role:client']);
    $router->get('/dashboard/bookings', 'DashboardController@bookings', ['auth', 'role:client']);
    $router->get('/dashboard/payments', 'DashboardController@payments', ['auth', 'role:client']);
    $router->get('/dashboard/invoices', 'DashboardController@invoices', ['auth', 'role:client']);
    $router->get('/dashboard/favorites', 'DashboardController@favorites', ['auth', 'role:client']);
    $router->get('/dashboard/notifications', 'DashboardController@notifications', ['auth', 'role:client']);

    $router->get('/admin', 'Admin\\DashboardController@index', ['auth', 'role:admin']);
    $router->get('/admin/bookings', 'Admin\\BookingController@index', ['auth', 'role:admin']);
    $router->post('/admin/bookings/{ref}/meeting-link', 'Admin\\BookingController@saveMeetingLink', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/bookings/{ref}/approve-payment', 'Admin\\BookingController@approvePayment', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/bookings/{ref}/reject-payment', 'Admin\\BookingController@rejectPayment', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/bookings/{ref}/reschedule', 'Admin\\BookingController@reschedule', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/bookings/{ref}/delete', 'Admin\\BookingController@delete', ['auth', 'role:admin', 'csrf']);
    $router->get('/admin/reports', 'Admin\\ReportController@page', ['auth', 'role:admin']);
    $router->get('/admin/reports/export', 'Admin\\ReportController@export', ['auth', 'role:admin']);

    $router->get('/admin/packages', 'Admin\\PackageController@index', ['auth', 'role:admin']);
    $router->post('/admin/packages', 'Admin\\PackageController@store', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/packages/{id}', 'Admin\\PackageController@update', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/packages/{id}/toggle-active', 'Admin\\PackageController@toggleActive', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/packages/{id}/delete', 'Admin\\PackageController@delete', ['auth', 'role:admin', 'csrf']);

    $router->get('/admin/instructors', 'Admin\\InstructorController@index', ['auth', 'role:admin']);
    $router->post('/admin/instructors', 'Admin\\InstructorController@store', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/instructors/{id}', 'Admin\\InstructorController@update', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/instructors/{id}/toggle-active', 'Admin\\InstructorController@toggleActive', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/instructors/{id}/delete', 'Admin\\InstructorController@delete', ['auth', 'role:admin', 'csrf']);

    $router->get('/admin/customers', 'Admin\\CustomerController@index', ['auth', 'role:admin']);
    // Must precede /admin/customers/{id} — {id} compiles to ([^/]+) and would
    // otherwise swallow "export" and look it up as customer #0.
    $router->get('/admin/customers/export', 'Admin\\CustomerController@export', ['auth', 'role:admin']);
    $router->get('/admin/customers/{id}', 'Admin\\CustomerController@show', ['auth', 'role:admin']);
    $router->get('/admin/customers/{id}/health-record', 'Admin\\CustomerController@healthRecord', ['auth', 'role:admin']);
    $router->post('/admin/customers/{id}/delete', 'Admin\\CustomerController@delete', ['auth', 'role:admin', 'csrf']);

    $router->get('/admin/payments', 'Admin\PaymentController@index', ['auth', 'role:admin']);
    $router->post('/admin/payments/{id}/approve', 'Admin\PaymentController@approve', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/payments/{id}/reject', 'Admin\PaymentController@reject', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/payments/{id}/delete', 'Admin\PaymentController@delete', ['auth', 'role:admin', 'csrf']);
    $router->get('/admin/reviews', 'Admin\ReviewController@index', ['auth', 'role:admin']);
    $router->post('/admin/reviews/{id}/toggle-approval', 'Admin\ReviewController@toggleApproval', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/reviews/{id}/update', 'Admin\ReviewController@update', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/reviews/{id}/delete', 'Admin\ReviewController@delete', ['auth', 'role:admin', 'csrf']);

    $router->get('/admin/settings', 'Admin\\SettingController@page', ['auth', 'role:admin']);
    $router->put('/admin/settings', 'Admin\\SettingController@save', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/settings/website', 'Admin\\SettingController@saveWebsite', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/settings/logo', 'Admin\\SettingController@uploadLogo', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/settings/google', 'Admin\\SettingController@saveGoogle', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/settings/payments', 'Admin\\SettingController@savePayments', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/settings/mail', 'Admin\\SettingController@saveMail', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/settings/security', 'Admin\\SettingController@saveSecurity', ['auth', 'role:admin', 'csrf']);
    $router->get('/admin/settings/backup/download', 'Admin\\SettingController@downloadBackup', ['auth', 'role:admin']);

    $router->get('/admin/notifications', 'Admin\\NotificationController@index', ['auth', 'role:admin']);
    $router->post('/admin/notifications', 'Admin\\NotificationController@store', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/notifications/{id}/delete', 'Admin\\NotificationController@delete', ['auth', 'role:admin', 'csrf']);

    $router->get('/admin/calendar', 'Admin\\CalendarController@index', ['auth', 'role:admin']);

    $router->get('/admin/content', 'Admin\\ContentController@index', ['auth', 'role:admin']);
    $router->put('/admin/content/hero', 'Admin\\ContentController@updateHero', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/content/about', 'Admin\\ContentController@updateAbout', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/content/contact', 'Admin\\ContentController@updateContact', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/content/testimonials', 'Admin\ContentController@updateTestimonials', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/content/faqs', 'Admin\ContentController@updateFaqs', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/content/programs', 'Admin\ContentController@updatePrograms', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/content/how-it-works', 'Admin\ContentController@updateHowItWorks', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/content/blog', 'Admin\ContentController@updateBlog', ['auth', 'role:admin', 'csrf']);

    $router->get('/admin/gallery', 'Admin\\GalleryController@index', ['auth', 'role:admin']);
    $router->post('/admin/gallery', 'Admin\\GalleryController@upload', ['auth', 'role:admin', 'csrf']);
    $router->put('/admin/gallery/{id}', 'Admin\\GalleryController@update', ['auth', 'role:admin', 'csrf']);
    $router->post('/admin/gallery/{id}/delete', 'Admin\\GalleryController@delete', ['auth', 'role:admin', 'csrf']);

    // ---- Trainer portal -----------------------------------------------------
    $router->get('/trainer', 'Trainer\\DashboardController@index', ['auth', 'role:instructor']);
    $router->group(['prefix' => '/trainer', 'middleware' => ['auth', 'role:instructor']], static function (Router $router): void {
        $router->get('/dashboard', 'Trainer\\DashboardController@index');

        $router->get('/bookings', 'Trainer\\BookingController@index');
        $router->post('/bookings/{ref}/accept', 'Trainer\\BookingController@accept', ['csrf']);
        $router->post('/bookings/{ref}/reject', 'Trainer\\BookingController@reject', ['csrf']);
        $router->post('/bookings/{ref}/delete', 'Trainer\\BookingController@delete', ['csrf']);
        $router->post('/reschedule-requests/{id}/approve', 'Trainer\\BookingController@approveReschedule', ['csrf']);
        $router->post('/reschedule-requests/{id}/decline', 'Trainer\\BookingController@declineReschedule', ['csrf']);

        $router->get('/calendar', 'Trainer\\CalendarController@index');
        $router->post('/calendar/availability', 'Trainer\\CalendarController@saveAvailability', ['csrf']);
        $router->post('/calendar/blocked-dates', 'Trainer\\CalendarController@blockDate', ['csrf']);
        $router->post('/calendar/blocked-dates/{id}/delete', 'Trainer\\CalendarController@unblockDate', ['csrf']);

        $router->get('/students', 'Trainer\\StudentController@index');
        $router->get('/students/{id}', 'Trainer\\StudentController@show');

        $router->get('/sessions/{ref}', 'Trainer\SessionController@show');
        $router->post('/sessions/{ref}', 'Trainer\SessionController@save', ['csrf']);
        $router->post('/sessions/{ref}/meeting-link', 'Trainer\SessionController@saveMeetingLink', ['csrf']);
        $router->post('/sessions/{ref}/complete', 'Trainer\SessionController@complete', ['csrf']);
        $router->post('/sessions/{ref}/resources', 'Trainer\\SessionController@uploadResource', ['csrf']);

        $router->get('/earnings', 'Trainer\\EarningsController@index');

        $router->get('/profile', 'Trainer\\ProfileController@page');
        $router->put('/profile', 'Trainer\\ProfileController@update', ['csrf']);
        $router->post('/profile/avatar', 'Trainer\\ProfileController@uploadAvatar', ['csrf']);
        $router->post('/profile/gallery', 'Trainer\\ProfileController@uploadGalleryImage', ['csrf']);
        $router->post('/profile/gallery/{id}/delete', 'Trainer\\ProfileController@deleteGalleryImage', ['csrf']);
        $router->post('/profile/certificates', 'Trainer\\ProfileController@uploadCertificateFile', ['csrf']);
        $router->post('/profile/certificates/{id}/delete', 'Trainer\\ProfileController@deleteCertificateFile', ['csrf']);

        $router->get('/notifications', 'Trainer\\NotificationController@index');
        $router->get('/reviews', 'Trainer\\ReviewController@index');
    });

    // ---- JSON API -----------------------------------------------------------
    $router->post('/api/auth/login', 'Api\\AuthController@login', ['guest', 'rate:login', 'csrf']);
    $router->post('/api/auth/signup', 'Api\\AuthController@signup', ['guest', 'rate:login', 'csrf']);
    $router->post('/api/auth/logout', 'Api\\AuthController@logout', ['auth', 'csrf']);
    $router->post('/api/auth/forgot-password', 'Api\\AuthController@forgotPassword', ['guest', 'rate:password_reset', 'csrf']);
    $router->post('/api/auth/reset-password', 'Api\\AuthController@resetPassword', ['guest', 'csrf']);

    $router->get('/api/profile', 'Api\\ProfileController@show', ['auth', 'role:client']);
    $router->put('/api/profile', 'Api\\ProfileController@update', ['auth', 'role:client', 'csrf']);
    $router->post('/api/profile/avatar', 'Api\\ProfileController@uploadAvatar', ['auth', 'role:client', 'csrf']);
    $router->post('/api/profile/change-password', 'Api\\ProfileController@changePassword', ['auth', 'role:client', 'csrf']);

    $router->get('/api/health-assessment', 'Api\\HealthAssessmentController@show', ['auth', 'role:client']);
    $router->post('/api/health-assessment/step', 'Api\\HealthAssessmentController@saveStep', ['auth', 'role:client', 'csrf']);
    $router->post('/api/consent', 'Api\\ConsentController@store', ['auth', 'role:client', 'csrf']);

    // Public browse endpoints — power the anonymous services/instructors pages
    // and the package-picker on an instructor's profile. Favoriting stays
    // gated since it writes personal data.
    $router->get('/api/services', 'Api\\ServiceController@index');
    $router->get('/api/services/{id}/packages', 'Api\\ServiceController@packages');

    $router->get('/api/instructors', 'Api\\InstructorController@index');
    $router->get('/api/instructors/{id}', 'Api\\InstructorController@show');
    $router->get('/api/instructors/{id}/availability', 'Api\\InstructorController@availability');
    $router->post('/api/instructors/{id}/favorite', 'Api\\InstructorController@toggleFavorite', ['auth', 'role:client', 'csrf']);

    $router->post('/api/coupons/validate', 'Api\\CouponController@validateCode', ['auth', 'role:client', 'csrf']);

    $router->post('/api/bookings', 'Api\\BookingController@store', ['auth', 'role:client', 'csrf']);
    $router->get('/api/bookings/{ref}', 'Api\\BookingController@show', ['auth', 'role:client']);
    $router->post('/api/bookings/{ref}/cancel', 'Api\\BookingController@cancel', ['auth', 'role:client', 'csrf']);
    $router->post('/api/bookings/{ref}/review', 'Api\\BookingController@review', ['auth', 'role:client', 'csrf']);
    $router->post('/api/bookings/{ref}/request-reschedule', 'Api\\BookingController@requestReschedule', ['auth', 'role:client', 'csrf']);

    $router->post('/api/payments/create-order', 'Api\\PaymentController@createOrder', ['auth', 'role:client', 'csrf']);
    $router->post('/api/payments/submit-proof', 'Api\\PaymentController@submitProof', ['auth', 'role:client', 'csrf']);
    $router->post('/api/payments/verify', 'Api\\PaymentController@verify', ['auth', 'role:client', 'csrf']);
    $router->post('/api/payments/{id}/approve', 'Api\\PaymentController@approve', ['auth', 'role:admin', 'csrf']);
    $router->post('/api/payments/{id}/reject', 'Api\\PaymentController@reject', ['auth', 'role:admin', 'csrf']);
    $router->post('/api/payments/{id}/delete', 'Api\\PaymentController@delete', ['auth', 'role:admin', 'csrf']);

    $router->get('/api/notifications', 'Api\\NotificationController@index', ['auth']);
    $router->post('/api/notifications/{id}/delete', 'Api\\NotificationController@delete', ['auth', 'role:admin', 'csrf']);

    $router->post('/api/contact', 'Api\\PublicController@submitContact', ['rate:contact', 'csrf']);
    $router->post('/api/newsletter/subscribe', 'Api\\PublicController@subscribeNewsletter', ['rate:contact', 'csrf']);

    // Server-to-server payment gateway webhooks (signature verified in controller)
    $router->post('/api/webhooks/razorpay', 'Api\\WebhookController@razorpay');
    $router->post('/api/webhooks/stripe', 'Api\\WebhookController@stripe');
};

