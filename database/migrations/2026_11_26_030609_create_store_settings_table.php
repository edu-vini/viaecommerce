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
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('Elisie Concept');
            $table->string('slogan')->default('Moderna. Elegante. Modesta.');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('contact_email')->nullable()->default('atendimento@elisie.com.br');
            $table->string('contact_phone')->nullable()->default('(11) 99842-1200');
            $table->string('contact_whatsapp')->nullable()->default('5511998421200');
            $table->string('contact_address')->nullable()->default('Av. Paulista, 1800 - Jardins, São Paulo - SP');
            $table->string('social_instagram')->nullable()->default('@elisieconcept');
            $table->string('social_facebook')->nullable()->default('elisieconcept');
            $table->string('social_pinterest')->nullable()->default('elisieconcept');
            $table->string('social_tiktok')->nullable()->default('@elisieconcept');

            // Branding Themes & Palettes
            $table->string('active_theme')->default('default'); // default, minimal_dark, clean_blue, emerald_green, sunset_orange, pastel_pink, custom
            $table->string('primary_color', 10)->default('#720403');
            $table->string('primary_hover', 10)->default('#580302');
            $table->string('primary_light', 10)->default('#fdf8f8');
            $table->string('secondary_color', 10)->default('#1c1917');
            $table->string('accent_color', 10)->default('#c49a45');
            $table->string('accent_hover', 10)->default('#a88132');
            $table->string('surface_color', 10)->default('#faf8f5');
            $table->string('border_color', 10)->default('#eee5db');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
