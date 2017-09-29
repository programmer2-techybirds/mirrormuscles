<?php
namespace Adcuz\FatSecret;

require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('fatsecret/Client.php');
require_once('fatsecret/FatSecretException.php');
require_once('fatsecret/OAuthBase.php');

	global $current_user;
	global $wpdb;

	$mm_fatsecret_options = get_option("mm_fatsecret_options");
	$mm_fatsecret_consumer_key = $mm_fatsecret_options["mm_fatsecret_consumer_key"];
	$mm_fatsecret_shared_secret = $mm_fatsecret_options["mm_fatsecret_shared_secret"];



	$action = $_POST['action'];

	switch ($action) {
		case 'search_ingredient':
			$client = new Client($mm_fatsecret_consumer_key, $mm_fatsecret_shared_secret);
			$search = $client->SearchFood($_POST['query']);

				if(!empty($search->foods->food))
					echo json_encode($search->foods->food);
				else
					echo json_encode(array('error'=>'no mathces'));

			break;
		
		case 'get_food':
			$client = new Client($mm_fatsecret_consumer_key, $mm_fatsecret_shared_secret);
			$food_id = $client->GetFood($_POST['food_id']);

				if(!empty($food_id->food->servings->serving))
					echo (count($food_id->food->servings->serving)>1) ? json_encode($food_id->food->servings->serving) : json_encode($food_id->food->servings);
				else
					echo json_encode(array('error'=>'no mathces'));
			
			break;
	}

?>