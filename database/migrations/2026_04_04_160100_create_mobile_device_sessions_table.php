<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_device_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_alias')->nullable();
            $table->string('platform', 20)->default('unknown');
            $table->string('access_token_hash', 64)->unique();
            $table->string('refresh_token_hash', 64)->unique();
            $table->timestamp('access_expires_at');
            $table->timestamp('refresh_expires_at');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('revoked_at')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'revoked_at']);
            $table->index(['user_id', 'refresh_expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_device_sessions');
    }
};
