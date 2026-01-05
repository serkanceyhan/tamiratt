<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_paid', 10, 2); // Lead price at time of purchase
            $table->timestamp('purchased_at');
            $table->timestamps();
            
            // A provider can only unlock a quote once
            $table->unique(['quote_id', 'provider_id']);
            $table->index('provider_id');
            $table->index('purchased_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_purchases');
    }
};
