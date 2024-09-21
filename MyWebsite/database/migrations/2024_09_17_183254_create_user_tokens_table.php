<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('tokenable_id');
            $table->string('tokenable_type');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')-> nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tokens');
    }
};
