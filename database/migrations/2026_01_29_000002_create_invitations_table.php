<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // e.g. "Romeo & Juliet"

            // Couple information
            $table->string('bride_name');
            $table->string('bride_nickname')->nullable();
            $table->string('bride_father')->nullable();
            $table->string('bride_mother')->nullable();
            $table->text('bride_photo')->nullable();

            $table->string('groom_name');
            $table->string('groom_nickname')->nullable();
            $table->string('groom_father')->nullable();
            $table->string('groom_mother')->nullable();
            $table->text('groom_photo')->nullable();

            // Event details
            $table->datetime('akad_date')->nullable();
            $table->string('akad_time')->nullable();
            $table->text('akad_venue')->nullable();
            $table->text('akad_address')->nullable();
            $table->string('akad_maps_link')->nullable();

            $table->datetime('resepsi_date')->nullable();
            $table->string('resepsi_time')->nullable();
            $table->text('resepsi_venue')->nullable();
            $table->text('resepsi_address')->nullable();
            $table->string('resepsi_maps_link')->nullable();

            // Media
            $table->text('cover_image')->nullable();
            $table->text('background_music')->nullable(); // URL youtube/soundcloud
            $table->json('gallery_images')->nullable();

            // Story (Love Story)
            $table->json('love_story')->nullable(); // array of story objects

            // Social media & Bank
            $table->json('bank_accounts')->nullable();
            $table->string('instagram_bride')->nullable();
            $table->string('instagram_groom')->nullable();

            // Customization (override theme settings)
            $table->json('custom_colors')->nullable();
            $table->json('custom_fonts')->nullable();
            $table->json('custom_styles')->nullable();

            // Settings
            $table->string('slug')->unique();
            $table->boolean('is_published')->default(false);
            $table->boolean('enable_rsvp')->default(true);
            $table->boolean('enable_wishes')->default(true);
            $table->boolean('enable_gallery')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
