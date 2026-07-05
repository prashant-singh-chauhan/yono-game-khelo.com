<?php

namespace Database\Seeders;

use App\Models\App;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AppSeeder extends Seeder
{
    /**
     * Seed the 53-app gaming catalog (from the live All Yono Games portal).
     */
    public function run(): void
    {
        // [name, sign-up bonus]; slug/rating/votes are derived deterministically.
        $samples = [
            ['IND Rummy', 100], ['Rummy Ludo', 100], ['INR Rummy', 580], ['Rumble Rummy', 566],
            ['Bingo 101', 850], ['Spin101', 90], ['TOP Rummy', 44], ['Spin 777', 220],
            ['Jaiho Slots', 444], ['Spin Winner', 46], ['Jaiho 91', 51], ['Joy Rummy', 56],
            ['JaiHo Rummy', 66], ['Slots Winner', 98], ['Spin Gold', 67], ['Jaiho 777', 41],
            ['Diwa VIP', 550], ['Yono Slots', 57], ['Diwa Slots', 531], ['Spin Crush', 100],
            ['Rummy 77', 66], ['Rummy Yono', 511], ['Club INR', 351], ['Good Slots', 51],
            ['Yes Spin', 336], ['OK Rummy', 46], ['Love Rummy', 66], ['Share Slots', 51],
            ['Jaiho Win', 44], ['IND Club', 46], ['Slots Spin', 88], ['MQM Bet', 51],
            ['Saga Slots', 51], ['ABC Rummy', 71], ['Jaiho Arcade', 66], ['Neta VIP', 50],
            ['Rummy 91', 66], ['Jaiho Spin', 51], ['101Z App', 544], ['Go Rummy', 51],
            ['789 Jackpot', 121], ['IND Slots', 88], ['Game Rummy', 229], ['MBM Bet', 51],
            ['567 Slots', 44], ['Yono Rummy', 661], ['Yono Games', 51], ['Maha Games', 66],
            ['Hindi 777', 151], ['Rummy 888', 88], ['Boss Rummy', 51], ['YN 777', 251],
            ['Yono Vip', 501],
        ];

        // Per-app logo overrides. Set a URL to show a real icon; null uses the
        // dynamic initials avatar fallback. One entry per app slug.
        $logos = [
            'ind-rummy'    => 'https://play-lh.googleusercontent.com/FPZ8q7DVRyRIl0BEPIHfmxowDqe6aNEOwrywScnLGLjAYPWK_bZg5Ehsl0aekK9AKxFEop_Ng7poHaykQOaP=w480-h960-rw',
            'rummy-ludo'   => 'https://tse-mm.bing.com/th?q=Rummy%20Ludo%20rummy%20slots%20app%20logo%20icon',
            'inr-rummy'    => 'https://tse-mm.bing.com/th?q=INR%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'rumble-rummy' => 'https://tse-mm.bing.com/th?q=Rumble%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'bingo-101'    => 'https://tse-mm.bing.com/th?q=Bingo%20101%20rummy%20slots%20app%20logo%20icon',
            'spin101'      => 'https://tse-mm.bing.com/th?q=Spin101%20rummy%20slots%20app%20logo%20icon',
            'top-rummy'    => 'https://tse-mm.bing.com/th?q=TOP%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'spin-777'     => 'https://tse-mm.bing.com/th?q=Spin%20777%20rummy%20slots%20app%20logo%20icon',
            'jaiho-slots'  => 'https://tse-mm.bing.com/th?q=Jaiho%20Slots%20rummy%20slots%20app%20logo%20icon',
            'spin-winner'  => 'https://tse-mm.bing.com/th?q=Spin%20Winner%20rummy%20slots%20app%20logo%20icon',
            'jaiho-91'     => 'https://tse-mm.bing.com/th?q=Jaiho%2091%20rummy%20slots%20app%20logo%20icon',
            'joy-rummy'    => 'https://tse-mm.bing.com/th?q=Joy%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'jaiho-rummy'  => 'https://tse-mm.bing.com/th?q=Jaiho%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'slots-winner' => 'https://tse-mm.bing.com/th?q=Slots%20Winner%20rummy%20slots%20app%20logo%20icon',
            'spin-gold'    => 'https://tse-mm.bing.com/th?q=Spin%20Gold%20rummy%20slots%20app%20logo%20icon',
            'jaiho-777'    => 'https://tse-mm.bing.com/th?q=Jaiho%20777%20rummy%20slots%20app%20logo%20icon',
            'diwa-vip'     => 'https://tse-mm.bing.com/th?q=Diwa%20VIP%20rummy%20slots%20app%20logo%20icon',
            'yono-slots'   => 'https://tse-mm.bing.com/th?q=Yono%20Slots%20rummy%20slots%20app%20logo%20icon',
            'diwa-slots'   => 'https://tse-mm.bing.com/th?q=Diwa%20Slots%20rummy%20slots%20app%20logo%20icon',
            'spin-crush'   => 'https://tse-mm.bing.com/th?q=Spin%20Crush%20rummy%20slots%20app%20logo%20icon',
            'rummy-77'     => 'https://tse-mm.bing.com/th?q=Rummy%2077%20rummy%20slots%20app%20logo%20icon',
            'rummy-yono'   => 'https://tse-mm.bing.com/th?q=Rummy%20Yono%20rummy%20slots%20app%20logo%20icon',
            'club-inr'     => 'https://tse-mm.bing.com/th?q=Club%20INR%20rummy%20slots%20app%20logo%20icon',
            'good-slots'   => 'https://tse-mm.bing.com/th?q=Good%20Slots%20rummy%20slots%20app%20logo%20icon',
            'yes-spin'     => 'https://tse-mm.bing.com/th?q=Yes%20Spin%20rummy%20slots%20app%20logo%20icon',
            'ok-rummy'     => 'https://tse-mm.bing.com/th?q=OK%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'love-rummy'   => 'https://tse-mm.bing.com/th?q=Love%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'share-slots'  => 'https://tse-mm.bing.com/th?q=Share%20Slots%20rummy%20slots%20app%20logo%20icon',
            'jaiho-win'    => 'https://tse-mm.bing.com/th?q=Jaiho%20Win%20rummy%20slots%20app%20logo%20icon',
            'ind-club'     => 'https://tse-mm.bing.com/th?q=IND%20Club%20rummy%20slots%20app%20logo%20icon',
            'slots-spin'   => 'https://tse-mm.bing.com/th?q=Slots%20Spin%20rummy%20slots%20app%20logo%20icon',
            'mqm-bet'      => 'https://tse-mm.bing.com/th?q=MQM%20Bet%20rummy%20slots%20app%20logo%20icon',
            'saga-slots'   => 'https://tse-mm.bing.com/th?q=Saga%20Slots%20rummy%20slots%20app%20logo%20icon',
            'abc-rummy'    => 'https://tse-mm.bing.com/th?q=ABC%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'jaiho-arcade' => 'https://tse-mm.bing.com/th?q=Jaiho%20Arcade%20rummy%20slots%20app%20logo%20icon',
            'neta-vip'     => 'https://tse-mm.bing.com/th?q=Neta%20VIP%20rummy%20slots%20app%20logo%20icon',
            'rummy-91'     => 'https://rummyapklist.com/wp-content/themes/traffic88-download-child-post-seo-pro/assets/logo.png',
            'jaiho-spin'   => 'https://play-lh.googleusercontent.com/IU6X0i1TMmcn5cTw0OHl84h-mYDAKtP9KeZAEBvGFb2rOuSprHuRpRmLoFZZfC6E4770eyXSrf6EuFGGu90pzg=w480-h960-rw',
            '101z-app'     => 'https://tse-mm.bing.com/th?q=101Z%20App%20rummy%20slots%20app%20logo%20icon',
            'go-rummy'     => 'https://tse-mm.bing.com/th?q=Go%20Rummy%20rummy%20slots%20app%20logo%20icon',
            '789-jackpot'  => 'https://tse-mm.bing.com/th?q=789%20Jackpot%20rummy%20slots%20app%20logo%20icon',
            'ind-slots'    => 'https://tse-mm.bing.com/th?q=IND%20Slots%20rummy%20slots%20app%20logo%20icon',
            'game-rummy'   => 'https://tse-mm.bing.com/th?q=Game%20Rummy%20rummy%20slots%20app%20logo%20icon',
            'mbm-bet'      => 'https://mbmbetapp.com/wp-content/uploads/2024/11/images-4-150x150.jpeg',
            '567-slots'    => 'https://play-lh.googleusercontent.com/5lpGlk_jGpDFBBaL_-VV-TkP912WiH6tPY-Gx1LmRaeQF1KoU0-bgQx9QDLKZfM0RgygBjj4MUT6Dv2c2cfR=w480-h960-rw',
            'yono-rummy'   => 'https://play-lh.googleusercontent.com/URtvQjds7nCxft96uTvfNQb-Ced37SuKLHjQlyrAS3D-yCONhVTxNgNu6tQZJ4nq5DKfks36K7rDVkePOW_A3Q=w480-h960-rw',
            'yono-games'   => 'https://play-lh.googleusercontent.com/uwja748ZUBayjf2qOP_qy2M_zYGyvMVtdmCq3FK58cmavWl9iJguLL7bN-SwSAVh6ZIwErq_4oFSEJsZD9FGew=w480-h960-rw',
            'maha-games'   => 'https://play-lh.googleusercontent.com/qrmq4gcHN7nHrdW1sAIyVCbonAoA3Iz8reaQhqajXr57dmiVnYvgkP5GCpMahsuvkvzEeptWohO6O5rwWhu_Wg=w480-h960-rw',
            'hindi-777'    => 'https://play-lh.googleusercontent.com/h55G8TWmUlcj53EsM2q5Q5FL0crX3CWax_gyNTwmPBPGcejrDSBkCi3Jjdi8qSYlOi-tZXcN0vPnJMwNI8Yw=w480-h960-rw',
            'rummy-888'    => 'https://yononewgamess.com/wp-content/uploads/2026/03/Rummy-888.jpg',
            'boss-rummy'   => 'https://mahayonogames.com/uploads/posts/image_7uyte286kh940sj35g1dwfr.webp',
            'yn-777'       => 'https://play-lh.googleusercontent.com/aNVcYr4Ipy2U8QM1CuzvNlmPlL-xuyu00P_lQXlujlvDgmqmXPTv-zP6-57epWQTdu7T6AQE_YB0H11-y3k9wg=w480-h960-rw',
            'yono-vip'     => 'https://play-lh.googleusercontent.com/qSQfOA0gRUWW28VOb3EGounJE4dhZHWA45OOc1HdHBYR82Of70wuF6b69j0NKd8UyL_dEYeDP7rDpBrtAL42YrU=w480-h960-rw',
        ];

        $ratingPool = [4.7, 4.5, 4.8, 4.4, 4.6, 4.9, 4.3, 4.8, 4.5, 4.7];
        $votePool = ['665K', '576K', '445K', '99.7K', '54.7K', '178K', '52.7K', '32.8K', '72K', '388K',
            '122K', '588K', '722K', '642K', '542K', '210K', '331K', '188K', '256K', '410K', '95.4K'];
        $total = count($samples);

        foreach ($samples as $i => [$name, $bonus]) {
            $slug   = Str::slug($name);
            $rating = $ratingPool[$i % count($ratingPool)];
            $votes  = $votePool[$i % count($votePool)];

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
                'logo_url'        => $logos[$slug] ?? null,
                'short_intro'     => "Register on {$name} and claim up to ₹{$bonus} welcome bonus instantly. Low withdrawal minimum of just ₹100.",
                'about_paragraph' => "{$name} is one of the most trusted rummy & slots platforms in 2026, offering secure gameplay, instant withdrawals and 24/7 support. Millions of players enjoy daily tournaments and lightning-fast payouts.",
                'features'        => "Welcome Bonus: Claim up to ₹{$bonus} instantly\nLow withdrawal: Minimum is ₹100\nMulti-language Support\nInstant UPI Withdrawals",
                'download_steps'  => "Click Download to get the APK file\nGo to Settings > Enable Unknown Sources\nOpen APK and tap Install\nVerify phone number and get bonus",
                'seo_title'       => "{$name} App Yono – Free Welcome Bonus ₹{$bonus}",
                'seo_keywords'    => "{$name}, {$name} download, {$name} APK",
                'promo_code'      => strtoupper(Str::slug($name, '')).$bonus,
                'created_at'      => now()->subHours(($total - $i) * 6),
                'updated_at'      => now()->subHours(($total - $i) * 6),
            ]);
        }
    }
}
