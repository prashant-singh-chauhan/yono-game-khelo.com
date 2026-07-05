<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Query;
use App\Models\Review;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_apps'      => App::count(),
            'new_releases'    => App::where('is_new_release', true)->count(),
            'other_apps'      => App::where('is_new_release', false)->count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
            'unread_queries'  => Query::where('is_read', false)->count(),
        ];

        $recentApps = App::latest('updated_at')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentApps'));
    }
}
