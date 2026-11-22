<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            if (! Schema::hasColumn('themes', 'sections_config')) {
                $table->json('sections_config')->nullable()->after('custom_css');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('themes', 'sections_config')) {
            Schema::table('themes', function (Blueprint $table) {
                $table->dropColumn('sections_config');
            });
        }
    }
};
