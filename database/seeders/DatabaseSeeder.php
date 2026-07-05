<?php

namespace Database\Seeders;

use App\Models\App;
use App\Models\Query;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin account ───────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@yonogame.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );


        // ── Portal settings ─────────────────────────────────────────────
        Setting::putMany([
            'portal_title'          => 'Yono Game Khelo',
            'portal_tagline'        => 'All Yono Games, Rummy Apps & Slots Games',
            'telegram_join_url'     => 'https://t.me/+omG',
            'whatsapp_number'       => '+9198765432',
            'brand_logo_url'        => 'https://allnewyonoapps.com/logo.jpg',
            'header_gradient_start' => '#6366F1',
            'header_gradient_end'   => '#8B5CF6',
            'theme_accent'          => '#6366F1',
            'site_disclaimer'       => "Yonogamekhelo.com is an independent platform. We don't own, manage, or operate any of the apps you see listed here. Our website is designed to help you discover and learn about different gaming options, but we don't control how those apps work or handle their services.\n\nIt's important to know that rummy apps can be addictive for some people. They also carry financial risks, especially if you're not careful with your spending. That's why we strongly recommend using these apps responsibly. If you choose to play, please set limits and stay aware of your habits.",
            'banned_states'         => 'Rummy, even as a skill-based game, is not legal everywhere. It is banned by the government in the following states: Andhra Pradesh, Sikkim, Nagaland, Assam, Arunachal Pradesh, Tamil Nadu, Odisha, and Telangana. If you live in any of these places, you should not download or play rummy apps.',
            'seo_keywords'          => 'Yono Game Khelo, Yono Rummy, Yono Slots, Yono Teen Patti, Yono VIP, Jaiho Rummy, Spin777, Rummy 365, All Yono Apps, Yono Download',
            'seo_description'       => 'Download All Yono Rummy, Slots & Teen Patti Apps from https://allnewyonoapps.com. Get ₹500–₹1500 Sign Up Bonus, Instant Withdraw & Secure Gaming 2026.',
            'social_card_url'       => '',
            'telegram_bot_token'    => '',
            'telegram_chat_id'      => '',
        ]);

        // ── Sample gaming apps ──────────────────────────────────────────
        $samples = [
            ['Yono Vip', 'yono-vip', 551, 4.5, '665K'],
            ['YN 777', 'yn-777', 251, 4.8, '576K'],
            ['Boss Rummy', 'boss-rummy', 51, 4.5, '445K'],
            ['Rummy 888', 'rummy-888', 88, 4.4, '99.7K'],
            ['Hindi 777', 'hindi-777', 151, 4.6, '54.7K'],
            ['Maha Games', 'maha-games', 66, 4.5, '178.5K'],
            ['Yono Games', 'yono-games', 51, 4.7, '52.7K'],
            ['Yono Rummy', 'yono-rummy', 661, 4.7, '32.8K'],
            ['567 Slots', '567-slots', 44, 4.7, '72K'],
            ['MBM Bet', 'mbm-bet', 51, 4.3, '388K'],
            ['Game Rummy', 'game-rummy', 229, 4.8, '122K'],
            ['IND Slots', 'ind-slots', 88, 4.7, '588K'],
            ['789 Jackpot', '789-jackpot', 121, 4.5, '722K'],
            ['Go Rummy', 'go-rummy', 51, 4.8, '642K'],
            ['101Z App', '101z-app', 544, 4.8, '542K'],
            ['Spin 777', 'spin-777', 220, 4.9, '210K'],
            ['Jaiho Slots', 'jaiho-slots', 444, 4.8, '331K'],
            ['Yes Spin', 'yes-spin', 336, 4.3, '188K'],
        ];

        foreach ($samples as $i => [$name, $slug, $bonus, $rating, $votes]) {
            App::updateOrCreate(['slug' => $slug], [
                'name'            => $name,
                'download_link'   => 'https://example.com/download/'.$slug.'.apk',
                'is_new_release'  => true,
                'category'        => 'New Release',
                'sign_up_bonus'   => $bonus,
                'min_withdrawal'  => 100,
                'rating'          => $rating,
                'votes'           => $votes,
                'app_size'        => rand(38, 92).'MB',
                'short_intro'     => "Register on {$name} and claim up to ₹{$bonus} welcome bonus instantly. Low withdrawal minimum of just ₹100.",
                'about_paragraph' => "{$name} is one of the most trusted rummy & slots platforms in 2026, offering secure gameplay, instant withdrawals and 24/7 support. Millions of players enjoy daily tournaments and lightning-fast payouts.",
                'features'        => "Welcome Bonus: Claim up to ₹{$bonus} instantly\nLow withdrawal: Minimum is ₹100\nMulti-language Support\nInstant UPI Withdrawals",
                'download_steps'  => "Click Download to get the APK file\nGo to Settings > Enable Unknown Sources\nOpen APK and tap Install\nVerify phone number and get bonus",
                'seo_title'       => "{$name} App Yono – Free Welcome Bonus ₹{$bonus}",
                'seo_keywords'    => "{$name}, {$name} download, {$name} APK",
                'promo_code'      => strtoupper(Str::slug($name, '')).$bonus,
                'created_at'      => now()->subDays(21 - $i),
                'updated_at'      => now()->subDays(21 - $i),
            ]);
        }

        // ── Sample reviews ──────────────────────────────────────────────
        $vip = App::where('slug', 'yono-vip')->first();
        Review::updateOrCreate(
            ['author' => 'Prashant', 'app_id' => $vip?->id],
            ['rating' => 5, 'comment' => 'Hello', 'is_approved' => false, 'created_at' => now()->subSeconds(7)]
        );
        Review::updateOrCreate(
            ['author' => 'Rahul', 'app_id' => App::where('slug', 'yono-rummy')->value('id')],
            ['rating' => 5, 'comment' => 'Fast withdrawal, got my ₹100 in 2 minutes!', 'is_approved' => true]
        );

        // ── Sample user queries ─────────────────────────────────────────
        $queries = [
            ['DavidTaw', 'no.reply.PascalPersson@gmail.com', 'Do you desire to entice more customers for your business?', 'Hey! allyonogamesi.com, I noticed your page and wanted to reach out about growing your traffic...'],
            ['AK Hz', 'rankyounow.website@gmail.com', 'Helping New Teams Grow Organically', 'Hey team allyonogamesi.com, I would like to help you rank higher on Google with proven SEO...'],
            ['Jonnie Quinton', 'domains@search-allyonogamesi.com', 'Results for allyonogamesi.com', 'Dear Sir/Madam Place allyonogamesi.com in the top of Google search results...'],
            ['Singh', 'ananya@rocketdigitaltech.com', "Get Your Website on Google's First Page", 'Hello http://allyonogamesi.com, We can place your website on the first page of Google...'],
            ['Mark Colins', 'markcollins.web7@gmail.com', 'Question about your website', 'Hello, We recently ran a backend analysis of your site and found a few improvements...'],
            ['Lucy Johnson', 'lucyjohnson.web@gmail.com', 'Re: Increase traffic to your website', 'Hello allyonogamesi.com, I was going through your website and noticed...'],
            ['Jensen', 'pranabs.e.s1@gmail.com', 'Testing', "Hello! Your site 'Yono Game Khelo' could be reaching many more users..."],
        ];
        foreach ($queries as $j => [$sender, $email, $subject, $message]) {
            Query::updateOrCreate(
                ['email' => $email, 'subject' => $subject],
                [
                    'sender_name' => $sender,
                    'message'     => $message,
                    'received_at' => now()->subDays($j)->subHours(rand(1, 20)),
                ]
            );
        }
    }
}
