<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->id();

            // Primary information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('download_link')->nullable();
            $table->boolean('is_new_release')->default(true);
            $table->string('category')->default('New Release');

            // Card specifications
            $table->unsignedInteger('sign_up_bonus')->default(50);
            $table->unsignedInteger('min_withdrawal')->default(100);
            $table->decimal('rating', 2, 1)->default(4.5);
            $table->string('votes')->default('10K');
            $table->string('app_size')->default('45MB');

            // Branding
            $table->string('logo_url')->nullable();
            $table->string('logo_path')->nullable();

            // Explanations & content
            $table->text('short_intro')->nullable();
            $table->longText('about_paragraph')->nullable();
            $table->text('features')->nullable();       // one feature per line
            $table->text('download_steps')->nullable(); // one step per line

            // SEO
            $table->string('seo_title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->text('seo_meta_description')->nullable();

            // Promo
            $table->string('promo_code')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apps');
    }
};
