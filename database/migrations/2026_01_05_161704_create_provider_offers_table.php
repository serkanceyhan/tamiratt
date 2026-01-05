<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->integer('timeline_days')->nullable(); // Estimated completion time
            $table->text('description');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
            
            $table->index(['quote_id', 'status']);
            $table->index('provider_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_offers');
    }
};
