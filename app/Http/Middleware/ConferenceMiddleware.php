<?php namespace App\Http\Middleware;
use App\Conference;
use Closure;

class ConferenceMiddleware {

	/**
	 *
	 * This MiddleWare Will be fired at Every Request .
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
//		$last_conference_id = Conference::last_id();
		$token = str_random(6)."1q2w3e4r".str_random(6);
		$url = "http://icomevents.com/conference/information/".$token;
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'method'  => 'GET',
		    ),
		);
		$context  = stream_context_create($options);
		$allConferences =  file_get_contents($url, false, $context) ;

		$allConferences = json_decode($allConferences);

		$allCurrentConferences = Conference::all();


		if(count($allCurrentConferences) == 0){

			foreach ($allConferences as $conference) {
				$arr=[];

				foreach ($conference as $key => $value) {
					$arr[$key] = $value;
				}

				Conference::create($arr);
			}

		}else{

			$allConferencesIds = [];
			foreach($allConferences as $conference){

				$allConferencesIds[$conference->id] =[
						'id'	=> $conference->id,
						'name'	=> $conference->name,
						'from'	=> $conference->from,
						'to'	=> $conference->to,
						'venue'	=> $conference->venue
				];

			}

			$id = 0;

			foreach($allCurrentConferences as $currentConference){

				if( array_key_exists($currentConference->id, $allConferencesIds) ){

					$currentConference->update($allConferencesIds[$currentConference->id]);

					$id = $currentConference->id;

				}else{

					$currentConference->delete();

				}

			}

			foreach ($allConferencesIds as $key => $value) {

				if($key > $id){

					Conference::create($value);

				}
			}

		}


		return $next($request);
	}

}
