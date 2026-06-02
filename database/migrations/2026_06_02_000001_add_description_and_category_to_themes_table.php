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
        Schema::table('themes', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('category')->nullable()->after('description')->comment('e.g. Islamic, Adat, Modern, Nature, Minimalist');
            $table->text('custom_css')->nullable()->after('button_style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn(['description', 'category', 'custom_css']);
        });
    }
};
