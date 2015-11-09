<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("attendance",function($table){
		    $table->increments('id');
		    $table->date("check_in");
		    $table->date("check_out");
		    $table->integer("organizer_id")->unsigned();
		    
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
		Schema::dropIfExists('attendance');
	}

}
