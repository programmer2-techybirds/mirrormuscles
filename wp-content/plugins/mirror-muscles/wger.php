<?php
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

global $wpdb;
global $bp;

$mm_wger_options = get_option("mm_wger_options");
$mm_wger_apikey = $mm_wger_options["mm_wger_apikey"];

class Wger {

    static public $base = 'https://wger.de/api/v2/';
    private $apiKey;

    function __construct($apiKey = false) {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function getKey() {
        return $this->apiKey;
    }

    function setKey($apiKey) {
        $this->apiKey = $apiKey;
    }

    
    public function getMuscles($id = false, $filter=false) {
        if(!$id)
        $requestUrl = Wger::$base.'exercise/?language=2&limit=300&status=2';
        
        $response = $this->getResponse($requestUrl);

        global $wpdb;
        foreach ($response->results as $key => $value) {
            
        	$wpdb->insert($wpdb->prefix.'workout_exercise',
        		array( 
                    'id' => $value->id,
        			'name' => $value->name,
        			'description' => $value->description,
        			'category' => $value->category,
        			'muscles' => json_encode($value->muscles),
        			'muscles_secondary' => json_encode($value->muscles_secondary),
        			'equipment' => json_encode($value->equipment),
				), 
				array(
                    '%d', 
					'%s',
					'%s',
					'%d',
					'%s',
					'%s',
					'%s'
				) );
        }
        return $response;
    }

    /* Private Methods */

    private function getResponse($requestUrl) {
        $ch = curl_init();
    	
    	curl_setopt($ch, CURLOPT_URL, $requestUrl);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	    $headers = array();
		$headers[] = 'Authorization: Token '.$this->getKey();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    	$response = curl_exec($ch);

    	curl_close($ch);

        $response = json_decode($response);
        
        $this->ErrorCheck($response);
        
        return $response;
    }

    private function ErrorCheck($response) {
        if (isset($response->error)) {
            throw new WgerException($response->error->message, (int) $response->error->code);
        }
    }

}


class WgerException extends Exception{
	
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}


$wger = new Wger($mm_wger_apikey);
print_r($mm_wger_apikey);
$muscle = $wger->getMuscles();

print_r($muscle);
 	



?>