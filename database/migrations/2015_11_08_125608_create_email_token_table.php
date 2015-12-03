<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTokenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("email_token",function($table){
		    $table->increments('id');
		    $table->string("token");
		    $table->integer("organizer_id")->unsigned();
		    $table->integer("task_id")->unsigned();
		    $table->timestamps();
		    
			$table->foreign('task_id')
			      ->references('id')->on('task')
			      ->onDelete('cascade');
			
			$table->foreign('organizer_id')
			      ->references('id')->on('organizer')
			      ->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('email_token');
	}

}
