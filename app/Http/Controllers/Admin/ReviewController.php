<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $appId  = $request->integer('app');
        $rating = $request->integer('rating');

        $filtered = fn ($q) => $q->with('app')
            ->when($appId, fn ($sub) => $sub->where('app_id', $appId))
            ->when($rating, fn ($sub) => $sub->where('rating', $rating))
            ->latest();

        $pending  = $filtered(Review::where('is_approved', false))->get();
        $approved = $filtered(Review::where('is_approved', true))->get();

        // Apps that actually have reviews, for the filter dropdown.
        $apps = App::whereHas('reviews')->orderBy('name')->get(['id', 'name']);

        return view('admin.reviews.index', compact('pending', 'approved', 'apps', 'appId', 'rating'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return back()->with('status', 'Review approved and published.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Review deleted.');
    }
}
