<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('author_name');
            
            $table->unsignedBigInteger('user_id');
            
            // $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Use foreignId for user_id
            $table->timestamp('created_at')->useCurrent();
            // $table->timestamps("created_at");
            $table->timestamp('updated_at')->nullable();
            // $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL'));

            $table->integer('num_likes')->default(0); 
            $table->integer('num_tags')->default(0);  

        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
