<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {

            $table->increments('id');
			$table->string('srv_name');
			$table->string('mod_name');
			$table->string('from');
			$table->string('to');
			$table->text('mod_params');
			$table->text('comment');
			$table->timestamps();

			$table->index(['srv_name','mod_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('routes');
    }
}
