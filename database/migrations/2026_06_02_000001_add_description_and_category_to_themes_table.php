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
            if (! Schema::hasColumn('themes', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('themes', 'category')) {
                $table->string('category')->nullable()->after('description')->comment('e.g. Islamic, Adat, Modern, Nature, Minimalist');
            }
            if (! Schema::hasColumn('themes', 'custom_css')) {
                $table->text('custom_css')->nullable()->after('button_style');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $cols = array_values(array_filter(
            ['description', 'category', 'custom_css'],
            fn (string $col) => Schema::hasColumn('themes', $col)
        ));

        if ($cols !== []) {
            Schema::table('themes', function (Blueprint $table) use ($cols) {
                $table->dropColumn($cols);
            });
        }
    }
};
