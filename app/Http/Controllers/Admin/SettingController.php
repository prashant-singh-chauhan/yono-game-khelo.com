<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /** Keys managed by the portal settings form. */
    private const KEYS = [
        'portal_title', 'portal_tagline', 'telegram_join_url', 'whatsapp_number',
        'brand_logo_url', 'header_gradient_start', 'header_gradient_end', 'theme_accent',
        'site_disclaimer', 'banned_states', 'seo_keywords', 'seo_description',
        'social_card_url', 'telegram_bot_token', 'telegram_chat_id',
    ];

    public function index(): View
    {
        $settings = collect(self::KEYS)
            ->mapWithKeys(fn ($key) => [$key => Setting::get($key)])
            ->all();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'portal_title'          => 'required|string|max:255',
            'portal_tagline'        => 'required|string|max:255',
            'telegram_join_url'     => 'required|string|max:2048',
            'whatsapp_number'       => 'nullable|string|max:32',
            'brand_logo_url'        => 'nullable|string|max:2048',
            'header_gradient_start' => 'nullable|string|max:16',
            'header_gradient_end'   => 'nullable|string|max:16',
            'theme_accent'          => 'nullable|string|max:16',
            'site_disclaimer'       => 'nullable|string',
            'banned_states'         => 'nullable|string',
            'seo_keywords'          => 'nullable|string',
            'seo_description'       => 'nullable|string',
            'social_card_url'       => 'nullable|string|max:2048',
            'telegram_bot_token'    => 'nullable|string|max:255',
            'telegram_chat_id'      => 'nullable|string|max:255',
            'new_password'          => 'nullable|string|min:8|confirmed',
        ]);

        Setting::putMany(collect($validated)->only(self::KEYS)->all());

        if (! empty($validated['new_password'])) {
            /** @var User $user */
            $user = Auth::user();
            $user->forceFill(['password' => Hash::make($validated['new_password'])])->save();
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('status', 'Portal configuration saved successfully.');
    }
}
