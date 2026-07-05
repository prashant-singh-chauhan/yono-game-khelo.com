<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\Models\Query;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PublicController extends Controller
{
    public function home(Request $request): View
    {
        $search = $request->string('q')->toString();
        $tab    = $request->string('tab')->toString() ?: 'all';

        $apps = App::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('slug', 'like', "%{$search}%"))
            ->when($tab === 'new', fn ($q) => $q->where('is_new_release', true))
            ->latest('id')
            ->get();

        $featured = App::orderByDesc('rating')->orderByDesc('sign_up_bonus')->limit(12)->get();

        return view('public.home', compact('apps', 'featured', 'search', 'tab'));
    }

    public function show(App $app): View
    {
        $reviews = $app->reviews()->where('is_approved', true)->latest()->get();
        $related = App::where('id', '!=', $app->id)->where('is_new_release', true)->inRandomOrder()->limit(4)->get();

        return view('public.show', compact('app', 'reviews', 'related'));
    }

    public function storeReview(Request $request, App $app): RedirectResponse
    {
        $data = $request->validate([
            'author'  => 'required|string|max:120',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $app->reviews()->create($data + ['is_approved' => false]);

        return back()->with('status', 'Thanks! Your review was submitted and is pending approval.');
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function about(): View
    {
        return view('public.about');
    }

    public function disclaimer(): View
    {
        return view('public.disclaimer');
    }

    public function terms(): View
    {
        return view('public.terms');
    }

    public function privacy(): View
    {
        return view('public.privacy');
    }

    /**
     * Instant search suggestions (JSON) for the navbar search bar.
     */
    public function suggest(Request $request): JsonResponse
    {
        $term = trim($request->string('q')->toString());

        if ($term === '') {
            return response()->json([]);
        }

        $results = App::query()
            ->where('name', 'like', "%{$term}%")
            ->orWhere('slug', 'like', "%{$term}%")
            ->orderByDesc('is_new_release')
            ->limit(6)
            ->get()
            ->map(fn (App $a) => [
                'name'  => $a->name,
                'slug'  => $a->slug,
                'url'   => route('app.show', $a),
                'logo'  => $a->logo(),
                'init'  => $a->initials(),
                'bonus' => '₹'.number_format($a->sign_up_bonus),
                'rating'=> (string) $a->rating,
                'new'   => (bool) $a->is_new_release,
            ]);

        return response()->json($results);
    }

    /**
     * Dynamic XML sitemap covering static pages + every app detail page.
     */
    public function sitemap(): Response
    {
        $apps = App::query()->select('slug', 'updated_at')->get();

        return response()
            ->view('public.sitemap', compact('apps'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * robots.txt pointing crawlers at the sitemap.
     */
    public function robots(): Response
    {
        $body = "User-agent: *\n"
            ."Allow: /\n"
            ."Disallow: /admin\n"
            ."Disallow: /login\n\n"
            .'Sitemap: '.url('/sitemap.xml')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sender_name' => 'required|string|max:120',
            'email'       => 'required|email|max:190',
            'subject'     => 'nullable|string|max:190',
            'message'     => 'required|string|max:5000',
            'attachment'  => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        Query::create($data + ['received_at' => now()]);

        return back()->with('status', 'Your message has been sent. We will get back to you soon!');
    }
}
