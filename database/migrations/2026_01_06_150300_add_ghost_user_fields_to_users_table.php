<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Phone might already exist from previous migration
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'phone_verified_at')) {
                $table->timestamp('phone_verified_at')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'is_ghost')) {
                $table->boolean('is_ghost')->default(false)->after('phone_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only drop columns that we might have added
            if (Schema::hasColumn('users', 'phone_verified_at')) {
                $table->dropColumn('phone_verified_at');
            }
            if (Schema::hasColumn('users', 'is_ghost')) {
                $table->dropColumn('is_ghost');
            }
        });
    }
};
