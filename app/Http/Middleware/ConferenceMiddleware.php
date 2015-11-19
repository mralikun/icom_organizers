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
		$last_conference_id = Conference::last_id();
		$token = str_random(6)."1q2w3e4r".str_random(6).$last_conference_id;
		$url = "http://icomevents.tooonme.com/conference/information/".$token;
		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'method'  => 'GET',
		    ),
		);
		$context  = stream_context_create($options);
		$newConferences = file_get_contents($url, false, $context);


		foreach (json_decode($newConferences) as $conference) {
			$arr=[];
		
			foreach ($conference as $key => $value) {
				$arr[$key] = $value;
			}
		
			Conference::create($arr);
		}
		
		return $next($request);
	}

}
