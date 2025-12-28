<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('location_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Aynı hizmet-konum kombinasyonu tekrar ekleneməsin
            $table->unique(['service_id', 'location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('location_service');
    }
};
