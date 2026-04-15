<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswakkri_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswakkri_id')->nullable()->constrained('siswakkris')->nullOnDelete();
            $table->string('name', 150)->index();
            $table->string('social_platform', 40);
            $table->string('social_account', 150);
            $table->unsignedTinyInteger('age');
            $table->boolean('replaced_previous')->default(false);
            $table->string('submitted_from_ip', 45)->nullable();
            $table->timestamp('submitted_at')->index();
            $table->timestamps();

            $table->index(['name', 'submitted_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswakkri_histories');
    }
};
