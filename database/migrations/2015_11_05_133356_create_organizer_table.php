<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("organizer",function($table){
		    $table->increments('id');
		    $table->date("dob");
		    $table->string("cell_phone");
		    $table->date("college");
		    $table->integer("id_number");
		    $table->string("language");
		    $table->boolean("activity");
		    $table->string("agreement");

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('organizer');
	}

}

