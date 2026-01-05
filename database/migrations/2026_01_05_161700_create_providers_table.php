<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('tax_number')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('verification_documents')->nullable(); // Media library paths
            $table->json('service_areas')->nullable(); // Selected categories and locations
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
