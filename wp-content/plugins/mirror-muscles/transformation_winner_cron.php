<?php

//require_once('../../../wp-config.php');
//require_once('../../../wp-load.php');

define('_WP_ROOT_DIR', realpath(dirname(__FILE__).'/../../../') );

chdir(_WP_ROOT_DIR);

require_once(_WP_ROOT_DIR.'/wp-config.php');
require_once(_WP_ROOT_DIR.'/wp-load.php');


global $wpdb;

if(php_sapi_name() == 'cli'){
  $options = getopt('', array('key:'));
  if(isset($options['key'])){
    $_GET['key'] = $options['key'];
  }
}

if(isset($_GET['key']) && !empty($_GET['key'])){
	$key = $_GET['key'];
	$hashed = md5(NONCE_SALT);
	if( $key == $hashed ){
		$winner = set_transformation_winner();
		$winner['date'] = current_time( 'mysql' );
		file_put_contents('today_winner.txt',$winner);
	}else{
		die('Wrong secret key.');
	}
}

function set_transformation_winner(){
  global $wpdb;
  $bp_table = $wpdb->prefix . 'bp_xprofile_data'; 
  $mesur_table = $wpdb->prefix . 'standard_user_measurments';
  $query = $wpdb->prepare(
        "SELECT U.ID, M.* " .
        "FROM $bp_table B, $wpdb->users U " .
        "JOIN $mesur_table M ON M.user_id = U.ID " .
        "WHERE B.user_id = U.ID " .
        "AND DATE(M.added) >= DATE(curdate() - INTERVAL 3 MONTH - INTERVAL 7 DAY) " .
        "AND U.user_registered <= DATE_SUB(curdate(), INTERVAL 3 MONTH) " .
        "AND B.field_id = %d " .
        "AND B.value = %s " .
        "ORDER BY M.added ASC"
       , 2
       , 'Standard User'
    );

    $three_month_registered = $wpdb->get_results($query);

    $users_with_results = array();
    foreach ($three_month_registered as $key => $user) {
      $users_with_results[$user->ID][] = $user;
    }

    foreach($users_with_results as $uid=>$result){
      if (sizeof($users_with_results[$uid])>1){
        if(sizeof($users_with_results[$uid])==2){
          $first = reset($users_with_results[$uid]);
          $last = end($users_with_results[$uid]);
        }else{

          $last = end($users_with_results[$uid]);
        
          $dates = array();
          foreach($result as $key=>$value){
            $dates[] = $value->added;
          }

          $day_intervals = array();
          foreach($dates as $day){
            $dateStart = new DateTime(date('Y-m-d',strtotime('-3 months')));
            $dateEnd  = new DateTime(date('Y-m-d',strtotime($day)));
            $dateDiff = $dateStart->diff($dateEnd);
            $day_intervals[] = $dateDiff->days;
          }

          asort($day_intervals);
          $equal_day_intervals = array_unique( array_diff_assoc( $day_intervals, array_unique( $day_intervals ) ) );
          $key = ($equal_day_intervals) ? key($equal_day_intervals) : key($day_intervals);

          $first = $users_with_results[$uid][$key];

        }
        

        switch ($last->units) {
          case 'kg':
              $last_weight = number_format((float)$last->weight, 2, '.', '');
              break;
          case 'lbs':
              $last_weight =  number_format((float)$last->weight*0.45359237, 2, '.', '');
              break;
          case 'oz':
              $last_weight =  number_format((float)$last->weight*0.0283495231, 2, '.', '');
              break;
          default:
              $last_weight = number_format((float)$last->weight, 2, '.', '');
        }

        $last_fatmass = round(($last->bodyfat*$last_weight)/100,1);
        $last_leanmass = round($last_weight-$last_fatmass,1);


        $results[$uid]['user_id'] = $uid;
        $results[$uid]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$uid);
        $results[$uid]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$uid);
        $results[$uid]['first_bodyfat'] = $first->bodyfat;
        $results[$uid]['last_weight'] = $last_weight;
        $results[$uid]['last_bodyfat'] = $last->bodyfat;
        $results[$uid]['last_leanmass'] = $last_leanmass;
        $results[$uid]['last_fatmass'] = $last_fatmass;
        $results[$uid]['last_category'] = $last->category;
        $results[$uid]['result'] = $first->bodyfat - $last->bodyfat;
        $results[$uid]['added'] = $last->added;
      }
    }

    if(!empty($results)){
      usort($results, function($a, $b) {
          if($a['result']==$b['result']) return 0;
          return $a['result'] < $b['result'] ? 1 : -1;
      });
      
    $res = (string)$results[0]['result'];

      for($i=0; $i<sizeof($results); $i++){
        if($results[$i]['result'] == $res)
          $equal_results[$i] = $results[$i]; 
      }

      if(!empty($equal_results)){
        usort($equal_results, function($a, $b) {
            if($a['added']==$b['added']) return 0;
            return $a['added'] < $b['added'] ? 1 : -1;
        });
      }

      $results = (!empty($equal_results)) ? $equal_results : $results;

      $params = array(
                      'mm_winner_user_id'=> $results[0]['user_id'],
                      'mm_winner_firstname'=> $results[0]['firstname'],
                      'mm_winner_lastname'=> $results[0]['lastname'],
                      'mm_winner_first_bodyfat'=> $results[0]['first_bodyfat'],
                      'mm_winner_last_weight'=> $results[0]['last_weight'],
                      'mm_winner_last_bodyfat'=> $results[0]['last_bodyfat'],
                      'mm_winner_last_leanmass'=> $results[0]['last_leanmass'],
                      'mm_winner_last_fatmass'=> $results[0]['last_fatmass'],
                      'mm_winner_last_category'=> $results[0]['last_category'],
                      'mm_winner_result'=> $results[0]['result'],

                    );

      update_option("mm_transformation_winner", $params);

      return $results[0];
    }
    else
      return $results = array(); 
}


?>