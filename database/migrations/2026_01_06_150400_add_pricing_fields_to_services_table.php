<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('estimated_price_min', 10, 2)->nullable()->after('icon');
            $table->decimal('estimated_price_max', 10, 2)->nullable()->after('estimated_price_min');
            $table->decimal('lead_price', 10, 2)->default(50.00)->after('estimated_price_max');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['estimated_price_min', 'estimated_price_max', 'lead_price']);
        });
    }
};
