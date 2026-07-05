<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppController extends Controller
{
    public function index(Request $request): View
    {
        $search   = $request->string('search')->toString();
        $category = $request->string('category')->toString();

        $apps = App::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($category === 'new', fn ($q) => $q->where('is_new_release', true))
            ->when($category === 'other', fn ($q) => $q->where('is_new_release', false))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.apps.index', compact('apps', 'search', 'category'));
    }

    public function create(): View
    {
        $app = new App;

        return view('admin.apps.form', compact('app'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data = $this->handleLogo($request, $data);

        $app = App::create($data);

        $this->notifyTelegram($app);

        return redirect()
            ->route('admin.apps.index')
            ->with('status', "\"{$app->name}\" has been created successfully.");
    }

    /**
     * Send a Telegram channel notification when a new app is added,
     * using the bot token + chat id configured in Portal Settings.
     */
    private function notifyTelegram(App $app): void
    {
        $token  = Setting::get('telegram_bot_token');
        $chatId = Setting::get('telegram_chat_id');

        if (blank($token) || blank($chatId)) {
            return; // Feature disabled until configured.
        }

        $message = "🎮 *New app added: {$app->name}*\n"
            ."💰 Sign-up bonus: ₹".number_format($app->sign_up_bonus)."\n"
            ."🏧 Min withdrawal: ₹".number_format($app->min_withdrawal)."\n"
            ."⭐ Rating: {$app->rating}\n"
            .route('app.show', $app);

        try {
            Http::timeout(8)->asForm()->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Telegram notification failed: '.$e->getMessage());
        }
    }

    public function edit(App $app): View
    {
        return view('admin.apps.form', compact('app'));
    }

    public function update(Request $request, App $app): RedirectResponse
    {
        $data = $this->validated($request, $app);
        $data = $this->handleLogo($request, $data, $app);

        $app->update($data);

        return redirect()
            ->route('admin.apps.index')
            ->with('status', "\"{$app->name}\" has been updated successfully.");
    }

    public function destroy(App $app): RedirectResponse
    {
        $name = $app->name;
        $app->delete();

        return back()->with('status', "\"{$name}\" has been deleted.");
    }

    private function validated(Request $request, ?App $app = null): array
    {
        $slugRule = 'nullable|string|max:255|unique:apps,slug';
        if ($app) {
            $slugRule .= ','.$app->id;
        }

        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'slug'                => $slugRule,
            'download_link'       => 'nullable|url|max:2048',
            'is_new_release'      => 'boolean',
            'sign_up_bonus'       => 'required|integer|min:0',
            'min_withdrawal'      => 'required|integer|min:0',
            'rating'              => 'required|numeric|min:0|max:5',
            'votes'               => 'required|string|max:32',
            'app_size'            => 'required|string|max:32',
            'logo_url'            => 'nullable|url|max:2048',
            'short_intro'         => 'nullable|string',
            'about_paragraph'     => 'nullable|string',
            'features'            => 'nullable|string',
            'download_steps'      => 'nullable|string',
            'seo_title'           => 'nullable|string|max:255',
            'seo_keywords'        => 'nullable|string|max:1024',
            'seo_meta_description'=> 'nullable|string',
            'promo_code'          => 'nullable|string|max:64',
            'logo'                => 'nullable|image|max:4096',
        ]);

        $validated['is_new_release'] = $request->boolean('is_new_release');
        $validated['category'] = $validated['is_new_release'] ? 'New Release' : 'Other';

        if (blank($validated['slug'] ?? null)) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        return $validated;
    }

    private function handleLogo(Request $request, array $data, ?App $app = null): array
    {
        unset($data['logo']);

        if ($request->hasFile('logo')) {
            if ($app && $app->logo_path) {
                \Storage::disk('public')->delete($app->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('app-logos', 'public');
        }

        return $data;
    }
}
