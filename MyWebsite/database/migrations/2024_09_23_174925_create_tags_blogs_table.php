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
        Schema::create('tags_blogs', function (Blueprint $table) {
            // $table->id();
            $table->foreignId("tag_id")->constrained()->onDelete('cascade');
            $table->foreignId("blog_id")->constrained()->onDelete('cascade');
            // $table->unsignedBigInteger('tag_id');
            // $table->unsignedBigInteger('blog_id');
            // $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags_blogs');
    }
};
