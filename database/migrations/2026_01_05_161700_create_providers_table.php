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
            $table->string('phone')->nullable();
            $table->string('tax_number')->nullable();
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable(); // Admin notes for approval/rejection
            $table->json('service_areas')->nullable(); // Selected location IDs
            $table->json('service_categories')->nullable(); // Selected service category IDs
            $table->decimal('balance', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('activation_token', 64)->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('verification_status');
            $table->index('activation_token');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
