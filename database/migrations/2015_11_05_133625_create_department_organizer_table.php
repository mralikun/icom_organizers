<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentOrganizerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("department_organizer",function($table){
		    
		    $table->increments('id');
		    $table->integer("organizer_id")->unsigned();
		    $table->integer("user_id")->unsigned();

			$table->foreign('organizer_id')
			      ->references('id')->on('organizer')
			      ->onDelete('cascade');
			
			$table->foreign('user_id')
			      ->references('id')->on('users')
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
		Schema::dropIfExists('department_organizer');
	}

}

//,,,,,,,,



//........

