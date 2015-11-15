<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
				return Auth::user();
			}else{
				return false;
			}

		}else{
			return false;
		}

	}

	public function logout()
	{
		Auth::logout();
	}

}
