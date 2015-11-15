<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
class AuthController extends Controller {

	private function loginValidation($inputs){
		$validator = Validator::make(
				$inputs,
				[
					'name' => "required",
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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function login()
	{
		$inputs = Input::all();

		$validator = $this->loginValidation($inputs);

		if($validator){

			if (Auth::attempt(['name' => $inputs['name'], 'password' => $inputs['password']]))
			{
				return Redirect::to('/user/'.Auth::user()->name);

			}else{

				return Redirect::back()->withErrors(["The Name or Password are Not Right .. Please Check Them Again!!"]);

			}

		}else{

			return Redirect::back()->withErrors(["The Name And Password are Required .. Please Check Them Again!!"]);

		}

	}

	public function logout()
	{
		Auth::logout();
		return Redirect::to("/");
	}

	public function onlineUser(){
		return Auth::user();
	}

}
