<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            //  laravel 7+ approach to setup link and cascade
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('tweet_id')
                ->constrained()
                ->onDelete('cascade');;

            $table->boolean('liked'); // 0 disliked
            $table->timestamps();
            //  unique constraint on index
            //  we protected from situation when we have both like and dislike
            $table->unique(['user_id', 'tweet_id']);
            //  traditional way to setup link
//            $table->foreign('user_id')
//                ->references('id')
//                ->on('users')
//                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
