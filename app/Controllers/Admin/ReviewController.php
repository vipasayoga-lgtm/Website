<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Models\ActivityLog;
use App\Models\InstructorReview;

final class ReviewController extends Controller
{
    public function index(Request $request): void
    {
        $this->view('admin/reviews', [
            'reviews' => InstructorReview::allWithDetails(),
        ], 'dashboard');
    }

    public function toggleApproval(Request $request, int $id): void
    {
        $review = InstructorReview::find($id);

        if (!$review) {
            $this->fail('Review not found.', 404);
        }

        $isApproved = InstructorReview::toggleApproval($id);

        ActivityLog::log(Auth::id(), 'review_approval_toggled', 'instructor_review', $id, [
            'is_approved' => $isApproved,
        ]);

        $message = $isApproved
            ? 'Review approved for website frontpage.'
            : 'Review unapproved and removed from website frontpage.';

        $this->success(['is_approved' => $isApproved], $message);
    }

    public function update(Request $request, int $id): void
    {
        $review = InstructorReview::find($id);

        if (!$review) {
            $this->fail('Review not found.', 404);
        }

        $text = trim((string) $request->input('review_text', ''));
        $rating = $request->input('rating') !== null ? (int) $request->input('rating') : (int) $review['rating'];
        $isApproved = $request->input('is_approved') !== null ? (int) $request->input('is_approved') : (int) $review['is_approved'];

        if ($rating < 1 || $rating > 5) {
            $this->fail('Rating must be between 1 and 5.');
        }

        InstructorReview::updateReview($id, [
            'review_text' => $text,
            'rating' => $rating,
            'is_approved' => $isApproved,
        ]);

        ActivityLog::log(Auth::id(), 'review_updated', 'instructor_review', $id, [
            'review_text' => $text,
            'rating' => $rating,
            'is_approved' => $isApproved,
        ]);

        $this->success(null, 'Review comment updated and published successfully.');
    }

    public function delete(Request $request, int $id): void
    {
        $db = Database::connection();
        $db->prepare('DELETE FROM instructor_reviews WHERE id = :id')->execute(['id' => $id]);
        ActivityLog::log(Auth::id(), 'review_deleted', 'instructor_review', $id);

        $this->success(null, 'Review deleted successfully.');
    }
}
