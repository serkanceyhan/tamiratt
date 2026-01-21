<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('category', 255)->change();
        });
    }

    public function down(): void
    {
        // Reverting to previous state (assuming it was string before or unknown, simpler to leave as string or revert to enum if we knew the values)
        // For now, we keep it as string as it's safe.
    }
};
