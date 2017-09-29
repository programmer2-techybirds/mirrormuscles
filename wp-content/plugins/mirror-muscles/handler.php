<?php

//ini_set('display_errors', 1);
//
//ini_set('display_startup_errors', 1);
//
//error_reporting(E_ALL);


require_once('../../../wp-config.php');

require_once('../../../wp-load.php');



	global $current_user;
	
	global $wpdb;

    //check if existed username on registation form
	
	if(isset($_POST['action']) && $_POST['action']=='check-username-exist'){

        echo (username_exists($_POST['signup_username'])) ? "false" : "true";

    }



    //check if existed email on registation form

    if(isset($_POST['action']) && $_POST['action']=='check-user-email-exist'){

        echo (email_exists($_POST['signup_email'])) ? "false" : "true";

    }

    //fillin empty required xpprofile fields

    if( isset($_POST['action']) && $_POST['action']=='fill_in_required'){

        $fields = $_POST;

        $member_type = $_POST['field_4'];

        

        //update xprofile fields from fillin required form

        foreach($fields as $key=>$value){

            if (strpos($key, 'field_') !== false && $key != 'filed_5_year' && $key != 'filed_5_month' && $key != 'filed_5_day'){

                if( $key == 'field_4'){

                    bp_set_member_type( $current_user->ID, $member_type );

                }

                preg_match_all('!\d+!', $key, $field);                    

                xprofile_set_field_data($field[0][0], $current_user->ID, $value );

            }else{//if this is Birthday field

                $date = date('Y-m-d', strtotime($_POST['field_5_year'] . '-' . $_POST['field_5_month'] . '-' . $_POST['field_5_day']) );

                xprofile_set_field_data(5, $current_user->ID, $date.' 00:00:00' );

            }

		}
		
        //remove not required member type fields, wich grabbs from social login

        if($member_type == 'gym'){

            xprofile_delete_field_data(1, $current_user->ID ); //remove First Name

            xprofile_delete_field_data(2, $current_user->ID ); //remove Last Name

            xprofile_delete_field_data(5, $current_user->ID ); //remove Birthday

            xprofile_delete_field_data(7, $current_user->ID ); //remove Gender

            wp_update_user( array( 'ID' => $current_user->ID,

                                    'first_name' => $_POST['field_3'],

                                    'last_name' => $_POST['field_3'],

                                    'display_name' => $_POST['field_3'] ) );

        }   

          else

            wp_update_user( array( 'ID' => $user_id,

                                    'first_name' => $_POST['field_1'],

                                    'last_name' => $_POST['field_2'],

                                    'display_name' => $_POST['field_1'].' '.$_POST['field_2'] ) );

        wp_redirect(bp_loggedin_user_domain().'/profile');

    }

	
	if(!empty($_POST)&&isset($_POST['upload_progress_image'])){
		
        $new_post = array(

            'post_title' => 'Progress by '.$current_user->user_nicename.' on '.current_time('Y-m-d H:i:s'),

            'post_status' => 'publish',

            'post_date' => current_time('Y-m-d H:i:s'),

            'post_author' => $current_user->ID,

            'post_type' => 'user-progress-image'

        );
		
        $post_id = wp_insert_post($new_post);


        if (!function_exists('wp_generate_attachment_metadata')){

            require_once(ABSPATH . "wp-admin" . '/includes/image.php');

            require_once(ABSPATH . "wp-admin" . '/includes/file.php');

            require_once(ABSPATH . "wp-admin" . '/includes/media.php');

        }

        if ($_FILES) {

            foreach ($_FILES as $file => $array) {

                if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {

                    return "upload error : " . $_FILES[$file]['error'];

                }

                $attach_id = media_handle_upload( $file, $post_id );

            }   

        }

        if ($attach_id > 0){

            update_post_meta($post_id,'_thumbnail_id',$attach_id);

        }

        wp_redirect( home_url()."/my-progress" ); exit;

    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'delete-progress-image'){

        global $wpdb;

        delete_post_thumbnail( $_POST['post_id'] );

        $deleted = wp_delete_post($_POST['post_id'],true);

        return $deleted;

    }
	
	
    if(!empty($_POST) && isset($_POST['save_bfc'])){

        $data = array();

        parse_str($_POST['data'], $data);

        $table_name = $wpdb->prefix . 'bfc_results';

        $exist = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE user_id = ".$data['current_user']." AND DATE(added) = CURDATE()");



        if($exist){

            echo json_encode(array('error'=>'exist'));

            exit;

        }



        $skinfolds = $data['chest']+$data['axilla']+$data['triceps']+$data['subscapular']+$data['abdominal']+$data['suprailiac']+$data['thigh'];

        switch ($data['units']) {

                case 'kg':

                    $weight = number_format((float)$data['weight'], 2, '.', '');

                    break;

                case 'lbs':

                    $weight =  number_format((float)$data['weight']*0.45359237, 2, '.', '');

                    break;

                case 'oz':

                    $weight =  number_format((float)$data['weight']*0.0283495231, 2, '.', '');

                    break;

                default:

                    $weight = number_format((float)$data['weight'], 2, '.', '');

              }



        $bodydensity = ( $data['gender'] == 'Male' ) ? 1.112-(0.00043499*$skinfolds)+(0.00000055*$skinfolds*$skinfolds)-(0.00028826*$data['age']) : 1.097-(0.00046971*$skinfolds)+(0.00000056*$skinfolds*$skinfolds)-(0.00012828*$data['age']);

        $bodyfat = round(((4.95/$bodydensity)-4.5)*100,2);

        $fatmass = round(($bodyfat*$data['weight'])/100,1);

        $leanmass = round($data['weight']-$fatmass,1);



        if( $data['gender']=='Male' )

        {

          if($bodyfat <= 4 )

              $category = 'Competitor';

          else if ($bodyfat>4&&$bodyfat<=14)

              $category = 'Athletes';

          else if($bodyfat>14&&$bodyfat<=18)

              $category = 'Fitness';

          else if($bodyfat>18&&$bodyfat<=26)

              $category = 'Acceptable';

          else if($bodyfat>26)

              $category = 'Obese';

        }

        else

        {

          if($bodyfat <= 12 )

              $category = 'Competitor';

          else if ($bodyfat>12&&$bodyfat<=21)

              $category = 'Athletes';

          else if($bodyfat>21&&$bodyfat<=25)

              $category = 'Fitness';

          else if($bodyfat>25&&$bodyfat<=32)

              $category = 'Acceptable';

          else if($bodyfat>32)

              $category = 'Obese';

        }

        $inserted = $wpdb->insert($table_name, array(

            'user_id' => $current_user->ID,

            'gender' => $data['gender'],

            'age' => $data['age'],

            'weight' => $data['weight'],

            'chest' => $data['chest'],

            'axilla' => $data['axilla'],

            'triceps' => $data['triceps'],

            'subscapular' => $data['subscapular'],

            'abdominal' => $data['abdominal'],

            'suprailiac' => $data['suprailiac'],

            'thigh' => $data['thigh'],

            'fatmass' => $fatmass,

            'leanmass' => $leanmass,

            'bodyfat' => $bodyfat,

            'category' => $category,

            'units' => $data['units'],

            'added' => current_time('Y-m-d H:i:s')

        ));



        echo json_encode(array('success'=>$inserted));    
    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'delete-prev-bfc-result'){

        $table_name = $wpdb->prefix . 'bfc_results';

        $table_name2 = $wpdb->prefix . 'sharing_requests';



        $deleted = $wpdb->delete( $table_name, array( 'id' => $_POST['result_id']) );

        //delete trainer sharing requests for this result

        $deleted = $wpdb->delete( $table_name2, array( 'result_id' => $_POST['result_id']) );

        if($deleted)

            return true;

        return false;

    }



    if(!empty($_POST) && isset($_POST['bfc_share_wall'])){

        global $bp;

        $action =  'New Body Fat Calculator Result';

        $activity_content = $_POST['text'];



        bp_activity_add ( array(

            'action' => $action,

            'content' => $activity_content,

            'component' => 'activity',

            'type' => 'activity_update',

            'user_id' => $bp->loggedin_user->id,

            'item_id' => $bp->displayed_user->id

        ));

    }



    if(!empty($_POST) && isset($_POST['bfc_share_email'])){

                                    

        $to = $_POST['email'];

        $subject = $_POST['subject'];

        $body = $_POST['text'];

        $headers = array('Content-Type: text/html; charset=UTF-8');



        wp_mail( $to, $subject, $body, $headers );

        

        //wp_redirect( $_SERVER['HTTP_REFERER'] );

        //exit();

    }





    if(!empty($_POST) && isset($_POST['photo_share_wall'])){

                                    

        global $bp;



        //get user-progress-image posts id

        $post_thumbnail_id = get_post_thumbnail_id( $_POST['post_id'],'full' );

        

        //get filename of the progress image(returns array( [0] =>...))

        $filename = get_post_meta( $post_thumbnail_id,'_wp_attached_file' );

        

        $wp_upload_dir = wp_upload_dir();



        //set title and content of the activity post

        $mpi_options = get_option("mpi_options");

        $activity_title =  'New Photo Progress';

        $activity_content = $mpi_options["mpi_share_photo_desc"].' Progress on the '.$_POST['published'];



        //save activity post and return saver post id

        $activity_id = bp_activity_add ( array(

            'action' => $activity_title,

            'content' => $activity_content,

            'component' => 'activity',

            'type' => 'activity_update',

            'user_id' => $bp->loggedin_user->id,

            'item_id' => $bp->displayed_user->id

        ));

        

        // Check the type of file. We'll use this as the 'post_mime_type'.

        $filetype = wp_check_filetype( basename( $filename[0] ), null );



        // Prepare an array of post data for the attachment.

        $attachment = array(

            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename[0] ), 

            'post_mime_type' => $filetype['type'],

            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename[0] ) ),

            'post_content'   => '',

            'post_status'    => 'inherit'

        );



        // Insert the attachment and conect it with activity post id.

        $attachment_id = wp_insert_attachment( $attachment, $filename[0], $activity_id );

        

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.

        require_once( ABSPATH . 'wp-admin/includes/image.php' );



        // Generate the metadata for the attachment, and update the database record.NOTICE $wp_upload_dir['path']!!!

        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $wp_upload_dir['path'] . '/' . basename($filename[0]) );

        wp_update_attachment_metadata( $attachment_id, $attachment_data );

        set_post_thumbnail( $activity_id, $attachment_id );

        

        //insert info about attachment and activity to media table

        $wpdb->insert(

                $wpdb->prefix . 'buddyboss_media', array(

                    'blog_id' => get_current_blog_id(),

                    'media_id' => $attachment_id,

                    'media_author' => get_current_user_id(),

                    'media_title' => $activity_title,

                    'activity_id' => $activity_id,

                    'upload_date' => current_time( 'mysql' ),

                ),

                array(

                    '%d',

                    '%d',

                    '%d',

                    '%s',

                    '%d',

                    '%d',

                )

        );

        //update media meta

        bp_activity_update_meta($activity_id, 'buddyboss_wall_action', '%INITIATOR% posted an update' );

        bp_activity_update_meta($activity_id, 'buddyboss_media_action', '%USER% posted a photo' );

        bp_activity_update_meta($activity_id, 'buddyboss_media_aid', $attachment_id );

        bp_activity_update_meta($activity_id, 'buddyboss_wall_initiator', get_current_user_id() );



    }


    if(!empty($_POST) && isset($_POST['save_parq'])){

        

        $table_name = $wpdb->prefix . 'parq';



        $parq = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE id = " . $_POST['parq_id']);



        $hashed = md5(NONCE_SALT.get_current_user_id().$parq->trainer_id);



        if( $_POST['parq_hash'] == $hashed ){



            $answers = array(

                '1'=>$_POST['question_1'],

                '2'=>$_POST['question_2'],

                '3'=>$_POST['question_3'],

                '4'=>$_POST['question_4'],

                '5'=>$_POST['question_5'],

                '6'=>$_POST['question_6'],

                '7'=>$_POST['question_7'],

                '7_other'=>$_POST['question_7_other'],);



            $wpdb->update( $table_name,

                array( 'client_name' => $_POST['parq_name'],

                    'client_dob' => $_POST['parq_dob'],

                    'client_address' => $_POST['parq_address'],

                    'client_postcode' => $_POST['parq_postcode'],

                    'client_email' => $_POST['parq_email'],

                    'client_mobile' => $_POST['parq_mobile'],

                    'client_answers' => json_encode($answers, JSON_FORCE_OBJECT),

                    'status' => 'complete' ),

                array( 'id' => $parq->id ));



            bp_notifications_add_notification( array(

                'user_id'           => $parq->trainer_id,

                'item_id'           => $parq->id,

                'secondary_item_id' => $parq->client_id,

                'component_name'    => 'connections',

                'component_action'  => 'connections_parq_complete',

                'date_notified'     => bp_core_current_time(),

                'is_new'            => 1,

            ));

        }

        $redirect = (bp_get_member_type($parq->trainer_id) == 'pt') ? '/my-trainers/#pending_parq' : '/my-gym/#pending_parq';

        wp_redirect($redirect);exit();

	}

//======================================================================

// MEMBERS CONNECTIONS

//======================================================================

//-----------------------------------------------------

// Send connection request

//-----------------------------------------------------

if( isset($_POST['action']) && $_POST['action']=='connection-request'){


    $table_name = $wpdb->prefix . 'members_connections';

    $sender = bp_loggedin_user_id();

    $reciver = $_POST['reciver'];

    $request_sender_type = bp_get_member_type($sender);

    $request_reciver_type = bp_get_member_type($reciver);

    $is_friend = friends_check_friendship($sender,$reciver);



    $exist_pending = $wpdb->get_row(

                        $wpdb->prepare(

                            "SELECT * FROM ".$table_name." WHERE request_sender_id = %d AND request_reciver_id = %d",

                            $reciver,

                            $sender

                        )

                    );

        

    if($is_friend){

	

        if(!$exist_pending){



            $parq_id = 0;

            

            if(($request_sender_type == 'standard' && $request_reciver_type == 'pt') || ($request_sender_type == 'standard' && $request_reciver_type == 'gym')){

                parse_str($_POST['data'], $data);



                $answers = array(

                '1'=>$data['question_1'],

                '2'=>$data['question_2'],

                '3'=>$data['question_3'],

                '4'=>$data['question_4'],

                '5'=>$data['question_5'],

                '6'=>$data['question_6'],

                '7'=>$data['question_7'],

                '7_other'=>$data['question_7_other'],);



                $wpdb->insert($wpdb->prefix . 'parq',

                    array( 

                        'trainer_id' => $reciver,

                        'client_id' => $sender,

                        'client_name' => $data['parq_name'],

                        'client_dob' => $data['parq_dob'],

                        'client_address' => $data['parq_address'],

                        'client_postcode' => $data['parq_postcode'],

                        'client_email' => $data['parq_email'],

                        'client_mobile' => $data['parq_mobile'],

                        'client_answers' => json_encode($answers, JSON_FORCE_OBJECT),

                        'status' => 'complete' 

                    ));

                

                $parq_id = $wpdb->insert_id;



                bp_notifications_add_notification( array(

                    'user_id'           => $reciver,

                    'item_id'           => $parq_id,

                    'secondary_item_id' => $sender,

                    'component_name'    => 'connections',

                    'component_action'  => 'connections_parq_complete',

                    'date_notified'     => bp_core_current_time(),

                    'is_new'            => 1,

                ));



            }else if(($request_sender_type == 'pt' && $request_reciver_type == 'standard') || ($request_sender_type == 'gym' && $request_reciver_type == 'standard')){



                $wpdb->insert($wpdb->prefix . 'parq', 

                    array(

                    'trainer_id' => $sender,

                    'client_id' => $reciver,

                    'status' => 'pending'

                ));   



                $parq_id = $wpdb->insert_id;

            }

               



            $wpdb->insert($table_name, array(

                'request_sender_id' => $sender,

                'request_reciver_id' => $reciver,

                'status' => 'pending',

                'parq' => $parq_id,

                'added' => current_time('Y-m-d H:i:s')

            ));



            $connection_id = $wpdb->insert_id;



            bp_notifications_add_notification( array(

                'user_id'           => $reciver,

                'item_id'           => $reciver,

                'secondary_item_id' => $sender,

                'component_name'    => 'connections',

                'component_action'  => 'connections_request',

                'date_notified'     => bp_core_current_time(),

                'is_new'            => 1,

            ));



            if($connection_id){

                switch ($request_sender_type) {

                    case 'standard':

                        $redirect = home_url().( ($request_reciver_type == 'pt') ? '/my-trainers/#requested' : '/my-gym/#requested' );

                    break;

                    case 'pt':

                        $redirect = home_url().( ($request_reciver_type == 'standard') ? '/trainer-clients/' : '/my-gym/' );

                    break;

                    case 'gym':

                        $redirect = home_url().( ($request_reciver_type == 'standard') ? '/gym-members/' : '/gym-trainers/' );

                    break;

                }

                echo json_encode(array('success'=>$redirect));

            }



        }else{

            echo json_encode(array('error'=>'You already have pending request from this user.'));

        }



    }else{

        echo json_encode(array('error'=>'This user is not in your friendship list.'));

    }

    exit();

}

//-----------------------------------------------------

// Cancel pending connection request by sender

//-----------------------------------------------------

if( isset($_POST['action']) && $_POST['action']=='connection-cancel'){



    $table_name = $wpdb->prefix.'members_connections';



    $exist = $wpdb->get_row("SELECT * FROM ".$table_name." 

                                WHERE request_sender_id=".bp_loggedin_user_id()." 

                                AND status='pending' 

                                AND id = ".$_POST['connection']);



    if($exist){

        //delete parq associated with this connection

        $wpdb->delete( $wpdb->prefix . 'parq', array( 'id' => $exist->parq ) );

        //delete pending connection

        $wpdb->delete( $table_name, array( 'id' => $exist->id ) );

        

        bp_notifications_add_notification( array(

            'user_id'           => $exist->request_reciver_id,

            'item_id'           => $exist->request_reciver_id,

            'secondary_item_id' => $exist->request_sender_id,

            'component_name'    => 'connections',

            'component_action'  => 'connections_cancel',

            'date_notified'     => bp_core_current_time(),

            'is_new'            => 1,

        ));



        echo json_encode(array('cancel_success'=>'Pending connection successfully cancelled.'));

    }else{

        echo json_encode(array('error'=>'This connection does not exist.'));

    }

    exit();

}





//-----------------------------------------------------

// Reject pending connection request by reciver

//-----------------------------------------------------

if( isset($_POST['action']) && $_POST['action']=='connection-reject'){



    $table_name = $wpdb->prefix . 'members_connections';



    $exist = $wpdb->get_row("SELECT * FROM ".$table_name." 

                                WHERE request_reciver_id=".bp_loggedin_user_id()." 

                                AND status='pending' 

                                AND id = ".$_POST['connection']);



    if($exist){

        //delete parq associated with this connection

        $wpdb->delete( $wpdb->prefix.'parq', array( 'id' => $exist->parq ) );

        //delete pending connection

        $wpdb->delete( $table_name, array( 'id' => $exist->id ) );

        

        bp_notifications_add_notification( array(

            'user_id'           => $exist->request_sender_id,

            'item_id'           => $exist->request_sender_id,

            'secondary_item_id' => $exist->request_reciver_id,

            'component_name'    => 'connections',

            'component_action'  => 'connections_reject',

            'date_notified'     => bp_core_current_time(),

            'is_new'            => 1,

        ));





        /*CHECK SENDER FOR LIMIT SPAM TRESHOLD*/

        $mm_spamer_options = get_option("mm_spamer_options");

        $mm_spamer_threshold = intval($mm_spamer_options["mm_spamer_threshold"]);

        $mm_spamer_notification = stripslashes_deep($mm_spamer_options["mm_spamer_notification"]);



        $table_name = $wpdb->prefix.'spam_requests';

        

        //check is sender already has any rejected requests

        $spams = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE sender_id = ".$exist->request_sender_id );

        

        //if this is not first rejected event for request_sender_id

        if(!empty($spams)){

            $wpdb->query( $wpdb->prepare("UPDATE ".$table_name." SET `qty` = `qty` + 1 WHERE `sender_id` = %d AND `reciver_id` = %d",

                        $exist->request_sender_id, $exist->request_reciver_id) );

            $count = $spams->qty+1;

        }

        else{

           $wpdb->query( $wpdb->prepare("INSERT INTO ".$table_name." ( sender_id, reciver_id, qty ) VALUES ( %d, %d, %d ) ", 

                        array($exist->request_sender_id,

                            $exist->request_reciver_id,

                            1)

                    ) );

            $count = 1;

        }



        if($count >= $mm_spamer_threshold){

            global $wpdb;

            $admin_id_from_email = $wpdb->get_row( "SELECT $wpdb->users.ID FROM $wpdb->users WHERE (SELECT $wpdb->usermeta.meta_value FROM $wpdb->usermeta WHERE $wpdb->usermeta.user_id = wp_users.ID AND $wpdb->usermeta.meta_key = 'wp_capabilities') LIKE '%administrator%'" );

            $wpdb->query('UPDATE wp_users SET user_status = 1 WHERE ID = '.$exist->request_sender_id);

            $args = array( 'recipients' => $exist->request_sender_id, 'sender_id' => $admin_id_from_email->ID, 'subject' => 'User status changed', 'content' => $mm_spamer_notification );

            messages_new_message( $args );



        }



        //redirect user who rejected pending connection

        switch (bp_get_member_type($exist->request_reciver_id)) {

            case 'standard':

                $redirect = home_url().( (bp_get_member_type($exist->request_sender_id) == 'pt') ? '/my-trainers/' : '/my-gym/' );

            break;

            case 'pt':

                $redirect = home_url().( (bp_get_member_type($exist->request_sender_id) == 'standard') ? '/trainer-clients/' : '/my-gym/' );

            break;

            case 'gym':

                $redirect = home_url().( (bp_get_member_type($exist->request_sender_id) == 'standard') ? '/gym-members/' : '/gym-trainers/' );

            break;

        }

        echo json_encode(array('success'=>$redirect));


    }else{

        echo json_encode(array('error'=>'This connection does not exist.'));

    }

    exit();

}


//-----------------------------------------------------

// Accept pending connection request by reciver

//-----------------------------------------------------

if( isset($_POST['action']) && $_POST['action']=='connection-accept'){



    $table_name = $wpdb->prefix.'members_connections';



    $exist = $wpdb->get_row("SELECT * FROM ".$table_name." 

                                WHERE request_reciver_id=".bp_loggedin_user_id()." 

                                AND status='pending' 

                                AND id = ".$_POST['connection']);


    if($exist){

        $wpdb->update( $table_name,

            array( 'status' => 'connected', 'added' => current_time('Y-m-d H:i:s')),  

            array( 'id' => $exist->id ));


        bp_notifications_add_notification( array(

            'user_id'           => $exist->request_sender_id,

            'item_id'           => $exist->request_sender_id,

            'secondary_item_id' => $exist->request_reciver_id,

            'component_name'    => 'connections',

            'component_action'  => 'connections_accept',

            'date_notified'     => bp_core_current_time(),

            'is_new'            => 1,

        ));

        //redirect user who rejected pending connection

        switch (bp_get_member_type($exist->request_reciver_id)) {

            case 'standard':

                $redirect = home_url().( (bp_get_member_type($exist->request_sender_id) == 'pt') ? '/my-trainers/' : '/my-gym/' );

            break;

            case 'pt':

                $redirect = home_url().( (bp_get_member_type($exist->request_sender_id) == 'standard') ? '/trainer-clients/' : '/my-gym/' );

            break;

            case 'gym':

                $redirect = home_url().( (bp_get_member_type($exist->request_sender_id) == 'standard') ? '/gym-members/' : '/gym-trainers/' );

            break;

        }

        echo json_encode(array('success'=>$redirect));

    }else{

        echo json_encode(array('error'=>'This connection does not exist.'));

    }

    exit();

}  

//-----------------------------------------------------

// Disconect connected connection request

//-----------------------------------------------------

if( isset($_POST['action']) && $_POST['action']=='connection-disconnect'){



    $table_name = $wpdb->prefix.'members_connections';



    $exist = $wpdb->get_row("SELECT * FROM ".$table_name." 

                                WHERE status='connected'

                                AND (request_sender_id=".bp_loggedin_user_id()." || request_reciver_id=".bp_loggedin_user_id().") 

                                AND id = ".$_POST['connection']);



    if($exist){



        //get Standard User id 

        $client_id = ( bp_get_member_type($exist->request_sender_id) == 'standard' ) ? $exist->request_sender_id : $exist->request_reciver_id;

        //get Personal Trainer or GYM User id

        $trainer_id = ( bp_get_member_type($exist->request_sender_id) == 'standard' ) ? $exist->request_reciver_id : $exist->request_sender_id;

        

        //and delete all related connections, parq and sharing requests

        $wpdb->delete( $table_name, array( 'id' => $exist->id ) );

        $wpdb->delete( $wpdb->prefix . 'parq', array( 'client_id' => $client_id, 'trainer_id' => $trainer_id ) );

        $wpdb->delete( $wpdb->prefix . 'sharing_requests', array( 'client_id' => $client_id, 'trainer_id' => $trainer_id ) );





        //check who was initiator of this connection, and define who must recive notification about disconnect

        if(bp_loggedin_user_id() == $exist->request_sender_id){

            $recipient = $exist->request_reciver_id;

            $sender = $exist->request_sender_id;

        }

        elseif(bp_loggedin_user_id() == $exist->request_reciver_id){

            $recipient = $exist->request_sender_id;

            $sender = $exist->request_reciver_id;   

        }


        bp_notifications_add_notification( array(

            'user_id'           => $recipient,

            'item_id'           => $recipient,

            'secondary_item_id' => $sender,

            'component_name'    => 'connections',

            'component_action'  => 'connections_disconnect',

            'date_notified'     => bp_core_current_time(),

            'is_new'            => 1,

        ));



        //redirect user who disconnected connection

        switch (bp_get_member_type($sender)) {

            case 'standard':

                $redirect = home_url().( (bp_get_member_type($recipient) == 'pt') ? '/my-trainers/' : '/my-gym/' );

            break;

            case 'pt':

                $redirect = home_url().( (bp_get_member_type($recipient) == 'standard') ? '/trainer-clients/' : '/my-gym/' );

            break;

            case 'gym':

                $redirect = home_url().( (bp_get_member_type($recipient) == 'standard') ? '/gym-members/' : '/gym-trainers/' );

            break;

        }



        echo json_encode(array('success'=>$redirect));



    }else{

        echo json_encode(array('error'=>'This connection does not exist.'));

    }

    exit();

}   


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'resend_parq'){

        

        $trainer_id = get_current_user_id();

        $client_id = $_POST['client_id'];



        $table_name = $wpdb->prefix . 'parq';

        $exist_parq = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE client_id = " . $client_id . " AND trainer_id = " . $trainer_id . " AND status = 'pending'"); 



        if(!$exist_parq){



            $wpdb->insert($wpdb->prefix . 'parq', array(

                'trainer_id' => $trainer_id,

                'client_id' => $client_id,

                'status' => 'pending'

            ));



            echo json_encode(array('success'=>'successfully'));



            bp_notifications_add_notification( array(

                'user_id'           => $client_id,

                'item_id'           => $wpdb->insert_id,

                'secondary_item_id' => $trainer_id,

                'component_name'    => 'connections',

                'component_action'  => 'connections_resend_parq',

                'date_notified'     => bp_core_current_time(),

                'is_new'            => 1,

            ));

        }

        else

            echo json_encode(array('error'=>'This client did not complete your previous PAR-Q request.'));

    }


    if(!empty($_POST) && isset($_POST['save_food_diary'])){



        $meals = $_POST['meals'];



        $diary_uniq_id = uniqid();

        $table_name = $wpdb->prefix . 'food_diary';

       

        foreach ($meals as $key => $meal) {

            foreach($meal['ingredients'] as $k=>$ingredient){



                $wpdb->insert($table_name, array(

                    'diary_uniq_id' => $diary_uniq_id,

                    'user_id' => $current_user->ID,

                    'meal_row' => $key,

                    'diary_name' => $meal['diary_name'],

                    'ingredient_name' => $ingredient['name'],

                    'ingredient_calories' => $ingredient['calories'],

                    'ingredient_protein' => $ingredient['protein'],

                    'ingredient_fats' => $ingredient['fat'],

                    'ingredient_carbs' => $ingredient['carbs'],

                    'updated' => current_time('Y-m-d H:i:s')

                ));



            }

        }

        exit;

    }


    if(isset($_POST['to_wall_food_plan']) && !empty($_POST['canvas']) ){



        $canvas = $_POST['canvas'];

        $filename = md5(uniqid()) . '.jpg';

        $food_plan =  $wpdb->get_row( "SELECT diary_name, updated FROM $wpdb->prefix".'food_diary'." WHERE user_id=".$current_user->ID." AND diary_uniq_id='".$_POST['uniqid']."'" );



        //remove base64data

        $uri =  substr($canvas,strpos($canvas,",")+1);

        

        $wp_upload_dir = wp_upload_dir();

        if (!is_dir($wp_upload_dir['path']) || !is_writable($wp_upload_dir['path']))

            return WRITING_PROBLEMS; 


        file_put_contents($wp_upload_dir['path'] . '/' . basename($filename), base64_decode($uri), LOCK_EX);


        //set title and content of the activity post

        $activity_title =  'My "'.strtoupper($food_plan->diary_name).'" nutrition plan, created at '.date('d F Y',strtotime($food_plan->updated));

        $activity_content = 'My "'.strtoupper($food_plan->diary_name).'" nutrition plan, created at '.date('d F Y',strtotime($food_plan->updated));



        //save activity post and return saver post id

        $activity_id = bp_activity_add ( array(

            'action' => $activity_title,

            'content' => $activity_content,

            'component' => 'activity',

            'type' => 'activity_update',

            'user_id' => $bp->loggedin_user->id,

            'item_id' => $bp->displayed_user->id

        ));

        

        // Check the type of file. We'll use this as the 'post_mime_type'.

        $filetype = wp_check_filetype( basename( $filename ), null );



        // Prepare an array of post data for the attachment.

        $attachment = array(

            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 

            'post_mime_type' => $filetype['type'],

            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

            'post_content'   => '',

            'post_status'    => 'inherit'

        );



        // Insert the attachment and conect it with activity post id.

        $attachment_id = wp_insert_attachment( $attachment, $wp_upload_dir['subdir'] . '/' .$filename, $activity_id );

        

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.

        require_once( ABSPATH . 'wp-admin/includes/image.php' );



        // Generate the metadata for the attachment, and update the database record.NOTICE $wp_upload_dir['path']!!!

        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $wp_upload_dir['path'] . '/' . basename($filename) );

        wp_update_attachment_metadata( $attachment_id, $attachment_data );

        set_post_thumbnail( $activity_id, $attachment_id );

        

        //insert info about attachment and activity to media table

        $wpdb->insert(

                $wpdb->prefix . 'buddyboss_media', array(

                    'blog_id' => get_current_blog_id(),

                    'media_id' => $attachment_id,

                    'media_author' => get_current_user_id(),

                    'media_title' => $activity_title,

                    'activity_id' => $activity_id,

                    'upload_date' => current_time( 'mysql' ),

                ),

                array(

                    '%d','%d','%d','%s','%d','%d',

                )

        );



        //update media meta

        bp_activity_update_meta($activity_id, 'buddyboss_wall_action', '%INITIATOR% posted an update' );

        bp_activity_update_meta($activity_id, 'buddyboss_media_action', '%USER% posted a photo' );

        bp_activity_update_meta($activity_id, 'buddyboss_media_aid', $attachment_id );

        bp_activity_update_meta($activity_id, 'buddyboss_wall_initiator', get_current_user_id() );

	}



    if(!empty($_POST) && isset($_POST['save_new_supplements_diary'])){



        $supplements = $_POST['supplements'];



        $diary_uniq_id = uniqid();

        $user_id = get_current_user_id();

        $table_name = $wpdb->prefix . 'supplements_diary';



        foreach ($supplements as $key => $supplement) {

            $wpdb->insert($table_name, array(

                'diary_uniq_id' => $diary_uniq_id,

                'user_id' => $user_id,

                'diary_name' => $_POST['diary_name'],

                'supplement_row' =>  $key,

                'supplement_name' => $supplement[0]['value'],

                'supplement_unit' => $supplement[1]['value'],

                'supplement_amount' => $supplement[2]['value'],

                'supplement_per_day' => $supplement[3]['value'],

                'created' => current_time('Y-m-d H:i:s')

            ));

        }



    }



    if(!empty($_POST) && (isset($_POST['delete_food_plan']) || isset($_POST['delete_supplement_plan'])) ){



        $table_name = (isset($_POST['delete_food_plan'])) ? $wpdb->prefix . 'food_diary' : $wpdb->prefix . 'supplements_diary';



        $exist =  $wpdb->get_var( "SELECT user_id FROM $table_name WHERE user_id=".$current_user->ID." AND diary_uniq_id='".$_POST['uniqid']."'" );



        if($exist == $current_user->ID){

            $deleted = $wpdb->delete( $table_name, array( 'diary_uniq_id' => $_POST['uniqid']) );

            echo json_encode(array('success'=>'Plan deleted.'));

        }

        else{

            echo json_encode(array('error'=>'Cannot delete this plan. Try later.'));

        }

    }



    if(!empty($_POST) && (isset($_POST['share_food_plan']) || isset($_POST['unshare_food_plan']) || isset($_POST['share_supplement_plan']) || isset($_POST['unshare_supplement_plan'])) ){



        $type = (isset($_POST['share_food_plan']) || isset($_POST['unshare_food_plan'])) ? 'food' : 'supplement';

        $action = (isset($_POST['share_food_plan']) || isset($_POST['share_supplement_plan'])) ? 1 : 0;

        $table_name = ($type=='food') ? $wpdb->prefix . 'food_diary' : $wpdb->prefix . 'supplements_diary';



        $exist =  $wpdb->get_var( "SELECT user_id FROM $table_name WHERE user_id=".$current_user->ID." AND diary_uniq_id='".$_POST['uniqid']."'" );



        if($exist == $current_user->ID){

            $updated = $wpdb->update( $table_name,

                                        array( 'shared' => $action ),  

                                        array( 'diary_uniq_id' => $_POST['uniqid'] ));

            echo json_encode(array('success'=>'Plan shared.'));

        }

        else{

            echo json_encode(array('error'=>'Cannot share this plan. Try later.'));

        }

	}




    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'set-calendar-person'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        

        $is_pending = false;



        //check is pending only for clients

        if(bp_get_member_type($current_user->ID)=='standard')

            $is_pending = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND person_id=".$current_user->ID." AND status = 'pending' ");

        

        //check if record exist in calendars table

        $exist = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND user_id=".$current_user->ID);

        

        if($is_pending){

            echo json_encode(array('pending'=>'You have pending training invitation for this date and time.'));

            return false;

        }elseif(!$_POST['person']){

            $deleted = $wpdb->delete( $table_name, array( 'calendar_date' => $_POST['calendar_date'], 'time_row' => $_POST['row'], 'user_id' => $current_user->ID ) );

            echo json_encode(array('deleted'=>'Row deleted.'));

            return false;

        }

        elseif($exist){

            $updated = $wpdb->update( $table_name,

            array( 'person_id' => $_POST['person']

             ),  

            array(  'calendar_date'=>$_POST['calendar_date'],

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'])

            );

            echo json_encode(array('updated'=>'Row updated.'));

        }else{

            $inserted = $wpdb->insert($table_name, array(

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'],

                    'person_id'=>$_POST['person'],

                    'calendar_date'=>$_POST['calendar_date']

            ));

            

            echo json_encode(array('inserted'=>'Row inserted.'));

        }

    }



    if(!empty($_POST) && isset($_POST['action']) && $_POST['action']=='set-calendar-workout'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        $exist = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND user_id=".$current_user->ID);



        if($exist){

            $updated = $wpdb->update( $table_name,

            array( 'workout' => $_POST['workout']

             ),  

            array(  'calendar_date'=>$_POST['calendar_date'],

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'])

            );

        }else{

            $inserted = $wpdb->insert($table_name, array(

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'],

                    'workout'=>$_POST['workout'],

                    'calendar_date'=>$_POST['calendar_date']

            ));

        }

        echo $wpdb->last_query;



    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action']=='get-day-calendar'){

        $calendar_day = array();

        $calendar_day['shared'] = 0;

        $table_name = $wpdb->prefix . 'calendars_schedules';

        

        $results = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND user_id=".$_POST['user_id']);

        if(!empty($results)){

            

            foreach($results as $key=>$result){

                $calendar_day['day'][$result->time_row] = $result;

                if($result->shared==1)

                    $calendar_day['shared'] = 1;

                else

                    $calendar_day['shared'] = 0;

            }

            echo json_encode(array('success'=>$calendar_day));

        }else

            echo json_encode(array('error'=>'No events found'));



        exit();

	}



    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'send-training-invitation'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        

        $exist = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND user_id=".$current_user->ID);



        //check client for allready saving tarining for that datetime

        $client_is_busy = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND user_id=".$_POST['person']);

        

        if($client_is_busy){

            echo json_encode(array('busy'=>'This client already has training on this date and time.'));

            return false;

        }

        else{

            if($exist){

                $deleted = $wpdb->delete( $table_name, array( 'calendar_date' => $_POST['calendar_date'], 'time_row' => $_POST['row'], 'user_id' => $current_user->ID ) );

                $succses = array('deleted'=>'Row successfuly deleted.');
			}
			
            $wpdb->insert($table_name, array(

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'],

                    'person_id'=>$_POST['person'],

                    'calendar_date'=>$_POST['calendar_date'],

                    'status'=>'pending'

            ));

            $wpdb->insert($table_name, array(

                    'user_id'=>$_POST['person'],

                    'time_row'=>$_POST['row'],

                    'person_id'=>$current_user->ID,

                    'calendar_date'=>$_POST['calendar_date'],

                    'status'=>'pending'

            ));

            $time = date("g a", mktime($_POST['row']+5));

            $fullname = bp_get_profile_field_data('field=1&user_id='.$current_user->ID).' '.bp_get_profile_field_data('field=8&user_id='.$current_user->ID); 

     

            

            $content = $fullname.' invites you to training '.date('d.m.Y', strtotime($_POST['calendar_date'])).' at '.$time.'. <small><a href="/training-schedule/?date='.base64_encode(str_rot13(date('d.m.Y', strtotime($_POST['calendar_date'])))).'">More...</a></small>';

            $args = array( 'recipients' => $_POST['person'], 'sender_id' => $current_user->ID, 'subject' => 'Training Invitation', 'content' => $content );

            messages_new_message( $args );

            

            echo json_encode(array('success'=> 'Invitation successfully sent.'));

        }

        

        exit();

    }



    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'send-training-invitation-other'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        

        $exist = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND user_id=".$current_user->ID);



        if($exist){

            $updated = $wpdb->update( $table_name,

            array( 'person_id' => -1,

                    'other_name' => $_POST['other_name'],

                    'other_email' => $_POST['other_email'],

                    'status' => 'accepted'

             ),  

            array(  'calendar_date'=>$_POST['calendar_date'],

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'])

            );

        }else{

            $inserted = $wpdb->insert($table_name, array(

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'],

                    'person_id'=> -1,

                    'calendar_date'=>$_POST['calendar_date'],

                    'other_name' => $_POST['other_name'],

                    'other_email' => $_POST['other_email'],

                    'status' => 'accepted'



            ));

        }

            $time = date("g a", mktime($_POST['row']+5));

            $fullname = bp_get_profile_field_data('field=1&user_id='.$current_user->ID).' '.bp_get_profile_field_data('field=8&user_id='.$current_user->ID); 

     

            

            $content = 'Hi '.$_POST['other_name'].'! <br /><br />'.$fullname.' invites you to training '.$_POST['calendar_date'].' at '.$time.'. <small><a href="'.home_url().'">More...</a></small>';

            $to = $_POST['other_email'];

            $subject = 'Mirror Muscles Training Invitation';

            $headers = array('Content-Type: text/html; charset=UTF-8');



            wp_mail( $to, $subject, $content, $headers );

    }



    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'accept-training-invitation'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        

        $client_row = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND user_id=".$current_user->ID);



        $trainer_id = $client_row->person_id;

        

        $update_trainer = $wpdb->update( $table_name,

            array( 'status' => 'accepted'

             ),  

            array(  'calendar_date'=>$_POST['calendar_date'],

                    'user_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'])

            );



        $updated_client = $wpdb->update( $table_name,

            array( 'status' => 'accepted'

             ),  

            array(  'calendar_date'=>$_POST['calendar_date'],

                    'person_id'=>$current_user->ID,

                    'time_row'=>$_POST['row'])

            );



        $fullname = bp_get_profile_field_data('field=1&user_id='.$current_user->ID).' '.bp_get_profile_field_data('field=8&user_id='.$current_user->ID); 

        $content = $fullname.' has accepted your training invitation.';

        $args = array( 'recipients' => $trainer_id, 'sender_id' => $current_user->ID, 'subject' => 'Trainig Invitation Accepted', 'content' => $content );



        messages_new_message( $args );

    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'reject-training-invitation'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        $client_row = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND user_id=".$current_user->ID);

        $trainer_id = $client_row->person_id;

        $delete_for_client = $wpdb->delete( $table_name, array( 'calendar_date' => $_POST['calendar_date'], 'time_row' => $_POST['row'], 'user_id' => $current_user->ID ) );

        $delete_for_trainer = $wpdb->delete( $table_name, array( 'calendar_date' => $_POST['calendar_date'], 'time_row' => $_POST['row'], 'person_id' => $current_user->ID ) );



        $fullname = bp_get_profile_field_data('field=1&user_id='.$current_user->ID).' '.bp_get_profile_field_data('field=8&user_id='.$current_user->ID); 

        $content = $fullname.' has rejected your training invitation.';

        $args = array( 'recipients' => $trainer_id, 'sender_id' => $current_user->ID, 'subject' => 'Trainig Invitation Rejected', 'content' => $content );



        messages_new_message( $args );

    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'refuse-training-invitation'){

        $table_name = $wpdb->prefix . 'calendars_schedules';

        $member_type = bp_get_member_type($current_user->ID);

        $exist_pending_refuse = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND (user_id=".$current_user->ID." OR person_id=".$current_user->ID.") AND (status = 'refusing' OR status='refusing-initiator') ORDER BY id ASC");

        $first_of_pare = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND status = 'accepted' AND (user_id=".$current_user->ID." OR person_id=".$current_user->ID.") ORDER BY id ASC");

            if($member_type == 'enchanced'){

                //check who is initiator of refusion

                $refuser = $first_of_pare->user_id;

                $recipient = $first_of_pare->person_id;

            }elseif($member_type == 'standard'){

                $refuser = $first_of_pare->person_id;

                $recipient = $first_of_pare->user_id;

            }

        if($exist_pending_refuse){

            echo json_encode(array('pending'=>'You already have a pending refuse.'));

            return false;

        }else{

            $update_initiator = $wpdb->update( $table_name,

                array( 'status' => 'refusing-initiator'

                 ),  

                array(  'calendar_date'=>$_POST['calendar_date'],

                        'user_id'=>$refuser,

                        'time_row'=>$_POST['row'])

                );



            $updated_recipient = $wpdb->update( $table_name,

                array( 'status' => 'refusing'

                 ),  

                array(  'calendar_date'=>$_POST['calendar_date'],

                        'person_id'=>$refuser,

                        'time_row'=>$_POST['row'])

                );



            $time = date("g a", mktime($_POST['row']+5));


            $fullname = bp_get_profile_field_data('field=1&user_id='.$refuser).' '.bp_get_profile_field_data('field=8&user_id='.$refuser); 

            $content = $fullname.' wants to refuse training '.date('d.m.Y', strtotime($_POST['calendar_date'])).' at '.$time.'. <small><a href="/training-schedule/?date='.base64_encode(str_rot13(date('d.m.Y', strtotime($_POST['calendar_date'])))).'">More...</a></small>';


            $args = array( 'recipients' => $recipient, 'sender_id' => $refuser, 'subject' => 'Training Refusal Requested', 'content' => $content );



            messages_new_message( $args );

		}

    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'accept-training-refuse'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        $member_type = bp_get_member_type($current_user->ID);

        $first_of_pare = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND (status = 'refusing' OR status='refusing-initiator') AND (user_id=".$current_user->ID." OR person_id=".$current_user->ID.") ORDER BY id ASC");

            if($member_type == 'enchanced'){

                //check who is initiator of refusion

                $refuser = $first_of_pare->user_id;

                $recipient = $first_of_pare->person_id;

            }elseif($member_type == 'standard'){

                $refuser = $first_of_pare->person_id;

                $recipient = $first_of_pare->user_id;

            }



        $delete_for_client = $wpdb->delete( $table_name, array( 'calendar_date' => $_POST['calendar_date'], 'time_row' => $_POST['row'], 'user_id' => $refuser ) );

        $delete_for_trainer = $wpdb->delete( $table_name, array( 'calendar_date' => $_POST['calendar_date'], 'time_row' => $_POST['row'], 'person_id' => $refuser ) );



        $fullname = bp_get_profile_field_data('field=1&user_id='.$refuser).' '.bp_get_profile_field_data('field=8&user_id='.$refuser); 

        $time = date("g a", mktime($_POST['row']+5));

        $content = $fullname.' has accepted training refusal '.date('d.m.Y', strtotime($_POST['calendar_date'])).' at '.$time.'.';

        $args = array( 'recipients' => $recipient, 'sender_id' => $refuser, 'subject' => 'Trainig Refusal Accepted', 'content' => $content );



        messages_new_message( $args );

    }

    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'reject-training-refuse'){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        $member_type = bp_get_member_type($current_user->ID);



        $first_of_pare = $wpdb->get_row("SELECT * FROM ".$table_name." WHERE calendar_date = '".$_POST['calendar_date']."' AND time_row=".$_POST['row']." AND (status = 'refusing' OR status='refusing-initiator') AND (user_id=".$current_user->ID." OR person_id=".$current_user->ID.") ORDER BY id ASC");

        if($member_type == 'enchanced'){

            //check who is initiator of refusion

            $refuser = $first_of_pare->user_id;

            $recipient = $first_of_pare->person_id;

        }elseif($member_type == 'standard'){

            $refuser = $first_of_pare->person_id;

            $recipient = $first_of_pare->user_id;

        }



        $update_initiator = $wpdb->update( $table_name,

                array( 'status' => 'accepted'

                 ),  

                array(  'calendar_date'=>$_POST['calendar_date'],

                        'user_id'=>$refuser,

                        'time_row'=>$_POST['row'])

                );



        $updated_recipient = $wpdb->update( $table_name,

                array( 'status' => 'accepted'

                 ),  

                array(  'calendar_date'=>$_POST['calendar_date'],

                        'person_id'=>$refuser,

                        'time_row'=>$_POST['row'])

                );



        $time = date("g a", mktime($_POST['row']+5));



        $fullname = bp_get_profile_field_data('field=1&user_id='.$refuser).' '.bp_get_profile_field_data('field=8&user_id='.$refuser); 

        $content = $fullname.' has rejected training refusal '.date('d.m.Y', strtotime($_POST['calendar_date'])).' at '.$time.'. <small><a href="/training-schedule/?date='.base64_encode(str_rot13(date('d.m.Y', strtotime($_POST['calendar_date'])))).'">More...</a></small>';



        $args = array( 'recipients' => $recipient, 'sender_id' => $refuser, 'subject' => 'Training Refusal Rejected', 'content' => $content );

        messages_new_message( $args );

    }



    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'get-week-calendar'){

        

        $table_name = $wpdb->prefix . 'calendars_schedules';

        $week = array();

        $mon_value = date($_POST['calendar_date'], strtotime('Monday this week'));



        // Assuming $date is in format DD-MM-YYYY

        list($year, $month, $day) = explode("-", $_POST["calendar_date"]);



        // Get the weekday of the given date

        $wkday = date('l',mktime('0','0','0', $month, $day, $year));



        switch($wkday) {

            case 'Monday': $dayName = 0; break;

            case 'Tuesday': $dayName = 1; break;

            case 'Wednesday': $dayName = 2; break;

            case 'Thursday': $dayName = 3; break;

            case 'Friday': $dayName = 4; break;

            case 'Saturday': $dayName = 5; break;

            case 'Sunday': $dayName = 6; break;   

        }



        $monday = mktime('0','0','0', $month, $day-$dayName, $year);



        $seconds_in_a_day = 86400;



        for($i=0; $i<7; $i++)

        {

            $dates[$i] = date('Y-m-d',$monday+($seconds_in_a_day*$i));

        }



        foreach($dates as $key=>$date){



            switch($key) {

                case 0: $dayName = 'Monday'; break;

                case 1: $dayName = 'Tuesday'; break;

                case 2: $dayName = 'Wednesday'; break;

                case 3: $dayName = 'Thursday'; break;

                case 4: $dayName = 'Friday'; break;

                case 5: $dayName = 'Saturday'; break;

                case 6: $dayName = 'Sunday'; break;   

            }



            $results = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE calendar_date = '".$date."' AND user_id=".$current_user->ID." ORDER BY calendar_date AND time_row");

            

            if(!empty($results)){

                

                foreach($results as $k=>$result){

                    $person1 = ($result->person_id) ? bp_get_profile_field_data('field=First Name&user_id='.$result->person_id).' '.bp_get_profile_field_data('field=Last Name&user_id='.$result->person_id) : '';

					$workout1 = ($result->workout) ? $result->workout : '';

					$person = $person1.' - '.$workout1;

                    

                    $week[$dayName][$result->time_row] = array('workout'=>$workout, 'person'=>$person);

                }

            }

        }

        echo json_encode($week);
    }


    if(!empty($_POST) && isset($_POST['action']) && ( $_POST['action'] == 'share-calendar' || $_POST['action'] == 'unshare-calendar' ) ){



        $table_name = $wpdb->prefix . 'calendars_schedules';

        $action = ($_POST['action']=='share-calendar') ? 1 : 0;

        $updated = $wpdb->update( $table_name,

            array( 'shared' => $action ),  

            array( 'user_id' => $current_user->ID,

                    'calendar_date'=>$_POST['calendar_date'] ));

        if($updated){



            $trainers = accepted_connection_requests('pt',$current_user->ID);



            $fullname = get_fullname($current_user->ID); 

            $hash = md5(NONCE_SALT.$_POST["calendar_date"].$current_user->ID);

            $encode_student = base64_encode(str_rot13($current_user->ID));

            $view_url = home_url().'/training-schedule/?action=show_shared_schedule&date='.$_POST["calendar_date"].'&student='.$encode_student.'&hash='.$hash;



            $content = ($action == 1) ? 'Training schedule has been shared by '.$fullname.' for '.$_POST['calendar_date'].'. You can view it <a href="'.$view_url.'">here</a>.' : 'Training schedule has been unshared by '.$fullname.' for '.$_POST['calendar_date'].'.';

            

            $args = array( 'recipients' => $trainers, 'sender_id' => $current_user->ID, 'subject' => 'Training Schedule Shared', 'content' => $content );



            messages_new_message( $args );



            echo json_encode(array('success'=>'Schedule day successfuly shared.'));

    

        }else

            echo json_encode(array('error'=>'Schedule is empty. No data to share.'));



        exit();

    }

    

    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'sharing_request_to_client'){

        

        $table_name = $wpdb->prefix . 'sharing_requests';

        

        $trainer_id = get_current_user_id();

        $client_id = $_POST['client_id'];

        $result_id = get_client_last_bfc_result($_POST['client_id']);

        $fullname = get_fullname($trainer_id); 

        

        $wpdb->insert($table_name, array(

            'trainer_id' => get_current_user_id(),

            'client_id' => $client_id,

            'result_id' =>  $result_id->id,

            'status' => 'pending',

        ));



        $result = base64_encode(str_rot13($wpdb->insert_id));

        $hash = $hash = md5(NONCE_SALT.$wpdb->insert_id);

        $accept_url = WP_PLUGIN_URL.'/mirror-muscles/handler.php?action=accept_share_result&result='.$result.'&hash='.$hash;

        $cancel_url = WP_PLUGIN_URL.'/mirror-muscles/handler.php?action=cancel_share_result&result='.$result.'&hash='.$hash;

        

        $content = $fullname.' has requested for sharing your latest body fat calculator results. <small><a href="'.$accept_url.'">Accept</a> or <a href="'.$cancel_url.'">Cancel</a>.</small>';

        $args = array( 'recipients' => $_POST['client_id'], 'sender_id' => get_current_user_id(), 'subject' => 'Body Fat Calculatr Results Sharing Requested', 'content' => $content );



        messages_new_message( $args );
    }


    if(!empty($_GET) && isset($_GET['action']) && $_GET['action'] === 'accept_share_result' ){



        $accepted_result = base64_decode(str_rot13(str_rot13($_GET['result'])));

        $hash = $_GET['hash'];

        $hashed = md5(NONCE_SALT.$accepted_result);

        if($hash==$hashed){

            $table_name = $wpdb->prefix . 'sharing_requests';

            $results = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$accepted_result." ORDER BY id DESC" );

            

            if( $results->status=='pending' ){

                

                $trainer_id = $results->trainer_id;

                $client_id = $results->client_id;



                $fullname = get_fullname($client_id); 

                

                //delete all previous accepted requests

                $deleted = $wpdb->delete( $table_name, array( 'trainer_id' => $trainer_id,'client_id'=>$client_id,'status'=>'accepted') );

                

                $updated = $wpdb->update( $table_name,

                array( 'status' => 'accepted' ),  

                array( 'id' => $accepted_result ));



                if($updated){

                    $content = $fullname.' has accepted your body fat calculator results sharing request.';

                    $args = array( 'recipients' => $trainer_id, 'sender_id' => $client_id, 'subject' => 'Body Fat Calculator Results Sharing Request Accepted', 'content' => $content );

                    messages_new_message( $args );

                    $redirect = (bp_get_member_type($trainer_id) == 'pt') ? '/my-trainers/#sharing_requests' : '/my-gym/#sharing_requests';

                    wp_redirect($redirect);

                }    

            }

            else{

                $redirect = (bp_get_member_type($trainer_id) == 'pt') ? '/my-trainers/#sharing_requests' : '/my-gym/#sharing_requests';

                wp_redirect($redirect);

            }

                       

        }else{

            wp_redirect(home_url());

        }

        

    }



    if(!empty($_GET) && isset($_GET['action']) && $_GET['action'] === 'cancel_share_result' ){



        $canceled_result = base64_decode(str_rot13(str_rot13($_GET['result'])));

        $hash = $_GET['hash'];

        $hashed = md5(NONCE_SALT.$canceled_result);

        if($hash==$hashed){

            $table_name = $wpdb->prefix . 'sharing_requests';

            $results = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE id = ".$canceled_result." ORDER BY id DESC" );

            

            if( $results->status == 'pending' ){

                

                $trainer_id = $results->trainer_id;

                $client_id = $results->client_id;

                $fullname = bp_get_profile_field_data('field=1&user_id='.$client_id).' '.bp_get_profile_field_data('field=8&user_id='.$client_id); 

        

                $deleted = $wpdb->delete( $table_name, array( 'id' => $canceled_result) );

        

                if($deleted){

                    $content = $fullname.' cancelled your sharing request.';

                    $args = array( 'recipients' => $trainer_id, 'sender_id' => $client_id, 'subject' => 'Cancelled sharing request', 'content' => $content );

                    messages_new_message( $args );

                    wp_redirect(bp_core_get_user_domain($client_id).'/messages/');

                }    

            }

            else

                wp_redirect(bp_core_get_user_domain($client_id).'/messages/');

        }else{

            //wp_redirect(home_url());

        }

    }




    if(!empty($_POST) && isset($_POST['save_onerepmax'])){

        

        $wpdb->insert(

                $wpdb->prefix . 'onerepmax_results', array(

                    'user_id' => $current_user->ID,

                    'exercise' => $_POST['exercise'],

                    'weight' => $_POST['weight'],

                    'repeats' => $_POST['repeats'],

                    'added' => current_time( 'mysql' ),

                ),

                array(

                    '%d','%s','%s','%d','%s'

                )

        );

    }

    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'delete-prev-onerepmax-result'){

        

        $deleted = $wpdb->delete( $wpdb->prefix . 'onerepmax_results', array( 'id' => $_POST['result_id']) );

        if($deleted)

            return true;

        return false;

    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'save_new_ingredient'){

        

        $table_name = $wpdb->prefix . 'custom_ingredients';

        

        $data = array();



        parse_str($_POST['data'], $data);



        $inserted = $wpdb->insert($table_name, array(

            'user_id' => $bp->loggedin_user->id,

            'name' => $data['ingredient_name'],

            'number_of_units' => $data['ingredient_number_of_units'],

            'measurement_description' => $data['ingredient_measurement_description'], 

            'calories' => $data['ingredient_calories'],

            'fat' => $data['ingredient_fat'],

            'saturated_fat' => $data['ingredient_saturated_fat'],

            'polyunsaturated_fat' => $data['ingredient_polyunsaturated_fat'],

            'monounsaturated_fat' => $data['ingredient_monounsaturated_fat'],

            'trans_fat' => $data['ingredient_trans_fat'],

            'cholesterol' => $data['ingredient_cholesterol'],

            'sodium' => $data['ingredient_sodium'],

            'potassium' => $data['ingredient_potassium'],

            'carbohydrate' => $data['ingredient_carbohydrate'],

            'fiber' => $data['ingredient_fiber'],

            'sugar' => $data['ingredient_sugar'],

            'protein' => $data['ingredient_protein'],

            'vitamin_a' => $data['ingredient_vitamin_a'],

            'vitamin_c' => $data['ingredient_vitamin_c'],

            'calcium' => $data['ingredient_calcium'],

            'iron' => $data['ingredient_iron']

        ));



        if($inserted){

            $fullname = get_fullname($bp->loggedin_user->id); 

            wp_mail(get_bloginfo('admin_email'),'New ingredient', 'New custom ingredient added by '.$fullname.'(ID - '.$bp->loggedin_user->id.').');

            //wp_redirect('/food-supplement-diary');

            echo json_encode(array('success' => 'New custom ingredient added'));

        }

    }


    if(!empty($_POST['action']) && $_POST['action'] == 'search_custom_ingredient'){



        $ingredients = $wpdb->get_results( $wpdb->prepare( "SELECT id AS food_id, name AS food_name FROM `{$wpdb->prefix}custom_ingredients` WHERE name LIKE '%%%s%%';", like_escape( $_POST['query'])));

        if(!empty($ingredients))

            echo json_encode($ingredients);

        else

            echo json_encode(array('error'=>'no mathces'));



        exit;

    }


    if(!empty($_POST['action']) && $_POST['action'] == 'get_custom_food'){



        $ingredient = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}custom_ingredients` WHERE id=".$_POST['food_id'], ARRAY_A);

        

        $ingredients['serving'] = $ingredient;

        $ingredients['serving']['serving_description'] = $ingredient['number_of_units'].' '.$ingredient['measurement_description'];



        if(!empty($ingredients))

            echo json_encode($ingredients);

        else

            echo json_encode(array('error'=>'no mathces'));



        exit;

    }


    if(!empty($_POST['action']) && $_POST['action'] == 'get_category_exercises'){

        

        $exercises = $wpdb->get_results("SELECT e.*, `ei`.`image`, `com`.`comment` FROM `{$wpdb->prefix}workout_exercise` AS e 

                                        LEFT JOIN {$wpdb->prefix}workout_exerciseimage AS ei ON `ei`.`exercise`=`e`.`id` 

                                        LEFT JOIN {$wpdb->prefix}workout_exercisecomment AS com ON `com`.`exercise`=`e`.`id` 

                                        WHERE `e`.`category`=".$_POST['category']);

        if(!empty($exercises)){

            foreach ($exercises as $key => $exercise)

                $return[$exercise->id][] = $exercise;

            echo json_encode($return);

        }

        else

            echo json_encode(array('error'=>'no mathces'));



        exit;

    }

    if(!empty($_POST['action']) && ($_POST['action'] == 'save-new-workout-log' || $_POST['action'] == 'save-new-adv-workout-log' || $_POST['action'] == 'save-new-workout-log-sample') ){

        $action = $_POST['action'];

        if($action == 'save-new-workout-log'){

			$table = $wpdb->prefix.'workout_logs';

		}else if($action == 'save-new-adv-workout-log'){

			$table = $wpdb->prefix.'workout_logs_adv';

		}else if($action == 'save-new-workout-log-sample'){

			$table = $wpdb->prefix.'workout_logs_sample';

		}

        $uniq_id = uniqid();



        foreach ( $_POST['workout'] as $key => $value ) {

            if($action == 'save-new-workout-log'){

                $values[] = $wpdb->prepare( "('%s','%s','%d','%d','%d','%s','%s','%s','%d','%d','%s')", $uniq_id, stripslashes_deep($_POST['name']), $current_user->ID, $value['day'], $value['exercise_id'], stripslashes_deep($value['exercise_name']), json_encode(explode(', ',$value['repeats']), JSON_NUMERIC_CHECK), json_encode(explode(', ',$value['weights']), JSON_NUMERIC_CHECK), $_POST['client_id'], 0, current_time('Y-m-d H:i:s'));

            }else if($action == 'save-new-workout-log-sample'){

				$values[] = $wpdb->prepare( "('%s','%s','%d','%d','%d','%s','%s','%s','%d','%d','%s')", $uniq_id, stripslashes_deep($_POST['name']), $current_user->ID, $value['day'], $value['exercise_id'], stripslashes_deep($value['exercise_name']), json_encode(explode(', ',$value['repeats']), JSON_NUMERIC_CHECK), json_encode(explode(', ',$value['weights']), JSON_NUMERIC_CHECK), $_POST['client_id'], 0, current_time('Y-m-d H:i:s'));

			}else{

                $values[] = $wpdb->prepare( "('%s','%s','%d','%d','%s', '%d','%s','%s','%s','%s','%d','%d','%d','%s')", $uniq_id, stripslashes_deep($_POST['name']), $current_user->ID, $value['week'], $value['exercise_order'], $value['exercise_id'], stripslashes_deep($value['exercise_name']), json_encode(explode(', ',$value['repeats']), JSON_NUMERIC_CHECK), json_encode(explode(', ',$value['loads']), JSON_NUMERIC_CHECK), json_encode(explode(', ',$value['repeats']), JSON_NUMERIC_CHECK), $value['tempo'], $_POST['client_id'], 0, current_time('Y-m-d H:i:s'));

			}

        }

        if($action == 'save-new-workout-log'){

            $query = "INSERT INTO ".$table." (uniq_id, name, user_id, day, exercise_id, exercise_name, repeats, weights, client_id, shared, added) VALUES ";

        }else if($action == 'save-new-workout-log-sample'){

			$query = "INSERT INTO ".$table." (uniq_id, name, user_id, day, exercise_id, exercise_name, repeats, weights, client_id, shared, added) VALUES ";

		}else{

            $query = "INSERT INTO ".$table." (uniq_id, name, user_id, week, exercise_order, exercise_id, exercise_name, repeats, loads, rest, tempo, client_id, shared, added) VALUES ";

		}

        

        $query .= implode( ",\n", $values );

        $wpdb->query( $wpdb->prepare("$query ", $values));



        echo $wpdb->last_query;

    }


    if(!empty($_POST) && isset($_POST['action']) && ($_POST['action']=='delete-workout-log' || $_POST['action']=='delete-workout-log-sample' || $_POST['action']=='delete-adv-workout-log') ){

        

		if($action == 'delete-workout-log'){

			$table = $wpdb->prefix.'workout_logs';

		}else if($action == 'delete-adv-workout-log'){

			$table = $wpdb->prefix.'workout_logs_adv';

		}else if($action == 'delete-workout-log-sample'){

			$table = $wpdb->prefix.'workout_logs_sample';

		}

        

        $exist =  $wpdb->get_var( "SELECT user_id FROM ".$table." WHERE user_id=".$current_user->ID." AND uniq_id='".$_POST['uniq_id']."'" );

        

        if($exist == $current_user->ID){

            $deleted = $wpdb->delete( $table, array( 'uniq_id' => $_POST['uniq_id']) );

            echo json_encode(array('success'=>'Workout log deleted.'));

        }else

            echo json_encode(array('error'=>'Cannot delete this log. Try later.'));

        exit();

    }


    if(!empty($_POST) && isset($_POST['action']) && ($_POST['action']=='delete-workout-log-exercise' || $_POST['action']=='delete-workout-log-exercise-sample' || $_POST['action']=='delete-adv-workout-log-exercise') ){

		$action = $_POST['action'];

		

		if($action == 'delete-workout-log-exercise'){

			$table = $wpdb->prefix.'workout_logs';

		}else if($action == 'delete-adv-workout-log-exercise'){

			$table = $wpdb->prefix.'workout_logs_adv';

		}else if($action == 'delete-workout-log-exercise-sample'){

			$table = $wpdb->prefix.'workout_logs_sample';

		}

        if($current_user->ID == 1){

			$deleted = $wpdb->delete( $table, array( 'id' => $_POST['id']) );

            echo json_encode(array('success'=>'Workout log exercise deleted.'));

		}else{

       		 $exist =  $wpdb->get_var( "SELECT user_id FROM ".$table." WHERE user_id=".$current_user->ID." AND id='".$_POST['id']."'" );



       		 if($exist == $current_user->ID){

            	$deleted = $wpdb->delete( $table, array( 'id' => $_POST['id']) );

            	echo json_encode(array('success'=>'Workout log exercise deleted.'));

        	 }else{

            	echo json_encode(array('error'=>'Cannot delete this log. Try later.'));

			 }

		}

        exit();

    }


    if(!empty($_POST) && isset($_POST['action']) && $_POST['action']=='update-workout-exercise' ){

        

        $table = ($_POST['logtype']=='advanced') ? $wpdb->prefix.'workout_logs_adv' : $wpdb->prefix.'workout_logs';

        

        if($_POST['logtype']=='advanced')

            $updated = $wpdb->update( 

                $table,

                array(

                    'week' => $_POST['week'],

                    'exercise_order' => $_POST['exercise_order'],

                    'exercise_id' => $_POST['exercise_id'],

                    'exercise_name' => $_POST['exercise_name'],

                    'tempo' => $_POST['tempo'],

                    'repeats' => json_encode($_POST['repeats'], JSON_NUMERIC_CHECK),

                    'loads' => json_encode($_POST['loads'], JSON_NUMERIC_CHECK),

                    'rest' => json_encode($_POST['rest'], JSON_NUMERIC_CHECK),

                    'added' => current_time( 'mysql' ),

                ),  

                array( 'uniq_id' => $_POST['uniq_id'],

                        'id' => $_POST['id']),

                array('%d','%s','%d','%s','%s','%s','%s','%s','%s'),

                array( '%s','%d')

            );

        else

            $updated = $wpdb->update( 

                $table,

                array(

                    'day' => $_POST['day'],

                    'exercise_id' => $_POST['exercise_id'],

                    'exercise_name' => $_POST['exercise_name'],

                    'repeats' => json_encode($_POST['repeats'], JSON_NUMERIC_CHECK),

                    'weights' => json_encode($_POST['weights'], JSON_NUMERIC_CHECK),

                    'added' => current_time( 'mysql' ),

                ),  

                array( 'uniq_id' => $_POST['uniq_id'],

                        'id' => $_POST['id']),

                array('%d','%d','%s','%s','%s','%s'),

                array( '%s','%d')

            );

        if($updated)

            echo json_encode(array('success'=>$_POST['id']));

        else

            echo json_encode(array('error'=>'Cannot update this log. Try later.'));

        exit();

    }

	if(!empty($_POST) && isset($_POST['action']) && $_POST['action']=='update-workout-exercise-sample' ){

        $table = $wpdb->prefix.'workout_logs_sample';

		$updated = $wpdb->update( 

			$table,

			array(

				'day' => $_POST['day'],

				'exercise_id' => $_POST['exercise_id'],

				'exercise_name' => $_POST['exercise_name'],

				'repeats' => json_encode($_POST['repeats'], JSON_NUMERIC_CHECK),

				'weights' => json_encode($_POST['weights'], JSON_NUMERIC_CHECK),

				'added' => current_time( 'mysql' ),

			),  

			array( 'uniq_id' => $_POST['uniq_id'],

					'id' => $_POST['id']),

			array('%d','%d','%s','%s','%s','%s'),

			array( '%s','%d')

		);

        if($updated)

            echo json_encode(array('success'=>$_POST['id']));

        else

            echo json_encode(array('error'=>'Cannot update this log. Try later.'));

        exit();

    }

    if(!empty($_POST) && isset($_POST['action']) && $_POST['action']=='insert-workout-exercise' ){



        $table = ($_POST['logtype']=='advanced') ? $wpdb->prefix.'workout_logs_adv' : $wpdb->prefix.'workout_logs';



        $existed = $wpdb->get_row("SELECT * FROM ".$table." WHERE uniq_id='".$_POST['uniq_id']."'" );



        if($_POST['logtype'] == 'advanced'){

            if($existed)

                $wpdb->insert(

                    $table, 

                    array(

                        'uniq_id' => $existed->uniq_id,

                        'user_id' => $existed->user_id,

                        'name' => $existed->name,

                        'week' => $_POST['week'],

                        'exercise_order' => $_POST['exercise_order'],

                        'exercise_id' => $_POST['exercise_id'],

                        'exercise_name' => $_POST['exercise_name'],

                        'tempo' => $_POST['tempo'],

                        'repeats' => json_encode($_POST['repeats'], JSON_NUMERIC_CHECK),

                        'loads' => json_encode($_POST['loads'], JSON_NUMERIC_CHECK),

                        'rest' => json_encode($_POST['rest'], JSON_NUMERIC_CHECK),

                        'shared' => $existed->shared,

                        'client_id' => $existed->client_id,

                        'added' => current_time( 'mysql' ),

                    ),

                    array(

                        '%s','%d','%s','%d','%s','%d','%s','%s','%s','%s','%s','%d','%d','%s'

                    )

                );

        }

        else{

            if($existed)

                $wpdb->insert(

                    $table, 

                    array(

                        'uniq_id' => $existed->uniq_id,

                        'user_id' => $existed->user_id,

                        'name' => $existed->name,

                        'day' => $_POST['day'],

                        'exercise_id' => $_POST['exercise_id'],

                        'exercise_name' => $_POST['exercise_name'],

                        'repeats' => json_encode($_POST['repeats'], JSON_NUMERIC_CHECK),

                        'weights' => json_encode($_POST['weights'], JSON_NUMERIC_CHECK),

                        'shared' => $existed->shared,

                        'client_id' => $existed->client_id,

                        'added' => current_time( 'mysql' ),

                    ),

                    array(

                        '%s','%d','%s','%d','%d','%s','%s','%s','%d','%d','%s'

                    )

                );

        }

        if($wpdb->insert_id)

            echo json_encode(array('success'=>$wpdb->insert_id));

        else

            echo json_encode(array('error'=>'Cannot create this log. Try later.'));

        exit();

    }
	  if(!empty($_POST) && isset($_POST['action']) && $_POST['action']=='insert-workout-exercise-sample' ){



        $table = $wpdb->prefix.'workout_logs_sample';



        $existed = $wpdb->get_row("SELECT * FROM ".$table." WHERE uniq_id='".$_POST['uniq_id']."'" );



		if($existed)

			$wpdb->insert(

				$table, 

				array(

					'uniq_id' => $existed->uniq_id,

					'user_id' => $existed->user_id,

					'name' => $existed->name,

					'day' => $_POST['day'],

					'exercise_id' => $_POST['exercise_id'],

					'exercise_name' => $_POST['exercise_name'],

					'repeats' => json_encode($_POST['repeats'], JSON_NUMERIC_CHECK),

					'weights' => json_encode($_POST['weights'], JSON_NUMERIC_CHECK),

					'shared' => $existed->shared,

					'client_id' => $existed->client_id,

					'added' => current_time( 'mysql' ),

				),

				array(

					'%s','%d','%s','%d','%d','%s','%s','%s','%d','%d','%s'

				)

			);

        if($wpdb->insert_id)

            echo json_encode(array('success'=>$wpdb->insert_id));

        else

            echo json_encode(array('error'=>'Cannot create this log. Try later.'));

        exit();

    }

    if(!empty($_POST) && isset($_POST['action']) && ($_POST['action']=='share-workout-log' || $_POST['action']=='unshare-workout-log') ){



        $action = ($_POST['action']=='share-workout-log') ? 1 : 0;    

        $table = ($_POST['logtype'] == 'advanced' ) ? $wpdb->prefix.'workout_logs_adv' : $wpdb->prefix.'workout_logs';

        

        $exist =  $wpdb->get_var( "SELECT user_id FROM ".$table." WHERE user_id=".$current_user->ID." AND uniq_id='".$_POST['uniq_id']."'" );

        

        if($exist == $current_user->ID){

            $updated = $wpdb->update( $table,

                                        array( 'shared' => $action ),  

                                        array( 'uniq_id' => $_POST['uniq_id'] ));

            echo json_encode(array('success'=>'Workout log shared.'));

        }

        else

            echo json_encode(array('error'=>'Cannot share this log. Try later.'));

        exit();

    }

    if(!empty($_POST) && isset($_POST['action']) && !empty($_POST['canvas']) && 

        ($_POST['action']=='to-wall-workout-log' || $_POST['action']=='to-wall-fitbit'

        || $_POST['action']=='to-wall-macronutrient' || $_POST['action']=='to-wall-iifym' || $_POST['action']=='to-wall-keto'

        || $_POST['action']=='to-wall-onerepmax' )  ){



        $canvas = $_POST['canvas'];

        $filename = md5(uniqid()) . '.jpg';



        switch ($_POST['action']) {

            case 'to-wall-workout-log':

                $table = ($_POST['logtype']=='advanced') ? $wpdb->prefix.'workout_logs_adv' : $wpdb->prefix.'workout_logs';

                $workout_log =  $wpdb->get_row( "SELECT * FROM ".$table." WHERE uniq_id='".$_POST['uniq_id']."'" );

                //if this is self created log

                if($workout_log->client_id == 0){



                    if($workout_log->user_id == $current_user->ID){

                        $activity_title =  'My "'.strtoupper($workout_log->name).'" workout log, created at '.date('F j, Y',strtotime($workout_log->added));

                        $activity_content = 'My "'.strtoupper($workout_log->name).'" workout log, created at '.date('F j, Y',strtotime($workout_log->added));    

                    }else{

                        $activity_title =  '"'.strtoupper($workout_log->name).'" workout log, created at '.date('F j, Y',strtotime($workout_log->added)).' by my Client '.get_fullname($workout_log->user_id);

                        $activity_content = '"'.strtoupper($workout_log->name).'" workout log, created at '.date('F j, Y',strtotime($workout_log->added)).' by my Client '.get_fullname($workout_log->user_id);

                    }

                }else{

                    $activity_title = '"'.strtoupper($workout_log->name).'" workout log, created at '.date('F j, Y',strtotime($workout_log->added)).' '.( (bp_get_member_type($current_user->ID) == 'standard') ? 'by '.get_fullname($workout_log->user_id) : 'for '.get_fullname($workout_log->client_id) );

                    $activity_content = '"'.strtoupper($workout_log->name).'" workout log, created at '.date('F j, Y',strtotime($workout_log->added)).' '.( (bp_get_member_type($current_user->ID) == 'standard') ? 'by '.get_fullname($workout_log->user_id) : 'for '.get_fullname($workout_log->client_id) );

                }                

            break;

            case 'to-wall-fitbit':

                $activity_title = 'My Fitbit results';

                $activity_content = 'My Fitbit results';

            break;



            case 'to-wall-macronutrient':

                $activity_title =  'New Macronutrient calculation results';

                $activity_content = 'New Macronutrient calculation results on the '.current_time('d F Y');

            break;



            case 'to-wall-keto':

                $activity_title =  'New Keto calculation results';

                $activity_content = 'New Keto calculation results on the '.current_time('d F Y');

            break;



            case 'to-wall-iifym':

                $activity_title =  'New IIFM calculation results';

                $activity_content = 'New IIFM calculation results on the '.current_time('d F Y');

            break;



            case 'to-wall-onerepmax':

                $activity_title =  'New One-Rep Max results';

                $activity_content = 'New One-Rep Max calculation results on the '.current_time('d F Y');

            break;

            default:

                exit();

                break;

        }

        //remove base64data

        $uri =  substr($canvas,strpos($canvas,",")+1);

        

        $wp_upload_dir = wp_upload_dir();

        if (!is_dir($wp_upload_dir['path']) || !is_writable($wp_upload_dir['path']))

            return WRITING_PROBLEMS; 



        file_put_contents($wp_upload_dir['path'] . '/' . basename($filename), base64_decode($uri), LOCK_EX);        

        //set title and content of the activity post

        //save activity post and return saver post id

        $activity_id = bp_activity_add ( array(

            'action' => $activity_title,

            'content' => $activity_content,

            'component' => 'activity',

            'type' => 'activity_update',

            'user_id' => $bp->loggedin_user->id,

            'item_id' => $bp->displayed_user->id

        ));

        

        // Check the type of file. We'll use this as the 'post_mime_type'.

        $filetype = wp_check_filetype( basename( $filename ), null );



        // Prepare an array of post data for the attachment.

        $attachment = array(

            'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 

            'post_mime_type' => $filetype['type'],

            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

            'post_content'   => '',

            'post_status'    => 'inherit'

        );



        // Insert the attachment and conect it with activity post id.

        $attachment_id = wp_insert_attachment( $attachment, $wp_upload_dir['subdir'] . '/' .$filename, $activity_id );

        

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.

        require_once( ABSPATH . 'wp-admin/includes/image.php' );



        // Generate the metadata for the attachment, and update the database record.NOTICE $wp_upload_dir['path']!!!

        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $wp_upload_dir['path'] . '/' . basename($filename) );

        wp_update_attachment_metadata( $attachment_id, $attachment_data );

        set_post_thumbnail( $activity_id, $attachment_id );

        

        //insert info about attachment and activity to media table

        $wpdb->insert(

                $wpdb->prefix . 'buddyboss_media', array(

                    'blog_id' => get_current_blog_id(),

                    'media_id' => $attachment_id,

                    'media_author' => get_current_user_id(),

                    'media_title' => $activity_title,

                    'activity_id' => $activity_id,

                    'upload_date' => current_time( 'mysql' ),

                ),

                array(

                    '%d','%d','%d','%s','%d','%d',

                )

        );



        //update media meta

        bp_activity_update_meta($activity_id, 'buddyboss_wall_action', '%INITIATOR% posted an update' );

        bp_activity_update_meta($activity_id, 'buddyboss_media_action', '%USER% posted a photo' );

        bp_activity_update_meta($activity_id, 'buddyboss_media_aid', $attachment_id );

        bp_activity_update_meta($activity_id, 'buddyboss_wall_initiator', $current_user->ID );

    }


    if(!empty($_POST) && isset($_POST['action']) && ($_POST['action']=='share-fitbit-account' || $_POST['action']=='unshare-fitbit-account')){



        if($_POST['action']=='share-fitbit-account')    

            $return = update_user_meta($current_user->ID, 'fitbit_shared', 1);

        else

            $return = delete_user_meta($current_user->ID, 'fitbit_shared');

        

        if($return)

            echo json_encode(array('success'=>'Fitbit account successfully shared/unshared.'));

        else

            echo json_encode(array('error'=>'Cannot share/unshare fitbit account. Try later.'));

        

        exit();

    }


if(!empty($_POST) && isset($_POST['save-noticeboards-post']) ){

    //if this is new noticeboards post

    $post_id = false;

    if( empty($_POST['save-noticeboards-post']) ){



        $new_post = array(

            'post_title' => $_POST['noticeboard-title'],

            'post_content' => $_POST['noticeboard-content'],

            'post_status' => 'publish',

            'post_date' => current_time('Y-m-d H:i:s'),

            'post_author' => $current_user->ID,

            'post_type' => 'noticeboards'

        );

        $post_id = wp_insert_post($new_post);



        update_post_meta($post_id,'noticeboard-link',$_POST['noticeboard-link']);



    }  else {

        $post_id = $_POST['save-noticeboards-post'];

        $notice_post = array(

            'ID'           => $post_id,

            'post_title' => $_POST['noticeboard-title'],

            'post_content' => $_POST['noticeboard-content'],

            'post_date' => current_time('Y-m-d H:i:s'),

        );

        update_post_meta($post_id,'noticeboard-link',$_POST['noticeboard-link']);

        wp_update_post( $notice_post );

    }

    if (!function_exists('wp_generate_attachment_metadata')){

        require_once(ABSPATH . "wp-admin" . '/includes/image.php');

        require_once(ABSPATH . "wp-admin" . '/includes/file.php');

        require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    }    

    if ($_FILES['noticeboard-image']['size'] != 0) {

        foreach ($_FILES as $file => $array) {

            if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {

                return "upload error : " . $_FILES[$file]['error'];

            }

            $attach_id = media_handle_upload( $file, $post_id );

        }
	

		if ($attach_id > 0){

       	 	update_post_meta($post_id,'_thumbnail_id',$attach_id); 

		} 

    }

    wp_redirect( home_url()."/gym-noticeboard" ); exit;

}


if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'delete-noticeboards-post'){

    global $wpdb;

    delete_post_thumbnail( $_POST['post_id'] );

    $deleted = wp_delete_post($_POST['post_id'],true);

    return $deleted;

}


if(!empty($_POST) && isset($_POST['save-timetables']) ){

    $spec = (isset($_POST['timetables-custom-spec']) && !empty($_POST['timetables-custom-spec'])) ? $_POST['timetables-custom-spec'] : $_POST['timetables-spec'];

    if( empty($_POST['save-timetables']) ){
		
		$day_data = implode(', ', $_POST['day']);

        $wpdb->insert(

            $wpdb->prefix.'timetables', 

            array(

                'user_id' => $current_user->ID,

                'classname' => $_POST['timetables-classname'],

                'classsize' => $_POST['timetables-classsize'],

                'specialization' => $spec,

                'trainer_id' => $_POST['timetables-trainer'],
				
				'day' => $day_data,

                'date' => strtotime($_POST['timetables-date']),

                'time' => $_POST['timetables-time'],

                'duration' => $_POST['timetables-duration'],

                'added' => current_time( 'mysql' ),

            ),

            array(

                '%d','%s','%d','%s','%d','%s','%s','%s','%d','%s'

            )

        );

    }  else {


        $wpdb->update( $wpdb->prefix.'timetables',

            array( 'classname' => $_POST['timetables-classname'],

            'classsize' => $_POST['timetables-classsize'],

            'specialization' => $spec,

            'trainer_id' => $_POST['timetables-trainer'],

            'date' => strtotime($_POST['timetables-date']),

            'time' => $_POST['timetables-time'],

            'duration' => $_POST['timetables-duration']

             ),

            array( 'id' => $_POST['save-timetables'] ));

    }

    wp_redirect( home_url()."/timetables" ); exit;

}


if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'delete-custom-timetables'){

    global $wpdb;
	 
	$flag = $wpdb->get_results( "DELETE FROM wp_timetables WHERE `id`=".$_POST['id']);
	
	//$flag = $wpdb->delete( $wpdb->prefix.'timetables', array( 'id' => $_POST['id']) );

   return $flag;

}


if(!empty($_POST) && isset($_POST['action']) && $_POST['action'] == 'get-timetables'){

    $timetables = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}timetables WHERE user_id = ".$_POST['gym_id']." AND date=".strtotime($_POST['date'])." ORDER BY time ASC" );

    if($timetables){

        $result = array();

        foreach ($timetables as $key => $value) {

            $member_type = bp_get_member_type($current_user->ID);

            $action = false;

            if( $member_type != 'pt' ){

               /* $action = ( $member_type == 'gym' ) 

                            ? '<i data-id="'. $value->id.'" class="fa fa-edit fa-lg edit-timetables"></i>&nbsp<i data-id="'.$value->id.'" class="fa fa-trash fa-lg delete-timetables"></i>' 

                            : ( (user_is_connected($value->user_id)) ? '<button data-id="'.$value->id.'" type="button" class="btn inverse book-taining">Book</button>' : '<small>Please become a GYM member to book a training</small>');
*/
            }

            $result[$key]['id'] = $value->id;

            $result[$key]['classname'] = $value->classname;

            $result[$key]['classsize'] = $value->classsize;

            $result[$key]['spec'] = $value->specialization;

            $result[$key]['date'] = $value->date;

            $result[$key]['date_'] = date('F d, Y',$value->date);

            $result[$key]['time'] = $value->time;

            $result[$key]['time_'] = date('H:i',$value->time);

            $result[$key]['duration'] = $value->duration;

            $result[$key]['duration_'] = date('H:i',$value->duration);

            $result[$key]['trainer_id'] = $value->trainer_id;

            $result[$key]['trainer_name'] = get_fullname($value->trainer_id);

            $result[$key]['action'] = $action;

        }

        echo json_encode(array('success'=>$result));

    }

    else

        echo json_encode(array('error'=>'No timetables found for this date'));

}


if (isset($_POST['review_member_submit_custom']) && is_user_logged_in()) {

        

        $user_reviewd = $_POST['rating_member_id'];

        $user_reviewd_name = $_POST['rating_member_name'];

        $avartar_reviewd = "";

        $link_set = bp_core_get_user_domain($current_user->ID);



        if (!prorevs_review_limit_exceeded($options, $current_user->ID, $user_reviewd)) {

                add_action('template_notices', 'prorevs_add_title_here_success');

                $rating_member = $_POST['rating_member'];

                $contentss .= '<span class="rating-top">';

                for ($dem = 1; $dem < 6; $dem++)

                    $contentss .= ($dem <= $rating_member) ? '<i class="fa fa-star rating-star"></i>' : '<i class="fa fa-star-o rating-star"></i>';

                $contentss .= '</span>'; 

                $user_id = $current_user->ID;

                $component = "Members";

                $type = "Member_review";

                $hide_sitewide = 0;

                $action = "<a href='".$link_set."' title='".$current_user->user_login."'>".get_fullname($current_user->ID)."</a> posted an Review ".bp_core_fetch_avatar( 'item_id='.$user_reviewd )."<a href='".bp_core_get_user_domain($user_reviewd)."'>".get_fullname($user_reviewd)."</a>";

                $content = $contentss.' '.htmlspecialchars($_POST['review_member_content']);

                $primary_link = $link_set;

                $item_id = "";

                $secondary_item_id = "";

                $date_recorded = current_time('mysql');

                $hide_sitewide = 0;

                $mptt_left = 0;

                $mptt_right = 0;

                $star = $rating_member;

                $usercheck = $user_reviewd;

                $anonymous = ($options['anonymous'] && isset($_POST['anonymous']) && $_POST['anonymous'] ? 1 : 0);



                prorevs_add_review($user_id, $component, $type, $action, $content, $primary_link, $item_id, $secondary_item_id, $date_recorded, $hide_sitewide, $mptt_left, $mptt_right, $star, $usercheck, $anonymous, 0, 1);



                $setcheckoption = $user_id . "-" . $usercheck;

                $checkfirst = get_option($setcheckoption);

                if ($checkfirst)

                    update_option($setcheckoption, $checkfirst + 1);

                else

                    add_option($setcheckoption, 1, '', 'yes');

        }

        wp_redirect( bp_core_get_user_domain($user_reviewd).'/reviews' );

    }



/*

    if(!empty($_POST) && isset($_POST['get_training_plans'])){

        $gender = $_POST['gender'];

        if(isset($_POST['bodyparts'])&&!empty($_POST['bodyparts'])){

          $query = $wpdb->prepare("

          SELECT {$wpdb->prefix}posts.* FROM {$wpdb->prefix}posts  

            LEFT JOIN {$wpdb->prefix}postmeta AS mt1 ON ( {$wpdb->prefix}posts.ID = mt1.post_id )  

            LEFT JOIN {$wpdb->prefix}postmeta AS mt2 ON ( {$wpdb->prefix}posts.ID = mt2.post_id )

            WHERE 

              {$wpdb->prefix}posts.post_type = 'training-plan' 

              AND {$wpdb->prefix}posts.post_status = 'publish'

              AND ( mt1.meta_key = 'wpcf-training-plan-gender' AND mt1.meta_value = '{$gender}' ) 

              AND (

                       (mt2.meta_key = 'wpcf-training-plan-bodyparts') 

                       AND

                       (mt2.meta_value REGEXP '%s')

                )  

              GROUP BY {$wpdb->prefix}posts.ID 

              ORDER BY {$wpdb->prefix}posts.post_date ASC

          ", implode("|",$_POST['bodyparts']));

        }else{

            $query = $wpdb->prepare("

          SELECT {$wpdb->prefix}posts.* FROM {$wpdb->prefix}posts  

            LEFT JOIN {$wpdb->prefix}postmeta AS mt1 ON ( {$wpdb->prefix}posts.ID = mt1.post_id )  

            LEFT JOIN {$wpdb->prefix}postmeta AS mt2 ON ( {$wpdb->prefix}posts.ID = mt2.post_id )

            WHERE 

              {$wpdb->prefix}posts.post_type = 'training-plan' 

              AND {$wpdb->prefix}posts.post_status = 'publish'

              AND ( mt1.meta_key = 'wpcf-training-plan-gender' AND mt1.meta_value = '{$gender}' ) 

              AND ( mt2.meta_key = 'wpcf-training-plan-category' AND mt2.meta_value = '%s')

              GROUP BY {$wpdb->prefix}posts.ID 

              ORDER BY {$wpdb->prefix}posts.post_date ASC

          ", $_POST['category']);

        }


        $training_plans = $wpdb->get_results($query);

        foreach($training_plans as $key=>$plan){

            $return[$key]['title'] = $plan->post_title;

            $return[$key]['attachment'] = get_post_custom_values('wpcf-training-plan-file', $plan->ID);

        }

        echo json_encode($return);

    }


    if(!empty($_POST) && isset($_POST['get_nutrition_plans'])){



        $query = array(

                    array(

                        'key' => 'wpcf-nutrition-plan-gender',

                        'value' => $_POST['gender'],

                        'compare' => '='

                    ),

                    array(

                        'key' => 'wpcf-nutrition-plan-category',

                        'value' => $_POST['category'],

                        'compare' => '='

                    )

                );


        $args = array(

            'order'             => 'ASC',

            'post_type'         => 'nutrition-plan',

            'meta_query'        => $query,

            'post_status'       => 'publish',

            'posts_per_page'    => -1,

        );

        $nutrition_plans = get_posts( $args );

        foreach($nutrition_plans as $key=>$plan){

            $return[$key]['title'] = $plan->post_title;

            $return[$key]['attachment'] = get_post_custom_values('wpcf-nutrition-plan-file', $plan->ID);

        }

        echo json_encode($return);

    }


*/


?>