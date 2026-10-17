<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Models\Package;

final class ServiceController extends Controller
{
    public function index(Request $request): void
    {
        $packages = Package::featured();
        $user = Auth::user();

        if ($user && ($user['role'] ?? '') === 'client') {
            $this->view('services/index', [
                'packages' => $packages,
                'instructorsBase' => '/dashboard/instructors',
            ], 'dashboard');
            return;
        }

        $this->view('services/index', ['packages' => $packages], 'main');
    }
}
