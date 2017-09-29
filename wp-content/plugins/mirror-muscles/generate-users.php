<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../../wp-config.php');
require_once('../../../wp-load.php');

//generate standard users

function bpdd_get_random_date( $days_from = 30, $days_to = 0 ) {
	// 1 day in seconds is 86400
	$from = $days_from * rand( 10000, 99999 );

	// $days_from should always be less than $days_to
	if ( $days_to > $days_from ) {
		$days_to = $days_from - 1;
	}

	$to        = $days_to * rand( 10000, 99999 );
	$date_from = time() - $from;
	$date_to   = time() - $to;

	return date( 'Y-m-d H:i:s', rand( $date_from, $date_to ) );
}

function tz_list() {
    $zones_array = array();
    $zones_array_formated = array();
    $timestamp = time();
    foreach(timezone_identifiers_list() as $key => $zone) {
        date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['offset'] = (int) ((int) date('O', $timestamp))/100;
        $zones_array[$key]['diff_from_GMT'] = '(GMT'.date('P', $timestamp).')';
    }
    usort($zones_array, function ($item1, $item2) {
        if ($item1['offset'] == $item2['offset']) return 0;
        return $item1['offset'] < $item2['offset'] ? -1 : 1;
    });

    foreach ($zones_array as $key => $za)
        $zones_array_formated[] = $za['diff_from_GMT'].' '.$za['zone'];


    return $zones_array_formated;
}

$cities = require_once( dirname( __FILE__ ) . '/world_cities_array.php' );
$genders = array('Male','Female');
$timezones = array("(GMT-10:00) Pacific/Honolulu", "(GMT-09:00) Pacific/Gambier", "(GMT-08:00) America/Anchorage",
					"(GMT-07:00) America/Phoenix", "(GMT-06:00) America/Mexico_City", "(GMT-05:00) Pacific/Easter",
					"(GMT-05:00) America/Chicago", "(GMT-04:00) America/Tortola", "(GMT-03:00) America/Argentina/Rio_Gallegos",
					"(GMT-02:00) Atlantic/South_Georgia", "(GMT-01:00) America/Scoresbysund", "(GMT+00:00) Europe/London",
					"(GMT+01:00) Europe/Madrid","(GMT+02:00) Europe/Helsinki","(GMT+02:00) Africa/Lubumbashi",
					"(GMT+04:00) Asia/Yerevan","(GMT+05:00) Asia/Aqtobe","(GMT+06:00) Asia/Dhaka");

$specs = array("Alternative / Extreme Sports", "Alternative Medicine",
				"Athletics","Bodybuilding","Bootcamps","Boxing","Calisthenics","Cross Fit","Female training",
				"Fitness","Group Exercise","Martial Arts","MMA","Nutritionists","Olympic Lifting","Physiotherapy",
				"Pilates and Flexibility","Power Lifting","Sports Specific","Strength & Conditioning",
				"Strong Man","Weight Loss","Yoga / Pilates");

/*
for($i=1;$i<=500;$i++){

	$user_id = wp_insert_user( array(
       'user_login'      => 'Std'.$i,
       'user_pass'       =>	'Std'.$i,
       'display_name'    => 'Std'.$i.' User',
       'user_email'      => 'Std'.$i.'@mirrormuscles.com',
       'user_registered' => bpdd_get_random_date( 60, 1 ),
   ) );

	xprofile_set_field_data( 1, $user_id, 'Std'.$i );
	xprofile_set_field_data( 2, $user_id, 'User' );
	xprofile_set_field_data( 4, $user_id, 'standard' );
	xprofile_set_field_data(5, $user_id, rand(1936,2008).'-'.rand(1,12).'-'.rand(1,31).' 00:00:00' );
	xprofile_set_field_data(7, $user_id, $genders[array_rand($genders,1)] );
	xprofile_set_field_data(10, $user_id, $cities[array_rand($cities, 1 )] );
	xprofile_set_field_data(11, $user_id, '+'.rand( 111111111 , 9999999999 ) );
	xprofile_set_field_data( 100, $user_id, $timezones[array_rand($timezones, 1 )] );

	bp_set_member_type( $user_id, 'standard' );
		
	update_user_meta( $user_id, 'first_name', 'Std'.$i );
	update_user_meta( $user_id, 'last_name', 'User' );

	if ( function_exists( 'bp_update_user_last_activity' ) )
		bp_update_user_last_activity( $user_id, bpdd_get_random_date( 5 ) );

	bp_update_user_meta( $user_id, 'notification_messages_new_message', 'no' );
}
*/
/*
for($i=1;$i<=100;$i++){

	$user_id = wp_insert_user( array(
       'user_login'      => 'Pt'.$i,
       'user_pass'       =>	'Pt'.$i,
       'display_name'    => 'Pt'.$i.' User',
       'user_email'      => 'Pt'.$i.'@mirrormuscles.com',
       'user_registered' => bpdd_get_random_date( 60, 1 ),
   ) );

	xprofile_set_field_data(1, $user_id, 'Pt'.$i );
	xprofile_set_field_data(2, $user_id, 'User' );
	xprofile_set_field_data(4, $user_id, 'pt' );
	xprofile_set_field_data(5, $user_id, rand(1936,2008).'-'.rand(1,12).'-'.rand(1,31).' 00:00:00' );
	xprofile_set_field_data(7, $user_id, $genders[array_rand($genders,1)] );
	xprofile_set_field_data(10, $user_id, $cities[array_rand($cities, 1 )] );
	xprofile_set_field_data(11, $user_id, '+'.rand( 111111111 , 9999999999 ) );
	xprofile_set_field_data(12, $user_id, $specs[array_rand($specs, 1 )] );
	xprofile_set_field_data(100, $user_id, $timezones[array_rand($timezones, 1 )] );

	bp_set_member_type( $user_id, 'pt' );
		
	update_user_meta( $user_id, 'first_name', 'Pt'.$i );
	update_user_meta( $user_id, 'last_name', 'User' );

	if ( function_exists( 'bp_update_user_last_activity' ) )
		bp_update_user_last_activity( $user_id, bpdd_get_random_date( 5 ) );

	bp_update_user_meta( $user_id, 'notification_messages_new_message', 'no' );
}*/
/*

for($i=1;$i<=100;$i++){

	$user_id = wp_insert_user( array(
       'user_login'      => 'Gyms'.$i,
       'user_pass'       =>	'Gyms'.$i,
       'display_name'    => 'Gyms'.$i.' User',
       'user_email'      => 'Gyms'.$i.'@mirrormuscles.com',
       'user_registered' => bpdd_get_random_date( 60, 1 ),
   ) );

	xprofile_set_field_data(1, $user_id, 'Gyms'.$i );
	xprofile_set_field_data(3, $user_id, 'Gyms'.$i );
	xprofile_set_field_data(4, $user_id, 'gym' );
	xprofile_set_field_data(10, $user_id, $cities[array_rand($cities, 1 )] );
	xprofile_set_field_data(11, $user_id, '+'.rand( 111111111 , 9999999999 ) );
	xprofile_set_field_data(12, $user_id, $specs[array_rand($specs, 1 )] );
	xprofile_set_field_data(100, $user_id, $timezones[array_rand($timezones, 1 )] );

	bp_set_member_type( $user_id, 'gym' );
		
	update_user_meta( $user_id, 'first_name', 'Gyms'.$i);

	if ( function_exists( 'bp_update_user_last_activity' ) )
		bp_update_user_last_activity( $user_id, bpdd_get_random_date( 5 ) );

	bp_update_user_meta( $user_id, 'notification_messages_new_message', 'no' );
}*/