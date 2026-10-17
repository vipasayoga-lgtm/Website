<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Favorite;
use App\Models\Instructor;
use App\Models\InstructorAvailability;
use App\Models\InstructorReview;
use App\Models\InstructorService;
use App\Models\Package;
use App\Models\ServiceType;

final class InstructorController extends Controller
{
    public function index(Request $request): void
    {
        $packageId = $request->query('package');
        $serviceTypeId = $request->query('service');

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

        $featured = Package::featured();
        $user = Auth::user();
        $layout = ($user && ($user['role'] ?? '') === 'client') ? 'dashboard' : 'main';
        $instructorsBase = ($user && ($user['role'] ?? '') === 'client') ? '/dashboard/instructors' : '/instructors';

        $this->view('instructors/index', [
            'instructors' => $instructors,
            'serviceTypes' => ServiceType::activeOrdered(),
            'selectedPackageId' => $packageId,
            'selectedServiceTypeId' => $serviceTypeId,
            'defaultPackageId' => $featured[0]['id'] ?? null,
            'instructorsBase' => $instructorsBase,
        ], $layout);
    }

    public function show(Request $request, string $id): void
    {
        $instructor = Instructor::findWithName((int) $id);
        $user = Auth::user();

        if (!$instructor || $instructor['status'] !== 'active') {
            $this->redirect($user && ($user['role'] ?? '') === 'client' ? '/dashboard/instructors' : '/instructors');
        }

        $layout = ($user && ($user['role'] ?? '') === 'client') ? 'dashboard' : 'main';

        $this->view('instructors/show', [
            'instructor' => $instructor,
            'reviews' => InstructorReview::forInstructor($instructor['id']),
            'availability' => InstructorAvailability::forInstructor($instructor['id']),
            'packageId' => $request->query('package'),
            'isFavorited' => Favorite::isFavorited(Auth::id(), (int) $instructor['id']),
        ], $layout);
    }
}
