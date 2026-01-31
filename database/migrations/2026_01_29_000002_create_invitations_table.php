<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('theme_id')->constrained('themes');
            
            // Basic Info
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('type')->default('wedding'); // wedding, engagement, birthday, etc.
            
            // Cover
            $table->string('cover_title')->nullable();
            $table->string('cover_subtitle')->nullable();
            $table->string('cover_photo')->nullable();
            
            // Event Date
            $table->dateTime('event_date')->nullable();
            
            // Content (JSON for flexible fields)
            $table->json('content')->nullable()->comment('Mempelai, Acara details');
            
            // Settings
            $table->json('settings')->nullable()->comment('Display toggles');
            
            // Music
            $table->boolean('music_enabled')->default(false);
            $table->string('music_url')->nullable();
            
            // Gift/Angpao
            $table->boolean('gift_enabled')->default(false);
            $table->json('gift_accounts')->nullable()->comment('Bank accounts for gift');
            
            // Features Toggle
            $table->boolean('countdown_enabled')->default(true);
            $table->boolean('rsvp_enabled')->default(true);
            $table->boolean('wishes_enabled')->default(true);
            
            // Status
            $table->boolean('is_published')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
