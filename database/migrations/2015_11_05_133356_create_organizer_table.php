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
		    $table->string("name");
		    $table->date("dob")->nullable();
		    $table->string("email");
			$table->string("cell_phone");
			$table->string("address")->nullable();
			$table->boolean("gender");
		    $table->string("college")->nullable();
		    $table->integer("id_number")->nullable();
		    $table->string("language")->nullable();
		    $table->boolean("activity")->default("1");
		    $table->string("agreement")->nullable();
		    $table->timestamps();

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

