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
            ['email' => 'yonogamekhelo@gmail.com'],
            [
                'name'     => 'Mann Chauhan',
                'password' => Hash::make('temp#@785612'),
            ]
        );


        // ── Portal settings ─────────────────────────────────────────────
        Setting::putMany([
            'portal_title'          => 'Yono Game Khelo',
            'portal_tagline'        => 'All Yono Games, Rummy Apps & Slots Games',
            'telegram_join_url'     => 'https://t.me/+omG',
            'whatsapp_number'       => '+918920858700',
            'brand_logo_url'        => null,
            'header_gradient_start' => '#6366F1',
            'header_gradient_end'   => '#8B5CF6',
            'theme_accent'          => '#6366F1',
            'site_disclaimer'       => "Yonogamekhelo.com is an independent platform. We don't own, manage, or operate any of the apps you see listed here. Our website is designed to help you discover and learn about different gaming options, but we don't control how those apps work or handle their services.\n\nIt's important to know that rummy apps can be addictive for some people. They also carry financial risks, especially if you're not careful with your spending. That's why we strongly recommend using these apps responsibly. If you choose to play, please set limits and stay aware of your habits.",
            'banned_states'         => 'Rummy, even as a skill-based game, is not legal everywhere. It is banned by the government in the following states: Andhra Pradesh, Sikkim, Nagaland, Assam, Arunachal Pradesh, Tamil Nadu, Odisha, and Telangana. If you live in any of these places, you should not download or play rummy apps.',
            'seo_keywords'          => 'Yono Game Khelo, Yono Rummy, Yono Slots, Yono Teen Patti, Yono VIP, Jaiho Rummy, Spin777, Rummy 365, All Yono Apps, Yono Download',
            'seo_description'       => 'Download All Yono Rummy, Slots & Teen Patti Apps from https://yonogamekhelo.com. Get ₹500–₹1500 Sign Up Bonus, Instant Withdraw & Secure Gaming 2026.',
            'social_card_url'       => '',
            'telegram_bot_token'    => '',
            'telegram_chat_id'      => '',
        ]);

       $this->call(AppSeeder::class);

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
