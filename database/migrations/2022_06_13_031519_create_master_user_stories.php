<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_user_stories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email_requestor');
            $table->string('email_receiver');
            $table->string('status');
            $table->timestamps('create_time');
            $table->timestamps('update_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_user_stories');
    }
};
