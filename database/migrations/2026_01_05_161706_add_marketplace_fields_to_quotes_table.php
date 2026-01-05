<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->enum('status', ['open', 'locked', 'completed', 'cancelled'])->default('open')->after('message');
            $table->decimal('lead_price', 10, 2)->default(50.00)->after('status'); // Price to unlock
            $table->unsignedBigInteger('purchased_by')->nullable()->after('lead_price'); // Provider ID who unlocked
            
            $table->index('status');
            $table->index('purchased_by');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['status', 'lead_price', 'purchased_by']);
        });
    }
};
