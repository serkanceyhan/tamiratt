<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            
            // User & Service
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_service_id')->nullable()->constrained('services')->onDelete('set null');
            
            // Dynamic Answers
            $table->json('answers')->nullable(); // Question answers as JSON
            $table->text('description')->nullable(); // Additional notes
            
            // Location
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null'); // City/District
            $table->text('address')->nullable(); // Full address
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Timing
            $table->date('preferred_date')->nullable();
            $table->enum('preferred_time', ['morning', 'afternoon', 'evening', 'flexible'])->default('flexible');
            $table->enum('urgency', ['normal', 'urgent', 'emergency'])->default('normal');
            
            // Budget (optional)
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            
            // Contact
            $table->string('phone', 20);
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            
            // Status & Marketplace
            $table->enum('status', ['draft', 'pending_verification', 'open', 'locked', 'completed', 'cancelled', 'expired'])->default('draft');
            $table->string('media_token', 64)->nullable(); // For temporary media grouping
            $table->decimal('lead_price', 10, 2)->default(50.00);
            $table->foreignId('purchased_by')->nullable()->constrained('providers')->onDelete('set null');
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Metadata
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('source')->nullable(); // Where the request came from (seo page, direct, etc)
            
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('phone');
            $table->index('media_token');
            $table->index(['service_id', 'status']);
            $table->index(['location_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
