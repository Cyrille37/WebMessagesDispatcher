<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {

        	$table->increments('id');
            $table->string('from');
			$table->string('to');
			$table->text('body');
			$table->string('srv_name');
			$table->string('srv_addr');
			$table->dateTime('srv_at');
            $table->dateTime('proxy_at');
			$table->timestamps();

            $table->index('srv_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
