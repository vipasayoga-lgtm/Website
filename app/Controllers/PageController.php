<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Models\GalleryImage;
use App\Models\Instructor;
use App\Models\InstructorReview;
use App\Models\Package;
use App\Models\PageContent;
use App\Models\Setting;

final class PageController extends Controller
{
    public function splash(Request $request): void
    {
        $siteLogo = Setting::get('site_logo_path', '');
        $logoUrl = !empty($siteLogo) ? $siteLogo : '/assets/img/brand/loginlogo-exact.png';

        if ((Auth::check() && !$request->query('view') && !$request->query('preview')) || $request->query('splash') || $request->query('preview')) {
            $redirectTo = $request->query('redirect') ?: (Auth::check() ? Auth::redirectHome() : '/home');
            $this->view('splash', [
                'redirectTo' => $redirectTo,
                'logoUrl' => $logoUrl,
            ], 'bare');
            return;
        }

        $this->renderLandingPage();
    }

    public function home(Request $request): void
    {
        $this->renderLandingPage();
    }

    private function renderLandingPage(): void
    {
        $instructors = Instructor::searchActive();
        usort($instructors, static fn ($a, $b) => (float) $b['rating_avg'] <=> (float) $a['rating_avg']);

        $db = Database::connection();
        $instructorCount = (int) $db->query("SELECT COUNT(*) c FROM instructors WHERE status = 'active'")->fetch()['c'];
        $combinedYears = (int) $db->query("SELECT COALESCE(SUM(experience_years), 0) y FROM instructors WHERE status = 'active'")->fetch()['y'];
        $programCount = (int) $db->query("SELECT COUNT(*) c FROM packages WHERE is_active = 1")->fetch()['c'];
        $avgRatingRow = $db->query("SELECT AVG(rating_avg) r FROM instructors WHERE rating_count > 0 AND status = 'active'")->fetch();
        $avgRating = $avgRatingRow['r'] !== null ? round((float) $avgRatingRow['r'], 1) : null;

        $this->view('landing', [
            'packages' => Package::featured(),
            'programs' => $this->programs(),
            'instructors' => array_slice($instructors, 0, 3),
            'photos' => $this->galleryPhotos(),
            'stats' => [
                'instructors' => $instructorCount,
                'years' => $combinedYears,
                'programs' => $programCount,
                'rating' => $avgRating,
            ],
            'hero' => PageContent::getSection('hero') ?? PageContent::defaultHero(),
            'about' => PageContent::getSection('about') ?? PageContent::defaultAbout(),
            'testimonials' => PageContent::getSection('testimonials') ?? PageContent::defaultTestimonials(),
            'approvedReviews' => InstructorReview::approvedForFrontpage(6),
            'faqs' => PageContent::getSection('faqs') ?? PageContent::defaultFaqs(),
            'contact' => PageContent::getSection('contact') ?? PageContent::defaultContact(),
            'howItWorks' => PageContent::getSection('how_it_works') ?? PageContent::defaultHowItWorks(),
            'blogPosts' => PageContent::getSection('blog') ?? PageContent::defaultBlogPosts(),
        ], 'bare');
    }

    private function galleryPhotos(): array
    {
        $images = GalleryImage::allOrdered();

        if (empty($images)) {
            return $this->defaultGalleryImages();
        }

        foreach ($images as &$image) {
            $image['path'] = $image['file_path'];
        }
        unset($image);

        return $images;
    }

    public function privacy(Request $request): void
    {
        $contact = PageContent::getSection('contact') ?? PageContent::defaultContact();
        $this->view('legal/privacy', ['contact' => $contact, 'contactEmail' => $contact['email']], 'main');
    }

    public function terms(Request $request): void
    {
        $contact = PageContent::getSection('contact') ?? PageContent::defaultContact();
        $this->view('legal/terms', ['contact' => $contact, 'contactEmail' => $contact['email']], 'main');
    }

    public function refunds(Request $request): void
    {
        $contact = PageContent::getSection('contact') ?? PageContent::defaultContact();
        $this->view('legal/refunds', ['contact' => $contact, 'contactEmail' => $contact['email']], 'main');
    }

    private function defaultGalleryImages(): array
    {
        $photos = [
            ['file' => 'pose-plank-fold.webp', 'caption' => 'Plank to forward fold', 'category' => 'poses', 'imgPos' => 'center 35%'],
            ['file' => 'pose-shoulderstand.webp', 'caption' => 'Shoulder stand preparation', 'category' => 'inversions', 'imgPos' => 'center 15%'],
            ['file' => 'pose-side-stretch.webp', 'caption' => 'Seated side stretch', 'category' => 'poses', 'imgPos' => 'center 20%'],
            ['file' => 'pose-crow-1.webp', 'caption' => 'Crow pose (Bakasana)', 'category' => 'poses', 'imgPos' => 'center 20%'],
            ['file' => 'pose-crow-3.webp', 'caption' => 'Crow pose, close focus', 'category' => 'poses', 'imgPos' => 'center 25%'],
            ['file' => 'pose-plough.webp', 'caption' => 'Plough pose', 'category' => 'inversions', 'imgPos' => 'center 20%'],
            ['file' => 'pose-headstand-1.webp', 'caption' => 'Headstand practice', 'category' => 'inversions', 'imgPos' => 'center 10%'],
            ['file' => 'pose-headstand-2.webp', 'caption' => 'Headstand, full room view', 'category' => 'inversions', 'imgPos' => 'center 10%'],
            ['file' => 'pose-bridge.webp', 'caption' => 'Bridge pose', 'category' => 'poses', 'imgPos' => 'center 25%'],
            ['file' => 'pose-shoulderstand-2.webp', 'caption' => 'Shoulder stand, studio corner', 'category' => 'inversions', 'imgPos' => 'center 15%'],
            ['file' => 'pose-boat-balcony-2.webp', 'caption' => 'Boat pose, balcony view', 'category' => 'poses', 'imgPos' => 'center 30%'],
            ['file' => 'pose-plough-2.webp', 'caption' => 'Plough pose variation', 'category' => 'inversions', 'imgPos' => 'center 20%'],
            ['file' => 'about-journey.webp', 'caption' => 'A quiet moment before practice', 'category' => 'stillness', 'imgPos' => 'center 20%'],
            ['file' => 'philosophy-meditation.webp', 'caption' => 'Seated meditation', 'category' => 'stillness', 'imgPos' => 'center 20%'],
        ];

        foreach ($photos as &$photo) {
            $photo['path'] = '/assets/img/client/' . $photo['file'];
        }

        return $photos;
    }

    /**
     * Studio program offerings — marketing content, deliberately separate from
     * the service_types/packages tables that drive real booking (those model
     * session *format*: one-to-one/group/weekly/monthly; these are yoga
     * *styles*). Real client photography is used wherever it exists; a small
     * stock fallback covers styles this instructor hasn't been photographed
     * teaching yet (prenatal/postnatal/preconception/face/kids yoga).
     */
    private function programs(): array
    {
        $stored = PageContent::getSection('programs');

        if (!empty($stored)) {
            return $stored;
        }

        return [
            ['name' => 'Regular Yoga', 'img' => '/assets/img/client/pose-side-stretch.webp', 'imgPos' => 'center 20%', 'description' => 'A well-rounded practice combining strength, flexibility and breath — the foundation for everything else.'],
            ['name' => 'Yoga Therapy', 'img' => '/assets/img/client/pose-bridge.webp', 'imgPos' => 'center 20%', 'description' => 'Gentle, therapeutic sequences shaped around specific injuries, conditions, or recovery goals.'],
            ['name' => 'Preconception Yoga', 'img' => '/assets/img/programs/preconception-yoga.webp', 'imgPos' => 'center 15%', 'description' => 'Calming, fertility-supportive practice for those preparing for the journey ahead.'],
            ['name' => 'Prenatal Yoga', 'img' => '/assets/img/programs/prenatal-yoga.webp', 'imgPos' => 'center 20%', 'description' => 'Safe, trimester-aware sequences that keep you strong and comfortable through pregnancy.'],
            ['name' => 'Postnatal Yoga', 'img' => '/assets/img/programs/postnatal-yoga.webp', 'imgPos' => 'center 20%', 'description' => 'Gentle, recovery-focused practice to rebuild strength and ease back into movement after birth.'],
            ['name' => "Women's Wellness", 'img' => '/assets/img/client/philosophy-flow.webp', 'imgPos' => 'center 20%', 'description' => "Practice tailored to the rhythms of a woman's body — hormonal balance, energy, and stress relief."],
            ['name' => 'Mat Pilates', 'img' => '/assets/img/client/pose-boat-balcony.webp', 'imgPos' => 'center 30%', 'description' => 'Core-focused, controlled movement on the mat — building real strength with no special equipment.'],
            ['name' => 'Face Yoga', 'img' => '/assets/img/programs/face-yoga.webp', 'imgPos' => 'center 15%', 'description' => 'Gentle facial exercises and massage techniques to relax tension and support natural radiance.'],
            ['name' => 'Kids Yoga', 'img' => '/assets/img/programs/kids-yoga.webp', 'imgPos' => 'center 20%', 'description' => 'Playful, imaginative sessions that introduce children to movement, focus, and calm.'],
            ['name' => 'Private Session', 'img' => '/assets/img/client/pose-dramatic-stretch.webp', 'imgPos' => 'center 25%', 'description' => 'One-on-one attention, fully tailored to your goals, pace, and comfort level.'],
        ];
    }
}
