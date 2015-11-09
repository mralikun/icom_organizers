<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConferenceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('conference', function($table)
		{
		    $table->integer('id')->primary()->index()->unsigned();
		    $table->string("name");
		    $table->date("from");
		    $table->date("to");
		    $table->string("venue");
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
		Schema::dropIfExists('conference');
	}

}
