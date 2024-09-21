<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('author_name');
            
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('last_update')->nullable();

            $table->integer('num_likes')->default(0); 
            $table->integer('num_tags')->default(0);  

        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
