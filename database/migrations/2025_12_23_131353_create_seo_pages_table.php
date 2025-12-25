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
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('custom_hero_title')->nullable();
            $table->longText('custom_content')->nullable();
            $table->timestamps();
            
            $table->index(['service_id', 'location_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_pages');
    }
};
