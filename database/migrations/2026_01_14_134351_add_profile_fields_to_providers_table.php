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
        Schema::table('providers', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('phone');
            $table->integer('experience_years')->default(0)->after('bio');
            $table->decimal('rating', 3, 2)->default(0)->after('experience_years');
            $table->integer('reviews_count')->default(0)->after('rating');
            $table->integer('completed_jobs')->default(0)->after('reviews_count');
            $table->json('languages')->nullable()->after('completed_jobs');
            $table->string('working_hours')->nullable()->after('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'experience_years',
                'rating',
                'reviews_count',
                'completed_jobs',
                'languages',
                'working_hours',
            ]);
        });
    }
};
