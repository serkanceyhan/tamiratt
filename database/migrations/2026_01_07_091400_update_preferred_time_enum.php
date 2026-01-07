<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change preferred_time enum to include new values
        DB::statement("ALTER TABLE service_requests MODIFY COLUMN preferred_time ENUM('morning', 'afternoon', 'evening', 'flexible', 'specific', 'two_months', 'six_months', 'just_looking') DEFAULT 'specific'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE service_requests MODIFY COLUMN preferred_time ENUM('morning', 'afternoon', 'evening', 'flexible') DEFAULT 'flexible'");
    }
};
