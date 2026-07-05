<?php

use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QueryController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ── Public website ──────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'storeContact'])->name('contact.store')->middleware('throttle:6,1');
Route::get('/disclaimer', [PublicController::class, 'disclaimer'])->name('disclaimer');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::get('/privacy', [PublicController::class, 'privacy'])->name('privacy');
Route::get('/app/{app:slug}', [PublicController::class, 'show'])->name('app.show');
Route::post('/app/{app:slug}/review', [PublicController::class, 'storeReview'])->name('app.review')->middleware('throttle:6,1');
Route::get('/search/suggest', [PublicController::class, 'suggest'])->name('search.suggest');

// ── SEO endpoints ───────────────────────────────────────────────────────
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [PublicController::class, 'robots'])->name('robots');

// ── Authentication ──────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Admin panel ─────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Apps CRUD (create/store before {app} to avoid conflicts)
    Route::get('apps', [AppController::class, 'index'])->name('apps.index');
    Route::get('apps/create', [AppController::class, 'create'])->name('apps.create');
    Route::post('apps', [AppController::class, 'store'])->name('apps.store');
    Route::get('apps/{app}/edit', [AppController::class, 'edit'])->name('apps.edit');
    Route::put('apps/{app}', [AppController::class, 'update'])->name('apps.update');
    Route::delete('apps/{app}', [AppController::class, 'destroy'])->name('apps.destroy');

    // User queries
    Route::get('queries', [QueryController::class, 'index'])->name('queries.index');
    Route::get('queries/{query}', [QueryController::class, 'show'])->name('queries.show');
    Route::delete('queries/{query}', [QueryController::class, 'destroy'])->name('queries.destroy');

    // Reviews moderation
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Portal settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
