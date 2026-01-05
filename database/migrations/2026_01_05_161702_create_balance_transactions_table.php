<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('balance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['credit', 'debit']); // credit = add, debit = subtract
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->unsignedBigInteger('related_quote_id')->nullable(); // If debit for lead unlock
            $table->unsignedBigInteger('related_package_id')->nullable(); // If credit for package purchase
            $table->string('payment_id')->nullable(); // İyzico transaction ID
            $table->timestamps();
            
            $table->index('provider_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('balance_transactions');
    }
};
