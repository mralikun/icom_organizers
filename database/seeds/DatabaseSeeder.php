<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;


class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Model::unguard();

		$this->call('UserTableSeeder');

        $this->command->info('User table seeded!');
	}

}


class UserTableSeeder extends Seeder {
  
	private $departments = [
			"DMU",
			"Information Technology",
			"Media Production",
			"CMD",
			"Design and Printing",
			"Marketing",
			"Admin",
			"Finance",
			"Medical",
			"CME",
	];

    public function run()
    {
    	foreach ($this->departments as $department ) {
			$password = Hash::make("123456");
			User::create(["name" => $department , "password" => $password , "role" => "department"]);
    	}
    }

}
