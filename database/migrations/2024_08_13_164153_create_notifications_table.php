<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id'); 
            $table->unsignedBigInteger('user_id'); 
            $table->unsignedBigInteger('post_id'); 
            $table->unsignedBigInteger('liked_by'); 
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('user_registration')->onDelete('cascade');
            $table->foreign('post_id')->references('user_post_id')->on('user_post')->onDelete('cascade');
            $table->foreign('liked_by')->references('user_id')->on('user_registration')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}


