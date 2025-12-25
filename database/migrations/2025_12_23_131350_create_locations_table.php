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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->index();
            $table->enum('type', ['city', 'district']);
            $table->foreignId('parent_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->unique(['slug', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
