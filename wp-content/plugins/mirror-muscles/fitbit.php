<?php

require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('fitbit/fitbitphp.php');

global $wpdb;
global $bp;


$mm_fitbit_options = get_option("mm_fitbit_options");
$mm_fitbit_consumer_key = $mm_fitbit_options["mm_fitbit_consumer_key"];
$mm_fitbit_consumer_secret = $mm_fitbit_options["mm_fitbit_consumer_secret"];

error_reporting(E_ALL);
ini_set('display_errors', 'On');


$fitbit = new FitBitPHP($mm_fitbit_consumer_key, $mm_fitbit_consumer_secret);

if(isset($_GET['reset_fitbit_session']) && !empty($_GET['reset_fitbit_session'])){
	$fitbit->resetSession();
	unset($_SESSION['fitbit_Token']);
    unset($_SESSION['fitbit_Secret']);
	delete_user_meta($bp->loggedin_user->id,'fitbit_user_id');
	wp_redirect(home_url().'/my-fitbit');
	exit;
}

$fitbit->initSession(home_url().'/my-fitbit');
$fitbit->setResponseFormat('json');

//sk65cool@yandex.ru - 494D5C
//sk65cool@gmail.com - 457CC5

if(isset($_POST['action'])&&!empty($_POST['action']))
{
	
	$action = $_POST['action'];

	switch ($action) {

		case 'get-fitbit-calories':

			try
			{

		    	
				$callback['caloriesIn'] = $fitbit->getTimeSeries('caloriesIn', date( 'Y-m-d', strtotime($_POST['date']) ), '30d');
				$callback['caloriesOut'] = $fitbit->getTimeSeries('caloriesOut', date( 'Y-m-d', strtotime($_POST['date']) ), '30d');

		    	echo json_encode(array('success'=>$callback));

			} catch (Exception $e) {
				echo json_encode(array('error'=>$e->getMessage()));
				//print_r($e);
			}
		break;

		case 'get-fitbit-steps':

			try
			{
		    	
				$callback['steps'] = $fitbit->getTimeSeries('steps', date( 'Y-m-d', strtotime($_POST['date']) ), '30d');
		    	$callback['distance'] = $fitbit->getTimeSeries('distance', date( 'Y-m-d', strtotime($_POST['date']) ), '30d');

		    	echo json_encode(array('success'=>$callback));

			} catch (Exception $e) {
				echo json_encode(array('error'=>$e->getMessage()));
				//print_r($e);
			}
		break;

		case 'get-fitbit-sleep':

			try
			{
				$callback['sleep'] = $fitbit->getTimeSeries('minutesAsleep', date( 'Y-m-d', strtotime($_POST['date']) ), '30d');
		    	$callback['awakenings'] = $fitbit->getTimeSeries('awakeningsCount', date( 'Y-m-d', strtotime($_POST['date']) ), '30d');
		    	echo json_encode(array('success'=>$callback));

			} catch (Exception $e) {
				echo json_encode(array('error'=>$e->getMessage()));
				//print_r($e);
			}
		break;
		
		default:
			# code...
			break;
	}







}








?>