<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->string('code', 6);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('purpose')->default('phone_verification'); // phone_verification, login, etc.
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['phone', 'code']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
