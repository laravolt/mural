<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration
{

    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id')->unsigned();
            $table->morphs('commentable');
            $table->text('body');
            $table->string('room');
            $table->softDeletes();
            $table->timestamps();

            $table->index('room');
        });
    }

    public function down()
    {
        Schema::drop('comments');
    }
}
