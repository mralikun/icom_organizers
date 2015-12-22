<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::all();
		return $users;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/*
	 * Validate the inputs
	 * */

	private function validate_inputs($inputs){
		$validator = Validator::make(
				$inputs,
				[
						'name' => "required|unique:users",
						'email' => "email",
						'password' => "required"
				]
		);


		if($validator->fails()){

			return false;

		}else{

			return true;
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = Input::all();

		if($this->validate_inputs($inputs)){

			if(Input::get('role') == "department" ||Input::get('role') == "operations" ){

				User::create($inputs);

			}else{

				return "error";

			}
		}
	}



	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$inputs =Input::all();

		if($this->validate_inputs($inputs)){

			if(Input::get('role') == "department" ||Input::get('role') == "operations" ){

				$user = User::find($id);
				$user->update($inputs);

			}else{

				return "error";
			}
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::find($id);

		$user->delete();
	}

	public function Home($username){
		if(Auth::user()->name != $username){
			abort('404');
		}
		return View::make("templates.master");
	}

}
