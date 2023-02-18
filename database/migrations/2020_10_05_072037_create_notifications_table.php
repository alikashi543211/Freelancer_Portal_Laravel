<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('created_by')->unsigned();
            $table->integer('thread_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
            $table->boolean('read')->default(false);
            $table->integer('type')->unsigned()->default(NOTIFICATION_MESSAGE);
            $table->timestamps();
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
