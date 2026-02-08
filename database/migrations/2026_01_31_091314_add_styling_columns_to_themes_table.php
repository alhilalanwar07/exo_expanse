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
            $table->string('primary_color')->nullable()->default('#FFFFFF');
            $table->string('secondary_color')->nullable()->default('#4E4E4E');
            $table->string('accent_color')->nullable()->default('#D4AF37');
            $table->string('text_color')->nullable()->default('#212529');
            $table->string('heading_color')->nullable()->default('#2C2C2C');
            $table->string('background_color')->nullable()->default('#F8F9FA');

            $table->string('heading_font')->nullable()->default('Italiana');
            $table->string('body_font')->nullable()->default('Roboto');
            $table->string('accent_font')->nullable()->default('Sacramento');

            $table->integer('container_max_width')->nullable()->default(440);
            $table->integer('heading_size')->nullable()->default(32);
            $table->string('border_radius')->nullable()->default('8px');

            $table->string('overlay_gradient')->nullable();
            $table->integer('overlay_opacity')->nullable()->default(60);
            $table->string('button_style')->nullable()->default('rounded');

            // Kolom view_file tetap ada tapi nullable jika kita mau pakai generic
            // $table->string('view_file')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn([
                'primary_color', 'secondary_color', 'accent_color',
                'text_color', 'heading_color', 'background_color',
                'heading_font', 'body_font', 'accent_font',
                'container_max_width', 'heading_size', 'border_radius',
                'overlay_gradient', 'overlay_opacity', 'button_style',
            ]);
        });
    }
};
