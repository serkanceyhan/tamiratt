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
        Schema::table('provider_offers', function (Blueprint $table) {
            // Add service_request_id column for new marketplace flow
            $table->foreignId('service_request_id')
                ->nullable()
                ->after('quote_id')
                ->constrained()
                ->cascadeOnDelete();
            
            // Make quote_id nullable for backward compatibility
            // NOTE: MySQL doesn't support modifying FK constraints directly,
            // so we drop and recreate
        });

        // Make quote_id nullable
        Schema::table('provider_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_offers', function (Blueprint $table) {
            $table->dropForeign(['service_request_id']);
            $table->dropColumn('service_request_id');
        });

        Schema::table('provider_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_id')->nullable(false)->change();
        });
    }
};

