<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->enum('type', ['text', 'textarea', 'select', 'radio', 'checkbox', 'number'])->default('text');
            $table->json('options')->nullable(); // For select/radio/checkbox
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->foreignId('parent_question_id')->nullable()->constrained('service_questions')->onDelete('cascade');
            $table->json('show_condition')->nullable(); // Conditional display rules
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['service_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_questions');
    }
};
