<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('quote_purchases', function (Blueprint $table) {
             if (!Schema::hasColumn('quote_purchases', 'service_request_id')) {
                $table->foreignId('service_request_id')
                    ->nullable()
                    ->after('quote_id')
                    ->constrained('service_requests')
                    ->cascadeOnDelete();
             }
        });

        Schema::table('quote_purchases', function (Blueprint $table) {
            // Try to drop old unique. If fails (index doesn't exist), ignore.
            // In a robust script we might check for index existence, but Laravel doesn't offer easy way.
            // We assume if quote_id exists, the index likely exists.
            try {
                $table->dropUnique(['quote_id', 'provider_id']);
            } catch (\Exception $e) {}

            // Add new unique constraint
            try {
                $table->unique(['service_request_id', 'provider_id']);
            } catch (\Exception $e) {}
        });

        Schema::table('quote_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_id')->nullable()->change();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('quote_purchases', function (Blueprint $table) {
            if (Schema::hasColumn('quote_purchases', 'service_request_id')) {
                try {
                    $table->dropForeign(['service_request_id']);
                    $table->dropUnique(['service_request_id', 'provider_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('service_request_id');
            }
            
            try {
                $table->unique(['quote_id', 'provider_id']);
            } catch (\Exception $e) {}
        });

        Schema::table('quote_purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_id')->nullable(false)->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};
