<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswakkris', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('social_platform', 40);
            $table->string('social_account', 150);
            $table->unsignedTinyInteger('age');
            $table->timestamp('last_submitted_at')->nullable()->index();
            $table->timestamps();

            $table->index(['social_platform', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswakkris');
    }
};
