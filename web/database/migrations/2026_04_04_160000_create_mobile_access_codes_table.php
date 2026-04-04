<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_access_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code_hash', 64)->unique();
            $table->string('code_prefix', 12)->index();
            $table->string('code_hint', 8)->nullable();
            $table->string('device_alias')->nullable();
            $table->string('platform', 20)->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable()->index();
            $table->string('consumed_by_ip', 45)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_access_codes');
    }
};
