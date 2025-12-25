<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localized_strings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index(); // home.welcome_message
            $table->string('locale', 5)->default('tr')->index(); // tr, en
            $table->text('value'); // Hoş geldiniz!
            $table->string('group')->nullable()->index(); // home, footer, contact
            $table->text('description')->nullable(); // Admin için açıklama
            $table->timestamps();
            
            // Unique: aynı key + locale kombinasyonu tekrar edemez
            $table->unique(['key', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localized_strings');
    }
};
