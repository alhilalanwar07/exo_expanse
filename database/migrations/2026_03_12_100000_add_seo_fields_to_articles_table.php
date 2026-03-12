<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('meta_description', 160)->nullable()->after('excerpt');
            $table->string('focus_keyword', 100)->nullable()->after('meta_description');
            $table->string('meta_keywords', 255)->nullable()->after('focus_keyword');
            $table->unsignedSmallInteger('reading_time')->nullable()->after('meta_keywords');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['meta_description', 'focus_keyword', 'meta_keywords', 'reading_time']);
        });
    }
};
