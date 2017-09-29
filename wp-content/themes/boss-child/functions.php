<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
/**
 * @package Boss Child Theme
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */


require_once( 'mm-options/mm-options.php' );
require_once( 'mm-landing_page/mm-landing_page.php' );

/**************************************************************
          SCRIPTS LOADING
***************************************************************/

function boss_child_theme_setup(){
  load_theme_textdomain( 'boss', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'boss_child_theme_setup' );


add_filter( 'script_loader_tag', 'add_cfasync_false', 10, 3 );

function add_cfasync_false( $tag, $handle, $src ) {

    // add any script handle to the array to set its cfasync attribute to false
    $handles = array ( 'jquery-fancybox', 'jquery-easing', 'jquery-mousewheel', 'jquery-metadata' );

    if ( in_array( $handle, $handles ) ) {
        $tag = '<script type="text/javascript" data-cfasync="false" src="' . $src . '"></script>';
    }

    return $tag;
}

function boss_child_theme_scripts_styles(){

    wp_deregister_script( 'selectboxes' );
    wp_deregister_style( 'fontawesome' );
    //wp_deregister_style( 'buddyboss-inbox-fontawesome' );
   // wp_deregister_style( 'buddyboss-wall-fontawesome' );
    //wp_deregister_style( 'buddyboss-media-fontawesome' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );


    wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js','','',true);
    wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.10.3/jquery-ui.js','','',true);
    wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js','','',true);
    wp_enqueue_script( 'magnific-popup','https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js','','',true);
   // wp_enqueue_script( 'validation', get_stylesheet_directory_uri().'/js/validation/jquery.validate.min.js','','',true);
    wp_enqueue_script( 'validation-additional', get_stylesheet_directory_uri().'/js/validation/additional-methods.min.js','','',true);      
    wp_enqueue_script( 'flot', get_stylesheet_directory_uri().'/js/flot/jquery.flot.min.js','','',true);
    wp_enqueue_script( 'flot-axis-labels', get_stylesheet_directory_uri().'/js/flot/jquery.flot.axislabels.js','','',true);
    wp_enqueue_script( 'flot-time', get_stylesheet_directory_uri().'/js/flot/jquery.flot.time.min.js','','',true);
    wp_enqueue_script( 'flot-navigate', get_stylesheet_directory_uri().'/js/flot/jquery.flot.navigate.js','','',true);
    wp_enqueue_script( 'flot-pie', get_stylesheet_directory_uri().'/js/flot/jquery.flot.pie.min.js','','',true);
    wp_enqueue_script( 'flot-resize', get_stylesheet_directory_uri().'/js/flot/jquery.flot.resize.min.js','','',true);
    wp_enqueue_script( 'flot-canvas', get_stylesheet_directory_uri().'/js/flot/jquery.flot.canvas.min.js','','',true);
    wp_enqueue_script( 'chosen', get_stylesheet_directory_uri().'/js/chosen.jquery.min.js','','',true);
    wp_enqueue_script( 'html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js','','',true);
    wp_enqueue_script( 'quicksearch', get_stylesheet_directory_uri().'/js/jquery.quicksearch.js','','',true);
    wp_enqueue_script( 'printElement', get_stylesheet_directory_uri().'/js/jquery.printElement.min.js','','',true);
    wp_dequeue_script( 'googlemaps' );//deregisted buddystream script include
    wp_deregister_style( 'googlemaps' );//deregisted buddystream script include
    wp_enqueue_script( 'mapsapi', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDHKB9Y1_R9tuSqYJMSqaAIFsFGw9qMhm8&libraries=places','','',true);
    wp_enqueue_script( 'geocomplete', get_stylesheet_directory_uri().'/js/jquery.geocomplete.min.js','','',true);
    wp_enqueue_script( 'scrollTo', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js','','',true);
    wp_enqueue_script( 'footable', get_stylesheet_directory_uri().'/js/footable.js','','',true);
    wp_enqueue_script( 'intlTelInput', get_stylesheet_directory_uri().'/js/intlTelInput.min.js','','',true);
    wp_enqueue_script( 'intlTelInput-utils', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.8/js/utils.js','','',true);
    wp_enqueue_script( 'moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.min.js','','',true);
    wp_enqueue_script( 'moment-tz', 'https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.2/moment-timezone-with-data.min.js','','',true);
    wp_enqueue_script( 'responsive-tabs', 'https://cdn.jsdelivr.net/jquery.responsive-tabs/1.6.1/jquery.responsiveTabs.min.js','','',true);


	if(is_page('training-schedule') || is_page('trainer-calendar'))
		//wp_enqueue_script( 'custom-calendar', get_stylesheet_directory_uri().'/js/custom-calendar.js','','',true);

	if(is_page('timetables')){
    	wp_enqueue_script( 'footable-filter', get_stylesheet_directory_uri().'/js/footable.filter.js','','',true);
    	wp_enqueue_script( 'footable-sort', get_stylesheet_directory_uri().'/js/footable.sort.js','','',true);
		wp_enqueue_script( 'custom-timetables', get_stylesheet_directory_uri().'/js/custom-timetables.js');
	}


  if(is_page('trainer-clients'))
    wp_enqueue_script( 'custom-my-clients', get_stylesheet_directory_uri().'/js/custom-my-clients.js','','',true);

  if(is_page('my-trainers'))
    wp_enqueue_script( 'custom-my-trainers', get_stylesheet_directory_uri().'/js/custom-my-trainers.js','','',true);

  if(is_page('gym-members'))
    wp_enqueue_script( 'custom-gym-members', get_stylesheet_directory_uri().'/js/custom-gym-members.js','','',true);

  if(is_page('gym-trainers'))
    wp_enqueue_script( 'custom-gym-trainers', get_stylesheet_directory_uri().'/js/custom-gym-trainers.js','','',true);

  if(is_page('my-gym'))
    wp_enqueue_script( 'custom-my-gym', get_stylesheet_directory_uri().'/js/custom-my-gym.js','','',true);
  
  if(is_page('client-progress'))
  	wp_enqueue_script( 'custom-gym-members', get_stylesheet_directory_uri().'/js/custom-gym-members.js','','',true);
    wp_enqueue_script( 'custom-client-progress', get_stylesheet_directory_uri().'/js/custom-client-progress.js','','',true);

  if(is_page('my-progress')){
      wp_enqueue_script( 'slick', get_stylesheet_directory_uri().'/js/slick.min.js','','',true);
      wp_enqueue_script( 'mousewheel', get_stylesheet_directory_uri().'/js/jquery.mousewheel.min.js','','',true);
      wp_enqueue_script( 'custom-my-progress', get_stylesheet_directory_uri().'/js/custom-my-progress.js','','',true);
      wp_enqueue_script( 'footable-paginate', get_stylesheet_directory_uri().'/js/footable.paginate.js','','',true);
  }


  if(is_page('my-fitbit'))
    wp_enqueue_script( 'custom-my-fitbit', get_stylesheet_directory_uri().'/js/custom-my-fitbit.js','','',true);

  if(is_page('workout-log')){
    wp_enqueue_script( 'custom-workout-log', get_stylesheet_directory_uri().'/js/custom-workout-log.js','','',true);
  }

  if(is_page('food-supplement-diary')){
      wp_enqueue_style( 'print-diaries', get_stylesheet_directory_uri().'/css/print-diaries.css', false ); 
      wp_enqueue_script( 'custom-food-supplement', get_stylesheet_directory_uri().'/js/custom-food-supplement.js','','',true);
  }
  if(is_page('nutrition-diary')){
      wp_enqueue_style( 'print-diaries', get_stylesheet_directory_uri().'/css/print-diaries.css', false ); 
      wp_enqueue_script( 'custom-food-supplement', get_stylesheet_directory_uri().'/js/custom-food-supplement.js','','',true);
  }

  if(is_page('nutrition-guidelines')){
    wp_enqueue_style( 'print-nutrition-guidelines', get_stylesheet_directory_uri().'/css/print-nutrition-guidelines.css', false ); 
    wp_enqueue_script( 'custom-nutrition-guidelines', get_stylesheet_directory_uri().'/js/custom-nutrition-guidelines.js','','',true); 
  }

  if(is_page('faqs')){
      wp_enqueue_script( 'footable-paginate', get_stylesheet_directory_uri().'/js/footable.paginate.js','','',true);
      wp_enqueue_script( 'footable-filter', get_stylesheet_directory_uri().'/js/footable.filter.js','','',true);
      wp_enqueue_script( 'footable-sort', get_stylesheet_directory_uri().'/js/footable.sort.js','','',true);
  }

  if(is_page('gym-noticeboard'))
    wp_enqueue_script( 'custom-noticeboard', get_stylesheet_directory_uri().'/js/custom-noticeboard.js','','',true);
  
  wp_enqueue_script( 'custom', get_stylesheet_directory_uri().'/js/custom.js?'.rand(0,100),'','',true );

  $mmshare_options = get_option("mmshare_options");
  $mmshare_facebook_app = $mmshare_options["mmshare_facebook_app"];
  $mmshare_google_app = $mmshare_options["mmshare_google_app"];
  $mpi_options = get_option("mpi_options");
  $mpi_share_photo_desc = stripslashes_deep($mpi_options["mpi_share_photo_desc"]);

  wp_localize_script( 'custom', 'mirrorMuscles', array( 
    'ajaxPath' => 'https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/handler.php',
    'ajaxPathAdmin' => admin_url('admin-ajax.php'),
    'fatsecretPath' => 'https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/fatsecret.php',
    'fitbitPath' => 'https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/fitbit.php',
    'pluginPath' => 'https://www.mirrormuscles.com/wp-content/plugins/mirror-muscles/',
    'homeUrl'=>home_url(),
    'themeDir'=>get_stylesheet_directory_uri(),
    'currentFullname'=>bp_get_profile_field_data('field=1&user_id='.get_current_user_id()).' '.bp_get_profile_field_data('field=8&user_id='.get_current_user_id()),
    'fbAppId'=>$mmshare_facebook_app,
    'googleAppId'=>$mmshare_google_app,
    'sharePhotoDesc'=>$mpi_share_photo_desc
  ));

  wp_localize_script( 'password-strength-meter', 'pwsL10n', array(
    'empty' => __( 'Strength indicator' ),
    'short' => __( 'Very weak <a class="fa fa-warning" rel="popover" data-content="<small>It seems like the password you\'re using is very weak. We highly recommend you create a unique password. But if you don\'t want to create a strong password - you can continue to register</small>"></a>' ),
    'bad' => __( 'Weak <a class="fa fa-warning" rel="popover" data-content="<small>It seems like the password you\'re using is weak. We highly recommend you create a unique password. But if you don\'t want to create a strong password - you can continue to register</small>"></a>' ),
    'good' => _x( 'Medium', 'password strength' ),
    'strong' => __( 'Strong' ),
    'mismatch' => __( 'Mismatch' )
  ));
  

  wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri().'/css/bootstrap.min.css', false ); 
  wp_enqueue_style( 'bootstrap-theme', 'https://code.jquery.com/ui/1.10.4/themes/hot-sneaks/jquery-ui.css', false );
  wp_enqueue_style( 'jquery-ui', get_stylesheet_directory_uri().'/css/bootstrap-theme.min.css', false );
  wp_enqueue_style( 'magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', false );
  wp_enqueue_style( 'slick', get_stylesheet_directory_uri().'/css/slick.css', false );
  //wp_enqueue_style( 'chosen', get_stylesheet_directory_uri().'/css/chosen.min.css', false );
  wp_enqueue_style( 'fancybox', get_stylesheet_directory_uri().'/css/jquery.fancybox-1.3.4.css', false );
  wp_enqueue_style( 'footable', get_stylesheet_directory_uri().'/css/footable.core.min.css', false );
  wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', false );
  wp_enqueue_style( 'intlTelInput', get_stylesheet_directory_uri().'/css/intlTelInput.css', false );
  wp_enqueue_style( 'responsive-tabs', 'https://cdn.jsdelivr.net/jquery.responsive-tabs/1.6.1/responsive-tabs.css', false );
  


  if(is_page('parq'))
    wp_enqueue_style( 'print-parq.css', get_stylesheet_directory_uri().'/css/print-parq.css', false ); 

  if(is_page('workout-log'))
    wp_enqueue_style( 'print-workout-log.css', get_stylesheet_directory_uri().'/css/print-workout-log.css', false ); 

  if(is_page('my-fitbit'))
    wp_enqueue_style( 'print-fitbit.css', get_stylesheet_directory_uri().'/css/print-fitbit.css', false ); 

  if(is_page('one-rep-max')){
    wp_enqueue_script( 'one-rep-max', get_stylesheet_directory_uri().'/js/custom-one-rep-max.js','','',true);
    wp_enqueue_script( 'footable-paginate', get_stylesheet_directory_uri().'/js/footable.paginate.js','','',true);
    wp_enqueue_style( 'print-onerepmax', get_stylesheet_directory_uri().'/css/print-onerepmax.css', false ); 
  }


  wp_enqueue_style( 'boss-child-custom', get_stylesheet_directory_uri().'/css/custom.css' );
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_scripts_styles',99999);



//remove js from reviews plugin(put this code to custom.js) 
remove_action('wp_head', 'insert_js_depro', 1);



//load scripts on admin area
function admin_load_scripts($hook_suffix) {
  if($hook_suffix != 'buddyboss_page_boss_options') {
    wp_enqueue_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js' );
    wp_enqueue_script( 'mapsapi', 'http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places');
    wp_enqueue_script( 'geocomplete', get_stylesheet_directory_uri().'/js/jquery.geocomplete.min.js' );
    wp_enqueue_script( 'custom-admin', get_stylesheet_directory_uri().'/js/custom-admin.js' );
  }
}
add_action('admin_enqueue_scripts', 'admin_load_scripts', 100);



function login_page_enqueue_style() {
  ?>
    <style type="text/css">
        .login h1 a {
            background: url(<?php echo get_stylesheet_directory_uri(); ?>/images/mmlogo2.png) !important;
            padding-bottom: 50px;
        }
		@media screen and (max-width: 720px) {
			body.login #login{
			  margin: 4% auto !important;
			  padding: 0px 35px !important;
			}
			#login h1 a {
				width: 230px !important;
			}
		
		}
		@media screen and (max-width: 400px) {
			body.login #login{
			  margin: 0% auto !important;
			  padding: 0px 35px !important;
			}
		}
    </style>
  <?php
  wp_enqueue_style( 'bosschild-login', get_stylesheet_directory_uri().'/css/login.css', false );
  wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}
add_action( 'login_enqueue_scripts', 'login_page_enqueue_style', 100 );



function add_my_favicon() {
   $favicon_path1 = get_stylesheet_directory_uri().'/images/favicon.ico ';
   echo '<link rel="shortcut icon" href="' . $favicon_path1 . '?v='.rand(1,15).'" type="image/x-icon" />';
}
add_action( 'wp_head', 'add_my_favicon' );
add_action( 'admin_head', 'add_my_favicon' );
add_action('login_head', 'add_my_favicon');


/**************************************************************
    END SCRIPTS LOADING
***************************************************************/



/**************************************************************
                    MEMBER TYPES
***************************************************************/

//register member types
function mm_register_member_types() {
    bp_register_member_type( 'standard', array(
        'labels' => array(
            'name'          => 'Standard Users',
            'singular_name' => 'Standard User'
        ),
    ) );

    bp_register_member_type( 'pt', array(
        'labels' => array(
            'name'          => 'Personal Trainers',
            'singular_name' => 'Personal Trainer',
        ),
    ) );

    bp_register_member_type( 'gym', array(
        'labels' => array(
            'name'          => 'GYM Users',
            'singular_name' => 'GYM User',
        ),
    ) );
}
add_action( 'bp_init', 'mm_register_member_types' );



//display member type xprofile selectbox as radio buttons
add_filter( 'bd_xprofile_field_type_membertype_as_radio', '__return_true');

//remove automatic links in profile field words
remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data', 9,2 );

//count members by types
function mm_count_member_types( $member_type = '', $taxonomy = 'bp_member_type' ) {
    global $wpdb;

    $member_types = bp_get_member_types();
    bp_core_clear_member_count_caches();

    if ( empty( $member_type ) || empty( $member_types[ $member_type ] ) )
        return false;

    $counter = $wpdb->get_var("SELECT COUNT(`a`.`user_id`) FROM wp_bp_activity AS a 
                    LEFT JOIN wp_bp_xprofile_data AS p ON `a`.`user_id`=`p`.`user_id`
                    WHERE `a`.`component` = 'members' AND `a`.`type` = 'last_activity' 
                    AND `p`.`field_id`= 4 AND `p`.`value`='".$member_type."'");

    if( empty( $counter ) )
      return 0;
    return (int) $counter;
}


//show member types tabs
function mm_display_directory_tabs() {
  $member_types = bp_get_member_types( array(), 'objects' );
  // Loop in member types to build the tabs
  foreach ( $member_types as $member_type ) : ?>
    <li id="members-<?php echo esc_attr( $member_type->name );?>">
      <a href="<?php bp_members_directory_permalink(); ?>">
        <?php printf( '%s <span>%d</span>', $member_type->labels['name'], mm_count_member_types($member_type->name) ); ?>
      </a>
    </li>
  <?php endforeach;
}
add_action( 'bp_members_directory_member_types', 'mm_display_directory_tabs' );




function mm_set_has_members_type_arg( $args = array() ) {
  // Get member types to check scope
  $member_types = bp_get_member_types();
  // Set the member type arg if scope match one of the registered member type
  if ( ! empty( $args['scope'] ) && ! empty( $member_types[ $args['scope'] ] ) ) {
    $args['member_type'] = $args['scope'];
  }
  return $args;
}
add_filter( 'bp_before_has_members_parse_args', 'mm_set_has_members_type_arg', 10, 1 );



function mm_member_header_display() {
  $member_type = bp_get_member_type( bp_displayed_user_id() );
  if ( empty( $member_type ) ) {
    return;
  }
  $member_type_object = bp_get_member_type_object( $member_type );
  ?>
  <p class="member-type"><?php echo esc_html( $member_type_object->labels['singular_name'] ); ?></p>
  <?php
}
add_action( 'bp_before_member_header_meta', 'mm_member_header_display' );



//set user DISPLAYED_NAME. Use user first&last names
function update_user_profile_after_activation( $user_id, $key, $user ) {
  global $bp;
  $member_type = bp_get_member_type($user_id);

  if($member_type == 'gym')
    wp_update_user( array( 'ID' => $user_id,
                            'first_name' => bp_get_profile_field_data('field=3&user_id='.$user_id),
                            'last_name' => bp_get_profile_field_data('field=3&user_id='.$user_id),
                            'display_name' => bp_get_profile_field_data('field=3&user_id='.$user_id) ) );
  else
    wp_update_user( array( 'ID' => $user_id,
                            'first_name' => bp_get_profile_field_data('field=1&user_id='.$user_id),
                            'last_name' => bp_get_profile_field_data('field=2&user_id='.$user_id),
                            'display_name' => bp_get_profile_field_data('field=1&user_id='.$user_id).' '.bp_get_profile_field_data('field=2&user_id='.$user_id) ) );

}
add_action( 'bp_core_activated_user', 'update_user_profile_after_activation', 10, 3 );



//set user DISPLAYED_NAME. Use user first&last names. For GYM Users - GYM Name
function update_user_profile_after_edit_xprofile( $user_id ) {
  global $bp;
  $member_type = bp_get_member_type($user_id);
  
  if($member_type == 'gym')
    wp_update_user( array( 'ID' => $user_id,
                            'first_name' => $_POST['field_3'],
                            'last_name' => $_POST['field_3'],
                            'display_name' => $_POST['field_3'] ) );
  else
    wp_update_user( array( 'ID' => $user_id,
                            'first_name' => $_POST['field_1'],
                            'last_name' => $_POST['field_2'],
                            'display_name' => $_POST['field_1'].' '.$_POST['field_2'] ) );
}
add_action( 'xprofile_updated_profile', 'update_user_profile_after_edit_xprofile');



//exclude xprofile fields depends on user member type
function mm_exclude_xprofile_by_member_type( $retval ) {  
  
    $member_type = bp_get_member_type(bp_displayed_user_id());
    //if this is view profile screen
    if(!bp_is_profile_edit()) {
        switch ($member_type) {
            case 'standard':
                $retval['exclude_fields'] = '3,12';  
            break;
            case 'pt':
                $retval['exclude_fields'] = '3';
            break;
            case 'gym':
                $retval['exclude_fields'] = '1,2,5,7';
            break;
        } 
    }//or this is edit profile screen
    elseif(bp_is_profile_edit()){
        switch ($member_type) {
            case 'standard':
                $retval['exclude_fields'] = '4,3,12';  
            break;
            case 'pt':
                $retval['exclude_fields'] = '4,3';
            break;
            case 'gym':
                $retval['exclude_fields'] = '4,1,2,5,7';
            break;
        }
    } 
  return $retval;
  
}
add_filter( 'bp_after_has_profile_parse_args', 'mm_exclude_xprofile_by_member_type' );

/**************************************************************
    END MEMBER TYPES
***************************************************************/






/**************************************************************
          MEMBERS REGISTRATION
***************************************************************/
//validete username for only spaces entered
function custom_validate_username($valid, $username ) {
    if (preg_match("/\\s/", $username)) {
      //if there are spaces
      return $valid=false;
    }
  return $valid;
}
add_filter('validate_username' , 'custom_validate_username', 10, 2);


//validate member type required fields
function validate_member_type_fields(){
  global $bp;

  if(isset($_POST['field_4'])&&!empty($_POST['field_4'])){
      $member_type = $_POST['field_4'];
      switch ($member_type) {
        //if this is Standard User
        case 'standard':
            unset($bp->signup->errors['field_3']);//remove empty GYM Name error
            unset($bp->signup->errors['field_12']);//remove empty Specialization error

            //also check First Name, Last Name, Location, Phone - for empty or only spaces string
            if(ctype_space($_POST['field_1']) || $_POST['field_1'] === "" || $_POST['field_1'] === null)
              $bp->signup->errors['field_1'] = 'Please enter correct First Name';
            if(ctype_space($_POST['field_2']) || $_POST['field_2'] === "" || $_POST['field_2'] === null)
              $bp->signup->errors['field_2'] = 'Please enter correct Last Name';
            if(ctype_space($_POST['field_10']) || $_POST['field_10'] === "" || $_POST['field_10'] === null)
              $bp->signup->errors['field_10'] = 'Please enter correct Location';
            if(ctype_space($_POST['field_11']) || $_POST['field_11'] === "" || $_POST['field_11'] === null)
              $bp->signup->errors['field_11'] = 'Please enter correct Phone';
          break;
        //if this is Personal Trainer
        case 'pt':
            unset($bp->signup->errors['field_3']);//remove empty GYM Name error

            //also check First Name, Last Name, Location, Phone - for empty or only spaces string
            if(ctype_space($_POST['field_1']) || $_POST['field_1'] === "" || $_POST['field_1'] === null)
              $bp->signup->errors['field_1'] = 'Please enter correct First Name';
            if(ctype_space($_POST['field_2']) || $_POST['field_2'] === "" || $_POST['field_2'] === null)
              $bp->signup->errors['field_2'] = 'Please enter correct Last Name';
            if(ctype_space($_POST['field_10']) || $_POST['field_10'] === "" || $_POST['field_10'] === null)
              $bp->signup->errors['field_10'] = 'Please enter correct Location';
            if(ctype_space($_POST['field_11']) || $_POST['field_11'] === "" || $_POST['field_11'] === null)
              $bp->signup->errors['field_11'] = 'Please enter correct Phone';
          break;
        //if this is GYM User
        case 'gym':
            unset($bp->signup->errors['field_1']);//remove empty First Name error
            unset($bp->signup->errors['field_2']);//remove empty Last Name error
            unset($bp->signup->errors['field_5']);//remove empty Birthday error
            unset($bp->signup->errors['field_7']);//remove empty Gender Name error

            //also check GYM Name, Location, Phone - for empty or only spaces string
            if(ctype_space($_POST['field_3']) || $_POST['field_3'] === "" || $_POST['field_3'] === null)
              $bp->signup->errors['field_3'] = 'Please enter correct GYM Name';
            if(ctype_space($_POST['field_10']) || $_POST['field_10'] === "" || $_POST['field_10'] === null)
              $bp->signup->errors['field_10'] = 'Please enter correct Location';
            if(ctype_space($_POST['field_11']) || $_POST['field_11'] === "" || $_POST['field_11'] === null)
              $bp->signup->errors['field_11'] = 'Please enter correct Phone';
          break;
      }
  }else{
    unset($bp->signup->errors['field_3']);//remove empty GYM Name error
    unset($bp->signup->errors['field_12']);//remove empty Specialization error
  }
}
add_action('bp_signup_validate', 'validate_member_type_fields');


//change recaptcha size to "compact"
remove_action( 'bp_before_registration_submit_buttons', 'bp_add_code' );
function bp_add_reg_captcaha() {
  global $bp;
  ?>
      <?php if (!empty($bp->signup->errors['recaptcha_response_field'])) : ?>
        <div class="error"><?php echo $bp->signup->errors['recaptcha_response_field']; ?></div>
      <?php endif; ?>

      <div id="mm-nocaptcha"></div>
      <?php if (get_option('mmbpcapt_public') == null || get_option('mmbpcapt_public') == '') echo "reCAPTCHA API keys empty!"; ?>
        <script type="text/javascript">
          var onloadCaptchaCallback = function() {
            grecaptcha.render('mm-nocaptcha', {
              'sitekey' : '<?php echo get_option('mmbpcapt_public'); ?>',
              'theme' : '<?php echo get_option('mmbpcapt_theme'); ?>',
              'type' : '<?php echo get_option('mmbpcapt_type'); ?>',
              'size' : 'compact'
            });
          };
        </script>
  <?php
}
add_action( 'bp_before_registration_submit_buttons', 'bp_add_reg_captcaha' );


//set user member type after account activation
function mm_set_default_member_type( $user_id, $user_login="", $user_password="", $user_email="", $usermeta="" ) {
    //get User Type value - xprofile field_4
    $type = bp_get_profile_field_data('field=4&user_id='.$user_id);
    switch($type) {
        case "Standard User":
            $member = 'standard';
            break;
        case "Personal Trainer":
            $member = 'pt';
            break;
        case "GYM User":
            $member = 'gym';
            break;
    }
    bp_set_member_type( $user_id, $member );
}
add_action( 'bp_core_signup_user', 'mm_set_default_member_type' );



//set birthdate year limits to - 100 old and 8years young 
function custom_birthdate_box($html, $type, $day, $month, $year, $field_id, $date) {       
  if($type == 'year'){
      $html = '<option value=""' . selected( $year, '', false ) . '>----</option>';
      for ( $i = date('Y', strtotime("-8 year", time())); $i >  date('Y', strtotime("-100 year", time())); $i-- ) {
        $html .= '<option value="' . $i .'"' . selected( $year, $i, false ) . '>' . $i . '</option>';
      }
  }
  return $html;
}
add_filter( 'bp_get_the_profile_field_birthdate', 'custom_birthdate_box',10,7);

/**************************************************************
    END MEMBERS REGISTRATION
***************************************************************/



/**************************************************************
   MEMBERS DELETEION
***************************************************************/
function wppl_delete_bp_member($user_id){
  global $wpdb;
//echo $user_id;
  //die();
}
add_action( 'delete_user', 'wppl_delete_bp_member' ); 

/**************************************************************
    END MEMBERS DELETION
***************************************************************/








function prevent_unregistered_users(){
  //print_r($_SERVER);
  //exit;
 // echo $_SERVER['REQUEST_URI'];
//echo get_the_ID().'==>';

if($_SERVER['REQUEST_URI'] =='/sitemap.xml'  ){
	//echo home_url();
	//wp_redirect( '/sitemap.xml' );
	
}else if( (!is_user_logged_in() 
        && basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) != 'transformation_winner_cron.php' 
        && stripos($_SERVER['REQUEST_URI'],'user-progress-image') != true
      ) 
      && (!bp_is_activation_page() 
          && basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) != 'lost-password' 
          && $_SERVER['REQUEST_URI'] != '/' 
          && basename($_SERVER['REQUEST_URI']) != 'my-account' 
          && basename($_SERVER['REQUEST_URI']) !='wp-login.php'
          && basename($_SERVER['REQUEST_URI']) != 'handler.php'
        ) 
      && $GLOBALS['pagenow'] != 'wp-login.php')
  {
    wp_redirect( home_url() );
    exit();
  }

}
add_action('init','prevent_unregistered_users');



if(function_exists('bp_is_activation_page')){
  function prevent_not_filled_required_users(){
    //print_r(basename($_SERVER['REQUEST_URI']));
    //exit;

    if(is_user_logged_in()
      && (!bp_is_activation_page()
          && basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) != 'lost-password'
          //&& $_SERVER['REQUEST_URI'] != '/'
          && basename($_SERVER['REQUEST_URI']) != 'my-account'
          && basename($_SERVER['REQUEST_URI']) != 'fill-in-required-fields'
          && basename($_SERVER['REQUEST_URI']) != 'wp-login.php'
          && basename($_SERVER['REQUEST_URI']) != 'handler.php')
      && $GLOBALS['pagenow'] != 'wp-login.php'
      && count(get_empty_required_fields()) > 0
      && !current_user_can('manage_options')){
      wp_redirect('/fill-in-required-fields/');
      exit;
    }
  }
  add_action('init','prevent_not_filled_required_users');
}


function get_empty_required_fields(){
  global $bp;

  //fields: 1 - First Name, 2 - Last Name, 3 - GYM Name 4 - User Type,
  // 5 - Birthday, 7 - Gender, 10 - Location, 11 - Phone, 12 - Specialization
  $required = array( 1, 2, 3, 4, 5, 7, 10, 11, 12, 100 );
  $member_type = bp_get_member_type($bp->loggedin_user->id);

  //for unset use key(not value of field id) from $required array
  switch ($member_type) {
    case 'standard':
        unset($required[2]);//unset GYM Name
        unset($required[8]);//unset Specialization
      break;
    case 'pt':
        unset($required[2]);//unset GYM Name
      break;
    case 'gym':
        unset($required[0]);//unset First Name
        unset($required[1]);//unset Last Name
        unset($required[4]);//unset Birthdate
        unset($required[5]);//unset Gender
      break;
  }
  $empty = array();
    foreach ($required as $field) {
        if( !bp_get_profile_field_data('field='.$field.'&user_id='.$bp->loggedin_user->id) ){
          $empty[] = $field;
        }
    }
    return $empty;
}




function my_class_names( $classes ) {
  if( basename($_SERVER['REQUEST_URI']) == 'fill-in-required-fields' ){
    $classes[] = 'fill-in-required-fields';
    $classes[] = 'page-template-page-no-buddypanel';
  }
  // return the $classes array
  return $classes;
}
add_filter( 'body_class', 'my_class_names' );


function go_home(){
  global $woocommerce;
  $woocommerce->cart->empty_cart();
  wp_redirect( home_url() );
  exit;
}
add_action('wp_logout','go_home');


function after_lost_password_redirect() {
    wp_redirect( home_url() ); 
    exit;
}
add_action('password_reset', 'after_lost_password_redirect');




add_filter( 'woocommerce_add_success', function( $message ) {
    if( strpos($message,'Your password has been reset') !== false )
      $message = 'Your password has been reset. <a href="/wp-login.php">Log in</a>';
    return $message;
});








/**************************************************************
    RATINGS AND REVIWES
***************************************************************/
//fix review post delete link
function my_new_bp_get_activity_delete_link( $link ) {
    $url = bp_get_activity_delete_url(); 
    $class = 'delete-activity'; 
 
    // Determine if we're on a single activity page, and customize accordingly. 
    if ( bp_is_activity_component() && is_numeric( bp_current_action() ) ) { 
        $class = 'delete-activity-single'; 
    } 
    $link = '<a href="' . esc_url( $url ) . '" class="btn danger ' . $class . '" rel="nofollow">' . __( 'Delete', 'buddypress' ) . '</a>'; 
    return $link;
}
add_filter('bp_get_activity_delete_link', 'my_new_bp_get_activity_delete_link');




remove_action('bp_member_header_actions', 'prorevs_member_header');
function prorevs_member_header_override() {
        if ($GLOBALS['bp']->current_component == "reviews") {
            return false;
        } else {
            $options = get_option('reviews_options');
            $allow_for_values = array();
            $reviews_allowed = 0;
            if(is_array($options['allow_for'])) {
                $allow_for_values = $options['allow_for'];
            }
            $bp_current_user_id =  bp_displayed_user_id();
            $current_user_id = get_current_user_id();
            $displayed_user = get_userdata( $bp_current_user_id );
            if($displayed_user) {
                $reviews_allowed = count(array_intersect($displayed_user->roles,$allow_for_values));
            }

            if(($reviews_allowed > 0 || ($reviews_allowed == 0 && !$options['hide_reviews'])) && $bp_current_user_id != $current_user_id  ) {
            ?>
            <div class="generic-button">
                <a title="<?= __('Add reviews for this user', 'bpreviews') ?>"
                   href="<?php echo bp_get_displayed_user_link() ?>reviews/"><?= __('Add Review', 'bpreviews') ?></a>
            </div>
            <?php
            }
        }
}
add_action('bp_member_header_actions', 'prorevs_member_header_override');




function prorevs_trainers_by_rating($limit) {
    global $wpdb;
    
    $users = $wpdb->get_results(
            $wpdb->prepare(
                    "SELECT
             a.ID as id, a.user_login AS name, AVG(star) AS rating, COUNT(star) AS reviews
         FROM ".$wpdb->prefix."users AS a
         LEFT JOIN ".$wpdb->prefix."bp_activity AS b ON a.ID = b.usercheck
         LEFT JOIN ".$wpdb->prefix."bp_xprofile_data AS x ON a.ID = x.user_id
         WHERE (b.is_activated is null or b.is_activated=1) AND (x.field_id=2 AND x.value='Personal trainer/Gym')
         GROUP BY id
         ORDER BY rating DESC
         LIMIT %d", $limit
            )
    );
    return custom_prorevs_print_users($users);
}

function prorevs_trainers_by_rating_shortcode($atts) {
    extract(shortcode_atts(array('limit' => 5), $atts));
    return prorevs_trainers_by_rating($limit);
}
add_shortcode('prorevs_trainers_by_rating', 'prorevs_trainers_by_rating_shortcode');


function prorevs_trainers_by_review_count($limit) {
    global $wpdb;

    $users = $wpdb->get_results(
            $wpdb->prepare(
                    "SELECT
                 a.ID as id, a.user_login AS name, AVG(star) AS rating, COUNT(star) AS reviews
             FROM ".$wpdb->prefix."users AS a
             LEFT JOIN ".$wpdb->prefix."bp_activity AS b ON a.ID = b.usercheck
             LEFT JOIN ".$wpdb->prefix."bp_xprofile_data AS x ON a.ID = x.user_id
             WHERE (b.is_activated is null or b.is_activated=1) AND (x.field_id=2 AND x.value='Personal trainer/Gym')
             GROUP BY id
             ORDER BY reviews DESC
             LIMIT %d", $limit
            )
    );

    return custom_prorevs_print_users($users);
}


function prorevs_trainers_by_review_count_shortcode($atts) {
    extract(shortcode_atts(array('limit' => 10), $atts));
    return prorevs_trainers_by_review_count($limit);
}
add_shortcode('prorevs_trainers_by_review_count', 'prorevs_trainers_by_review_count_shortcode');


//to remove bug with ratings dispaying, when in loop displayed current loggedin user rating
remove_action('bp_directory_members_actions', 'prorevs_add_star_loop_content');
remove_action('bp_after_member_header', 'prorevs_add_star_loop_header');
function custom_prorevs_add_star_loop_content() {
  return custom_prorevs_add_star_loop(1);
}

function custom_prorevs_add_star_loop($checkitem) {
    global $wpdb;

    $current_user_id = (bp_get_member_user_id()) ? bp_get_member_user_id() : bp_displayed_user_id();
    
    if( !isset($options['hide_reviews']) ){
        
        $check_content_loop = $wpdb->get_results("SELECT AVG(star) AS average, COUNT(star) AS quantity FROM " . $wpdb->prefix . "bp_activity WHERE  type = 'Member_review' AND usercheck='" . $current_user_id . "' and is_activated=1",ARRAY_A)[0];
        if ($check_content_loop['average'] != "") {
            $check_show_star_loop = $check_content_loop['average'];
            $demss = 0;
            echo '<div class="col-md-12 rating-top-container"><span class="rating-top"> ';
            for ($dem = 1; $dem < 6; $dem++) {
                if ($dem <= $check_show_star_loop) {
                    echo '<i class="fa fa-star rating-star"></i>';
                } else {
                    $demss++;
                    echo (ceil($check_show_star_loop) - $check_show_star_loop > 0 and $demss == 1) ? '<i class="fa fa-star-half-o rating-star"></i>' : '<i class="fa fa-star-o rating-star"></i>';
                }
            }
            echo '<br>Based on '.$check_content_loop['quantity'].' reviews</span></div>';
        } else {
            echo '<div class="col-md-12 rating-top-container"><span class="rating-top">No Reviews</span></div>';
        }
    }
    // End check
}
add_action('bp_after_member_header', 'custom_prorevs_add_star_loop_content',1);
add_action('bp_directory_members_actions', 'custom_prorevs_add_star_loop_content',1);



function custom_prorevs_print_users($users) {
    $ret = '<div class="prorevs_user_list">';
    $count = 1;
    foreach ($users as $user) {
        $ret .= '
        <div class="prorevs_user_list_item">
            <div class="prorevs_user_list_number">
                ' . ($count++) . '.
            </div>
            ' . get_avatar($user->id, 50, '', "") . '
            <div class="prorevs_user_list_info">
                <div>
                    <a href="' . get_bloginfo('home') . '/members/' . $user->name . '/"
                        class="prorevs_user_list_name">' . bp_get_profile_field_data('field=1&user_id='.$user->id).' '.bp_get_profile_field_data('field=8&user_id='.$user->id) . '</a>
                </div>
                <div class="ratingtop">';
        for ($i = 1; $i < 6; ++$i) {
            if ($i <= $user->rating + 0.5) {
                $ret .= '<img alt="1 star" src="' . DEPROURL . '/images/star.png">';
            } else {
                $ret .= '<img alt="1 star" src="' . DEPROURL . '/images/star_off.png">';
            }
        }
        $ret .=
                '</div>
                <div class="prorevs_user_list_reviews">(' . $user->reviews . ' reviews)</div>
            </div>
        </div><hr>';
    }

    $ret .= '</div>';

    return $ret;
}


/**************************************************************
    END RATINGS AND REVIEWS
***************************************************************/



/**************************************************************
    VERIFIED
***************************************************************/
//disable gravatar avarars
add_filter('bp_core_fetch_avatar_no_grav', '__return_true');

remove_action('bp_profile_header_meta', 'bp_verified_text');
//remove_action( 'bp_get_member_avatar', 'bp_show_verified_badge_members' );
remove_action( 'bp_get_group_member_avatar_thumb', 'bp_show_verified_badge_members' );



remove_filter( 'bp_get_displayed_user_avatar', 'bp_show_verified_badge' );
function bp_show_verified_badge_custom($object) {
  global $bp;

  $is_verified = get_user_meta( $bp->displayed_user->id, 'bp-verified', true );
  
  if ( !empty( $is_verified ) )
    if ( $is_verified['profile'] == 'yes' ){
        $text = !empty($is_verified['text']) ? $is_verified['text'] : 'Verified Member' ;
        $object .= '<span id="bp-verified-header"><i class="fa fa-lg fa-check-circle-o varified-mark" data-trigger="hover" data-placement="right" data-content="'.$text.'"></i></span>';
    }
    
  return $object;
}
add_filter( 'bp_get_displayed_user_avatar', 'bp_show_verified_badge_custom' );



remove_filter( 'bp_get_member_avatar', 'bp_show_verified_badge_members' );
function bp_show_verified_badge_members_custom($object) {
  global $bp, $members_template;
    
  $comments = isset( $members_template->members ) ? (int) $members_template->member->id : '';
  $is_verified = get_user_meta( $comments, 'bp-verified', true );
 
  if ( !empty( $is_verified ) )
    if ( $is_verified['profile'] == 'yes' ){
        $text = !empty($is_verified['text']) ? $is_verified['text'] : 'Verified Member' ;
        $object .= '<span id="bp-verified-header"><i class="fa fa-lg fa-check-circle-o varified-mark" data-trigger="hover" data-placement="right" data-content="'.$text.'"></i></span>';
    }
    
  return $object;
}
add_filter( 'bp_get_member_avatar', 'bp_show_verified_badge_members_custom' );


remove_filter( 'bp_get_activity_avatar', 'bp_show_verified_badge_activity' );
function bp_show_verified_badge_activity_custom($object) {
  global $bp, $activities_template;
  
  $comments = isset( $activities_template->activity->current_comment ) ? $activities_template->activity->current_comment->user_id : (int) $activities_template->activity->user_id;
  
  $is_verified = get_user_meta( $comments, 'bp-verified', true );
 
  if ( !empty( $is_verified ) )
    if ( $is_verified['profile'] == 'yes' ){
      $text = !empty($is_verified['text']) ? $is_verified['text'] : 'Verified Member' ;
      $object .= '<span id="bp-verified-header"><i class="fa fa-lg fa-check-circle-o varified-mark" data-trigger="hover" data-placement="right" data-content="'.$text.'"></i></span>';
    }

  return $object;
}
add_filter( 'bp_get_activity_avatar', 'bp_show_verified_badge_activity_custom' );


/**************************************************************
    END VERIFIED
***************************************************************/


/**************************************************************
    ADMIN USERS TABLE
***************************************************************/

function custom_columns_content( $value, $column_name, $user_id ) {

    switch( $column_name ) {
        case 'spam_requests' : 
            return (get_spam_requests( $user_id )) ? get_spam_requests( $user_id ).' rejected requests ' : '0';
        break;

        case 'user_type' :
          return ucfirst(bp_get_member_type($user_id));
        break;
    }
}
add_action( 'manage_users_custom_column', 'custom_columns_content', 9998, 3 );



function my_modify_user_columns($column_headers) {
  unset($column_headers['bbp_user_role']);
  unset($column_headers['role']);
  unset($column_headers['posts']);
  $column_headers['user_type'] = 'User Type';
  $column_headers['spam_requests'] = 'Spam Requests';
  return $column_headers;
}
add_action('manage_users_columns','my_modify_user_columns',9999,1);


function add_spammer_requests_column_sortable( $columns ) {
    $columns['user_type'] = 'user_type';
    $columns['spam_requests'] = 'spam_request';
    return $columns;
}
add_filter( 'manage_users_sortable_columns', 'add_spammer_requests_column_sortable' );


function spamer_sorting_query($userquery){
  global $wpdb;
  $limit =20;
    if(isset($_GET['paged'])){
        $page = $_GET['paged'];
        $offset = ($page - 1)  * $limit;
        $start = $offset + 1;
    }else{
        $start = 1;
    }
    if($_GET['paged'] == 1){
        $start = 1;
    }
  if('spam_request'==$userquery->query_vars['orderby']) {
    $table_name = $wpdb->prefix . 'spam_requests';
    $userquery->query_from .= " LEFT JOIN ".$table_name." AS alias ON (".$wpdb->prefix."users.ID = alias.sender_id) ";//note use of alias
    $userquery->query_where .= " AND 1=1 ";//which meta are we sorting with?
    $userquery->query_orderby = " ORDER BY alias.qty ".($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");//set sort order
  }
  else if('user_type'==$userquery->query_vars['orderby']) {
    $table_name = $wpdb->prefix . 'bp_xprofile_data';
    $userquery->query_from .= " LEFT JOIN ".$table_name." AS alias ON (".$wpdb->prefix."users.ID = alias.user_id AND alias.field_id=4) ";//note use of alias
    $userquery->query_where .= " AND 1=1";//which meta are we sorting with?
    $userquery->query_orderby = " ORDER BY alias.value ".($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");//set sort order
    $query->query_limit="LIMIT $start, $limit";
  }
}
add_action('pre_user_query', 'spamer_sorting_query');

/**************************************************************
    END ADMIN USERS TABLE
***************************************************************/


function workout_sorting_query($userquery){
  global $wpdb;
  $limit =20;
    if(isset($_GET['paged'])){
        $page = $_GET['paged'];
        $offset = ($page - 1)  * $limit;
        $start = $offset + 1;
    }else{
        $start = 1;
    }
    if($_GET['paged'] == 1){
        $start = 1;
    }
  if('spam_request'==$userquery->query_vars['orderby']) {
    $table_name = $wpdb->prefix . 'spam_requests';
    $userquery->query_from .= " LEFT JOIN ".$table_name." AS alias ON (".$wpdb->prefix."users.ID = alias.sender_id) ";//note use of alias
    $userquery->query_where .= " AND 1=1 ";//which meta are we sorting with?
    $userquery->query_orderby = " ORDER BY alias.qty ".($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");//set sort order
  }
  else if('user_type'==$userquery->query_vars['orderby']) {
    $table_name = $wpdb->prefix . 'bp_xprofile_data';
    $userquery->query_from .= " LEFT JOIN ".$table_name." AS alias ON (".$wpdb->prefix."users.ID = alias.user_id AND alias.field_id=4) ";//note use of alias
    $userquery->query_where .= " AND 1=1";//which meta are we sorting with?
    $userquery->query_orderby = " ORDER BY alias.value ".($userquery->query_vars["order"] == "ASC" ? "asc " : "desc ");//set sort order
    $query->query_limit="LIMIT $start, $limit";
  }
}
add_action('pre_user_query', 'spamer_sorting_query');


function my_register_javascript() {
    if ( wp_lostpassword_url() == get_current_URL() || strpos(get_current_URL(),wp_lostpassword_url()) !== false ) {
       wp_enqueue_style( 'bosschild-login', get_stylesheet_directory_uri().'/css/login.css', false );
    }
}
//add_action( 'init', 'my_register_javascript', 100 );

function get_spam_requests($user_id){
  global $wpdb;
  $table_name = $wpdb->prefix . 'spam_requests';
  $results = $wpdb->get_var( "SELECT SUM(qty) FROM ".$table_name." WHERE sender_id = ".$user_id );
  return $results;
}

function handle_not_spamers(){
  global $wpdb;
  if(isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'ham'){
    $action  = $_REQUEST['action'];
    $mm_spamer_options = get_option("mm_spamer_options");
    $mm_spamer_threshold = intval($mm_spamer_options["mm_spamer_threshold"]);
    $mm_notspamer_notification = stripslashes_deep($mm_spamer_options["mm_notspamer_notification"]);

    $user_id = !empty( $_REQUEST['user'] ) ? intval( $_REQUEST['user'] ) : false;

    if(empty($user_id))
      return;

    $table_name = $wpdb->prefix . 'spam_requests';

    $wpdb->delete($table_name, array('sender_id'=>$user_id));
    
    $args = array( 'recipients' => $user_id, 'sender_id' => get_current_user_id(), 'subject' => 'User status changed', 'content' => $mm_notspamer_notification );
    messages_new_message( $args );
  }
}
add_action('init', 'handle_not_spamers');


/**************************************************************
    CONNECTIONS
***************************************************************/


function print_connection_status(){
  
    global $wpdb;

    $table_name = $wpdb->prefix . 'members_connections';
    //request sender id
    $sender = bp_loggedin_user_id();
    //request reciver id
    $reciver = bp_get_member_user_id();
    //request sender User Type
    $request_sender_type = bp_get_member_type($sender);
    //request reciver User Type
    $request_reciver_type = bp_get_member_type($reciver);
    //check is sender and reciver are friends
    $is_friend = friends_check_friendship($sender,$reciver);

    //check if there is already connection between sender and reciver
    $connection = $wpdb->get_row( 
                    "SELECT `mc`.*, `p`.`status` AS `parq_status`
                    FROM ".$table_name." AS mc
                    LEFT JOIN ".$wpdb->prefix."parq AS p
                    ON `mc`.`parq`=`p`.`id`
                    WHERE (mc.request_sender_id=".$sender." OR mc.request_reciver_id =".$sender.") 
                    AND (mc.request_sender_id=".$reciver." OR mc.request_reciver_id =".$reciver.")");

    //if there is pending or accepted connection between sender and reciver
    if($connection){

        if($connection->status == 'pending')
            print_pending_connection_actions($connection);

        elseif($connection->status == 'connected')
            print_connected_connection_actions($connection);

    }else{

        switch ($request_sender_type) {
            //if request sender is Standard User
            case 'standard':
                //and request reciver is Personal Trainer
                if($request_reciver_type == 'pt'){
                    //if sender and reciver are friends
                    echo ($is_friend) 
                        ? '<button data-reciver="'.$reciver.'" type="button" class="btn connection-request">Connect as my Personal Trainer</button>'
                        : '<small>Please add user to your friendship list, before connect as your Trainer.</small>';
                }
                //or request reciver is GYM User
                elseif($request_reciver_type == 'gym'){
                    //if sender and reciver are friends
                    echo ($is_friend)
                        ? '<button data-reciver="'.$reciver.'" type="button" class="btn connection-request">Connect to GYM</button>'
                        : '<small>Please add user to your friendship list, before connect as your GYM.</small>'; 
                }
            break;
            //if request sender is Personal Trainer
            case 'pt':
                //and request reciver is Standard User
                if($request_reciver_type == 'standard'){
                    //if sender and reciver are friends
                    echo ($is_friend)
                        ? '<button data-reciver="'.$reciver.'" type="button" class="btn connection-request">Connect as my Client</button>'
                        : '<small>Please add user to your friendship list, before connect as your Client.</small>';
                }
                //or request reciver is GYM User
                elseif($request_reciver_type == 'gym'){
                    //if sender and reciver are friends
                    echo ($is_friend)
                        ? '<button data-reciver="'.$reciver.'" type="button" class="btn connection-request">Connect to GYM</button>'
                        : '<small>Please add user to your friendship list, before connect as your GYM.</small>'; 
                }
            break;
            //if request sender is GYM User
            case 'gym':
                //and request reciver is Standard User
                if($request_reciver_type == 'standard'){
                    //if sender and reciver are friends
                    echo ($is_friend)
                        ? '<button data-reciver="'.$reciver.'" type="button" class="btn connection-request">Connect as GYM Member</button>'
                        : '<small>Please add user to your friendship list, before connect as your GYM member.</small>';           
                }
                //or request reciver is Personal Trainer
                elseif($request_reciver_type == 'pt'){
                    //if sender and reciver are friends
                    echo ($is_friend)
                        ? '<button data-reciver="'.$reciver.'" type="button" class="btn connection-request">Connect as GYM Trainer</button>'
                        : '<small>Please add user to your friendship list, before connect as your GYM Trainer.</small>'; 
                }
            break;
        }
    }
}



function print_pending_connection_actions($connection){

    //request sender User Type
    $request_sender_type = bp_get_member_type($connection->request_sender_id);
    //request reciver User Type
    $request_reciver_type = bp_get_member_type($connection->request_reciver_id);
    
    if($request_sender_type == 'standard' || $request_reciver_type == 'standard'){

        switch ($connection->parq_status) {
            case 'pending':

                if ($request_sender_type == 'standard' 
                            && $connection->request_sender_id == bp_loggedin_user_id() 
                            && $connection->request_reciver_id == bp_get_member_user_id()){
                        echo '<a class="parq-link" href="/parq/?parq_id='.$connection->parq.'">Please fill in the PAR-Q.</a>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn warning connection-cancel">Cancel pending Connection</button>';
                }
                elseif ($request_sender_type == 'standard' 
                            && $connection->request_reciver_id == bp_loggedin_user_id() 
                            && $connection->request_sender_id == bp_get_member_user_id()){
                        echo '<p class="parq-status">Still pending PAR-Q...</p>
                            <button data-connection="" type="button" class="btn" disabled="disabled">Accept Connection</button>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn danger connection-reject">Reject Connection</button>';
                }
                elseif ($request_reciver_type == 'standard' 
                            && $connection->request_sender_id == bp_loggedin_user_id() 
                            && $connection->request_reciver_id == bp_get_member_user_id()){
                        echo '<p class="parq-status">Still pending PAR-Q...</p>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn warning connection-cancel">Cancel pending Connection</button>';
                }
                elseif ($request_reciver_type == 'standard' 
                            && $connection->request_reciver_id == bp_loggedin_user_id() 
                            && $connection->request_sender_id == bp_get_member_user_id()){
                        echo '<a class="parq-link" href="/parq/?parq_id='.$connection->parq.'">Please fill in the PAR-Q before you confirm the request.</a>
                            <button data-connection="" type="button" class="btn" disabled="disabled">Accept Connection</button>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn danger connection-reject">Reject Connection</button>';
                }
            break;
            case 'complete':
                if ($request_sender_type == 'standard' 
                            && $connection->request_sender_id == bp_loggedin_user_id() 
                            && $connection->request_reciver_id == bp_get_member_user_id()){
                        echo '<p class="parq-status">PAR-Q filled up.</p>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn warning connection-cancel">Cancel pending Connection</button>';
                }
                elseif ($request_sender_type == 'standard' 
                            && $connection->request_reciver_id == bp_loggedin_user_id() 
                            && $connection->request_sender_id == bp_get_member_user_id()){
                        echo '<p class="parq-status"><a href="/parq/?parq_id='.$connection->parq.'">PAR-Q filled up.</a></p>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn success connection-accept">Accept Connection</button>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn danger connection-reject">Reject Connection</button>';
                }
                elseif ($request_reciver_type == 'standard' 
                            && $connection->request_sender_id == bp_loggedin_user_id() 
                            && $connection->request_reciver_id == bp_get_member_user_id()){
                        echo '<p class="parq-status">PAR-Q filled up.</p>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn warning connection-cancel">Cancel pending Connection</button>';
                }
                elseif ($request_reciver_type == 'standard' 
                            && $connection->request_reciver_id == bp_loggedin_user_id() 
                            && $connection->request_sender_id == bp_get_member_user_id()){
                        echo '<p class="parq-status">PAR-Q filled up.</p>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn success connection-accept">Accept Connection</button>
                            <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn danger connection-reject">Reject Connection</button>';
                }
            break;
        }

    }else{

        if ($connection->request_sender_id == bp_loggedin_user_id() 
            && $connection->request_reciver_id == bp_get_member_user_id()){
                echo '<button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn warning connection-cancel">Cancel pending Connection</button>';

        }
        elseif($connection->request_reciver_id == bp_loggedin_user_id() 
            && $connection->request_sender_id == bp_get_member_user_id()){
                echo '<button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn success connection-accept">Accept Connection</button>
                    <button data-connection="'.$connection->id.'" type="button" disabled="false" class="btn danger connection-reject">Reject Connection</button>';
        }
    }
}




function print_connected_connection_actions($connection){
    
    $loggedin_type = bp_get_member_type(bp_loggedin_user_id());
    $displayed_type = bp_get_member_type(bp_get_member_user_id());
    if(bp_loggedin_user_id() != bp_get_member_user_id())
      switch ($loggedin_type) {
              //if request sender is Standard User
          case 'standard':
              echo '<button data-connection="'.$connection->id.'" type="button" class="btn danger connection-disconnect">Disconnect from '.(($displayed_type == 'pt') ? 'Personal Trainer' : 'GYM').'</button>';
          break;
          case 'pt':
              echo '<button data-connection="'.$connection->id.'" type="button" class="btn danger connection-disconnect">Disconnect from '.(($displayed_type == 'standard') ? 'Client' : 'GYM').'</button>';
          break;
          case 'gym':
              echo '<button data-connection="'.$connection->id.'" type="button" class="btn danger connection-disconnect">Disconnect from '.(($displayed_type == 'standard') ? 'Member' : 'Trainer').'</button>';
          break;
      }
}


function print_members_rejected_count(){
  global $wpdb;

  //if member are friends and this is not loggenin user
  if ( bp_loggedin_user_id() != bp_get_member_user_id() 
        && bp_get_member_type(bp_loggedin_user_id()) != bp_get_member_type(bp_get_member_user_id())
        && friends_check_friendship(bp_loggedin_user_id(),bp_get_member_user_id()) ){
    
    $table_name = $wpdb->prefix.'spam_requests';

    $qty = $wpdb->get_var( "SELECT qty FROM ".$table_name." 
                          WHERE sender_id = ".bp_loggedin_user_id()." 
                          AND reciver_id=".bp_get_member_user_id() );

    echo '<p class="spam-counter">'.(($qty) ? $qty : 0).' rejected '.(($qty==1) ? 'request' : 'requests').' from this user.</p>';
  }
    
}



function pending_connection_requests($connected_user_type){
  global $wpdb;
  return array_map(function($i) {return $i[0];}, $wpdb->get_results( "SELECT `x`.`user_id` FROM {$wpdb->prefix}members_connections AS c
                                LEFT JOIN {$wpdb->prefix}bp_xprofile_data AS x ON ((`x`.`user_id`=`c`.`request_sender_id`) OR (`x`.`user_id`=`c`.`request_reciver_id`))
                                WHERE `x`.`field_id` = 4 AND `x`.`value` = '".$connected_user_type."'
                                AND `c`.`status` = 'pending' AND (`c`.`request_sender_id` = ".get_current_user_id()." OR `c`.`request_reciver_id` =".get_current_user_id().")", ARRAY_N)
          );
}



function accepted_connection_requests($connected_user_type,$cur_user_id=false){
  global $wpdb;
  if(!$cur_user_id)
    $cur_user_id = get_current_user_id();
  return array_map(function($i) {return $i[0];}, $wpdb->get_results( "SELECT `x`.`user_id` FROM {$wpdb->prefix}members_connections AS c
                                LEFT JOIN {$wpdb->prefix}bp_xprofile_data AS x ON ((`x`.`user_id`=`c`.`request_sender_id`) OR (`x`.`user_id`=`c`.`request_reciver_id`))
                                WHERE `x`.`field_id` = 4 AND `x`.`value` = '".$connected_user_type."'
                                AND `c`.`status` = 'connected' AND (`c`.`request_sender_id` = ".$cur_user_id." OR `c`.`request_reciver_id` =".$cur_user_id.")", ARRAY_N)
          );
}



function get_not_connected_friends($user_id,$connected_user_type){

  global $wpdb;
  global $bp;

  //get all rows where current user is friend for someone
  $friends_all = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$bp->friends->table_name} WHERE (initiator_user_id = %d OR friend_user_id = %d) AND is_confirmed = 1", $user_id, $user_id ) );
  $friends = array();
  foreach ($friends_all as $key => $friend) {
    $friend_id = ( $friend->initiator_user_id == $user_id ) ? $friend->friend_user_id : $friend->initiator_user_id;
    //check if friend given membertype, than add him to array
    if(bp_get_member_type( $friend_id ) == $connected_user_type)
      $friends[] = $friend_id;

  }
  //get all rows where current user connected with someone
  $connected_all = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}members_connections WHERE (request_reciver_id = %d OR request_sender_id = %d) AND (status = %s OR status = %s )", $user_id, $user_id, 'pending', 'connected' ) );
  $connected = array();
  foreach ($connected_all as $key => $user)
    $connected[] = ( $user->request_reciver_id == $user_id ) ? $user->request_sender_id : $user->request_reciver_id;

  //check for each friend - if allready connected to current user
  foreach($friends as $key=>$friend)
    if(in_array($friend,$connected))
      unset($friends[$key]);

  return $friends;
}


/**************************************************************
    END CONNECTIONS
***************************************************************/

/**************************************************************
    PARQs
***************************************************************/

//returns pending parqs for given client
function get_pending_client_parq($client_id,$connected_user_type){
  global $wpdb;
  $table_name = $wpdb->prefix . 'parq';
  $parq = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE client_id = ".$client_id." AND status = 'pending'" );
  foreach ($parq as $key => $value)
    if(bp_get_member_type($value->trainer_id) != $connected_user_type)
      unset($parq[$key]);
  return $parq;  
}

function get_parq_status($parq_id){
  global $wpdb;
  return $wpdb->get_var( "SELECT status FROM {$wpdb->prefix}parq WHERE id = ".$parq_id );
}

function get_pending_parq_from_trainer($client_id,$trainer_id){
  global $wpdb;
  return $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}parq WHERE client_id = " . $client_id . " AND trainer_id = " . $trainer_id . " AND status = 'pending'" );  
}

function get_complete_client_parq($client_id){
  global $wpdb;
  global $bp;  
  return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}parq WHERE client_id = " . $client_id . " AND trainer_id=".$bp->loggedin_user->id." AND status = 'complete'" );
}

function get_parq($parq_id){
  global $wpdb;
  return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}parq WHERE id = ".$parq_id )[0];
}

/**************************************************************
    END PARQs
***************************************************************/

function is_booked_table($booker,$bookclass){
  global $wpdb;
  $table_name = 'wp_bp_notifications'; 
$results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE user_id='".$booker."' AND secondary_item_id='".$bookclass."'");
  return $results;
}

function get_fullname($user_id){
  return (bp_get_member_type($user_id) != 'gym') ? bp_get_profile_field_data('field=1&user_id='.$user_id).' '.bp_get_profile_field_data('field=2&user_id='.$user_id) : bp_get_profile_field_data('field=3&user_id='.$user_id);
}

function user_is_connected($displayed){
  global $wpdb;
  $table_name = $wpdb->prefix . 'members_connections';
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE status = 'connected' AND ((request_sender_id = ".get_current_user_id()." AND request_reciver_id =".$displayed.") OR (request_sender_id = ".$displayed." AND request_reciver_id =".get_current_user_id()."))" );
  return $results;
}

function user_is_pending($displayed){
  global $wpdb;
  $table_name = $wpdb->prefix . 'members_connections';
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE status = 'pending' AND ((request_sender_id = ".get_current_user_id()." AND request_reciver_id =".$displayed.") OR (request_sender_id = ".$displayed." AND request_reciver_id =".get_current_user_id()."))" );
  return $results;
}



/*
function user_is_initiator($displayed){
  global $wpdb;
  $table_name = $wpdb->prefix . 'members_connections';
  $results = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE status = 'pending' AND request_reciver_id =".$displayed );
  return $results;
}


function connection_is_pending($initiator,$member){
  global $wpdb;
  $table_name = $wpdb->prefix . 'members_connections';
 
  $results = $wpdb->get_row( "SELECT * FROM ".$table_name." WHERE status = 'pending' AND request_sender_id = ".$member." AND request_reciver_id = ".$initiator );
  if(!empty($results))
    return true;
  return false;
}








function get_student_trainers($student){
  global $wpdb;
  $trainers = array();
  $table_name = $wpdb->prefix . 'members_connections';
 
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE status = 'connected' AND (request_sender_id = ".$student." OR request_reciver_id = ".$student.")" );

  if(!empty($results)){
    foreach($results as $key=>$result){
      $initiator_member_type = bp_get_member_type($result->request_reciver_id);
      $friend_member_type = bp_get_member_type($result->request_sender_id);
      if($initiator_member_type == 'pt'){
        $trainers[$key]['ID'] = $result->request_reciver_id;
        $trainers[$key]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$result->request_reciver_id);
        $trainers[$key]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$result->request_reciver_id);
      }elseif($friend_member_type == 'pt'){
        $trainers[$key]['ID'] = $result->request_sender_id;
        $trainers[$key]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$result->request_sender_id);
        $trainers[$key]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$result->request_sender_id);
      }
    }
    usort($trainers, function($a, $b) {
        return strcmp($a['firstname'], $b['firstname']);
      });
    return $trainers;
  }
  return false;
}





function is_my_trainer($student,$trainer){
  global $wpdb;
  $students = array();
  $table_name = $wpdb->prefix . 'members_connections';
 
  $students = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE status = 'connected' AND ((request_sender_id = ".$student." AND request_reciver_id = ".$trainer.") OR (request_sender_id = ".$trainer." AND request_reciver_id = ".$student."))" );
  return $students;
}



function get_trainer_students($trainer){
  global $wpdb;
  $students = array();
  $table_name = $wpdb->prefix . 'members_connections';
 
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE status = 'connected' AND (request_sender_id = ".$trainer." OR request_reciver_id = ".$trainer.")" );
  if(!empty($results)){
    foreach($results as $key=>$result){
      $initiator_member_type = bp_get_member_type($result->request_reciver_id);
      $friend_member_type = bp_get_member_type($result->request_sender_id);
      if($initiator_member_type == 'standard'){
        $students[$key]['ID'] = $result->request_reciver_id;
        $students[$key]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$result->request_reciver_id);
        $students[$key]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$result->request_reciver_id);
      }elseif($friend_member_type == 'standard'){
        $students[$key]['ID'] = $result->request_sender_id;
        $students[$key]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$result->request_sender_id);
        $students[$key]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$result->request_sender_id);
      }
    }
    return $students;
  }
  return false;
}
*/


function bp_initiator_member_last_active( $request_reciver_id, $args = array() ) {
  echo bp_get_initiator_member_last_active( $request_reciver_id, $args );
}

function bp_get_initiator_member_last_active( $request_reciver_id, $args = array() ) {
  global $members_template;

  // Parse the activity format
  $r = bp_parse_args( $args, array(
    'active_format' => true
  ) );

  // Backwards compatibility for anyone forcing a 'true' active_format
  if ( true === $r['active_format'] ) {
    $r['active_format'] = __( 'active %s', 'buddypress' );
  }
  $act = bp_get_user_last_activity($request_reciver_id);
  // Member has logged in at least one time
  if ( isset( $act )) {

    // Backwards compatibility for pre 1.5 'ago' strings
    $last_activity = ! empty( $r['active_format'] )
      ? bp_core_get_last_activity( bp_get_user_last_activity($request_reciver_id), $r['active_format'] )
      : bp_core_time_since( bp_get_user_last_activity($request_reciver_id) );

  // Member has never logged in or been active
  } else {
    $last_activity = __( 'Never active', 'buddypress' );
  }

  return apply_filters( 'bp_member_last_active', $last_activity, $r );
}


//good functions

function get_bfc_results($user_id){
  global $wpdb;
  return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "bfc_results WHERE user_id=".$user_id." ORDER BY added DESC");
}

function get_bfc_plot_script($results,$plot_id){

  $weightList = $leanmassList = $bodyfatList = '';
  
  foreach($results as $key=>$result){
      switch ($result->units) {
          case 'kg':
              $weight = $result->weight;
              $leanmass = $result->leanmass;
              break;
          case 'lbs':
              $weight = $result->weight*0.45359237;
              $leanmass = $result->leanmass*0.45359237;
              break;
          case 'oz':
              $weight = $result->weight*0.0283495231;
              $leanmass = $result->leanmass*0.0283495231;
              break;
          default:
              $weight = $result->weight;
              $leanmass = $result->leanmass;
      }

      if( !next( $results ) ) {
          $weightList .= '['.(strtotime($result->added)*1000).', '.$weight.']';
          $leanmassList .= '['.(strtotime($result->added)*1000).', '.$leanmass.']';
          $bodyfatList .= '['.(strtotime($result->added)*1000).', '.$result->bodyfat.']';
          
      }
      else{
          $weightList .= '['.(strtotime($result->added)*1000).', '.$weight.'],';
          $leanmassList .= '['.(strtotime($result->added)*1000).', '.$leanmass.'],';
          $bodyfatList .= '['.(strtotime($result->added)*1000).', '.$result->bodyfat.'],';
      }
  }

  ?>

  <script type="text/javascript">
    var weightList = [<?php echo $weightList;?>];
    var bodyfatList = [<?php echo $bodyfatList;?>];
    var leanmassList = [<?php echo $leanmassList;?>];
    jQuery(function () {   

              var plot = jQuery.plot(jQuery("#<?php echo $plot_id ?>_container"),
              [
                  {
                    data: weightList,
                    label: "Body Weight, kg",
                    points: { show: true },
                    lines: { show: true, lineWidth: 2},
                    color: '#FD6703',
                    hoverable: true
                  },
                  {
                    data: leanmassList,
                    label: "Lean Mass, kg",
                    points: { show: true },
                    lines: { show: true, lineWidth: 2},
                    color: '#e7fcff',
                    hoverable: true
                  },
                  {
                    data: bodyfatList,
                    label: "Body Fat, %",
                    points: { show: true },
                    lines: { show: true, lineWidth: 2},
                    yaxis: 2,
                    color: '#4dcade',
                    hoverable: true
                  }
              ],
              {    canvas: true,
                   series: {
                   		points: { radius: 2 }
                   },
                   grid: {
                      hoverable: true,
                      backgroundColor: null,
                      minBorderMargin: 20,
                      borderWidth: 2,
                      margin: {
                          top: 5,
                          left: 5,
                          bottom: 5,
                          right: 5,
                      },
            		   borderColor: {
                          top: null,
                          left: '#fff',
                          bottom: '#fff',
                          right: '#fff'
                      }
                  },
                  legend:{
                      show: true,
                      container: '#<?php echo $plot_id?>_legend',
                      noColumns: 3,
                      labelBoxBorderColor: 'transparent',
                      labelFormatter: function(label, series) {
            			return '<span style="color:#fff;">' + label + '</span>';
            		  }
                  },
                  xaxis: {
                      show: true,
                      color: 'rgba(255,255,255, 0.1)',
                      mode: "time",
              		  timeformat: "%b %e, %Y",
              		  font: {
                          size: 10,
						  lineHeight: 13,
						  style: "normal",
						  weight: "400",
						  family: "Ubuntu",
						  variant: "small-caps",
						  color: "#fff"
                        }
                  },
                  yaxes: [
                      {
                          axisLabel: 'Weight, kg & Lean Mass, kg',
                          axisLabelUseCanvas: true,
                          axisLabelFontSizePixels: 12,
                          axisLabelColour: '#fff',
                          color: 'rgba(255,255,255, 0.1)',
                          font: {
                            size: 11,
							lineHeight: 13,
							style: "normal",
							weight: "400",
							family: "Ubuntu",
							variant: "small-caps",
							color: "#fff"
                          },
                          zoomRange: [weightList[0],new Date().getTime()],
                    	  panRange: [weightList[0],new Date().getTime()]
                      },
                      {
                          axisLabel: 'Body Fat, %',
                          axisLabelUseCanvas: true,
                          axisLabelFontSizePixels: 12,
                          axisLabelColour: '#fff',
                          position: "right",
                          color: 'rgba(255,255,255, 0.1)',
                          font: {
                            size: 11,
							lineHeight: 13,
							style: "normal",
							weight: "400",
							family: "Ubuntu",
							variant: "small-caps",
							color: "#fff"
                          },
                          zoomRange: [weightList[0],new Date().getTime()],
                    	  panRange: [weightList[0],new Date().getTime()]
                      }
                  ],
                  zoom: {
                      interactive: true,
                      amount: 1.2
                  },
                  pan: {
                      interactive: true
                  }
              });
			  
			// add zoom out button 
			
			jQuery("#zoombtn") .click(function(){	
					plot.zoomOut();
	  });

			
	
			// and add panning buttons
	//
//			function addArrow(dir, right, top, offset) {
//				jQuery("<img class='button' src='arrow-" + dir + ".gif' style='right:" + right + "px;top:" + top + "px'>").appendTo("#<?php echo $plot_id ?>_container").click(function (e) {
//						e.preventDefault();
//						plot.pan(offset);
//					});
//			}
//	
//			addArrow("left", 55, 60, { left: -100 });
//			addArrow("right", 25, 60, { left: 100 });
//			addArrow("up", 40, 45, { top: -100 });
//			addArrow("down", 40, 75, { top: 100 });
			  
      });
	  
	  
    
    jQuery("#<?php echo $plot_id ?>_container").bind("plothover", function (event, pos, item) {
          if (item) {
            var label = item.series.label;
            var date = new Date(item.datapoint[0]);
            var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var content_date = monthNames[(date.getMonth())]+' '+date.getDate() +', '+date.getFullYear();
            jQuery("#flot-chart-tooltip").remove();
            var content = (label == 'Body Weight, kg') 
                  ? 'Saved: <strong>'+content_date+'</strong><br> Weight: <strong>'+item.datapoint[1]+' kg</strong>' 
                  : ((label == 'Lean Mass, kg') ? 'Saved: <strong>'+content_date+'</strong><br> Lean Mass: <strong>'+item.datapoint[1]+' kg</strong>' 
                  : 'Saved: <strong>'+content_date+'</strong><br> Body Fat: <strong>'+item.datapoint[1]+' %</strong>');
            jQuery('body').append('<div id="flot-chart-tooltip" style="top:'+(item.pageY + 5)+'px; left: '+(item.pageX + 5)+'px">' + content + '</div>');
          }else
            jQuery("#flot-chart-tooltip").remove();
      });
	  
	  
  </script>



<?php 
}

function get_onerepmax_results($user_id){
  global $wpdb;
  return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "onerepmax_results WHERE user_id=".$user_id." ORDER BY added DESC");
}



function get_food_diary_plans($user_id){
  global $wpdb;
  $table_name = $wpdb->prefix . 'food_diary';
 
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE user_id = ".$user_id." ORDER BY updated DESC, meal_row ASC");
  
  $food_diary_plans = array();
  foreach($results as $result){
    $food_diary_plans[$result->diary_uniq_id][$result->meal_row][] = $result;
  }
  
  return $food_diary_plans;
}



function get_supplements_diary_plans($user_id){
  global $wpdb;
  $table_name = $wpdb->prefix . 'supplements_diary';
 
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE user_id = ".$user_id." ORDER BY updated DESC, supplement_row ASC");
  $supplement_diary_plans = array();
  foreach($results as $result){
   $supplement_diary_plans[$result->diary_uniq_id][$result->supplement_row] = $result;
  }
  
  return $supplement_diary_plans;
}


//!goood functions


function get_today_calendar($current_user,$date=false){
  global $wpdb;
  $calendar_day = array();
  $calendar_day['shared'] = 0;
  $table_name = $wpdb->prefix . 'calendars_schedules';
  
  if(!$date)
    $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE calendar_date = '".current_time('Y-m-d')."' AND user_id=".$current_user.' ORDER BY time_row ASC' );
  else
    $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE calendar_date = '".$date."' AND user_id=".$current_user.' ORDER BY time_row ASC' );
  if(!empty($results)){
    foreach($results as $key=>$result){
      $calendar_day[$result->time_row] = $result;
      if($result->shared==1)
        $calendar_day['shared'] = 1;
    }
    return $calendar_day;
  }
  return false;
}



function closest_date($dates, $findate)
{
    $newDates = array();

    foreach($dates as $date)
    {
        $newDates[] = strtotime($date);
    }

    sort($newDates);
    foreach ($newDates as $a)
    {
        if ($a >= strtotime($findate)) return $a;
    }
    return end($newDates);
}



function get_next_training_session($current_user){
  
  global $wpdb;
  $table_name = $wpdb->prefix . 'calendars_schedules';
  
  $current_time = current_time('Y-m-d H:i:s');
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE calendar_date >= '".current_time('Y-m-d')."' AND user_id=".$current_user.' ORDER BY time_row ASC' );

  $datetimes = array();
  if(!empty($results)){
    foreach ($results as $key => $value) {
        $hour = 5+$value->time_row;
        $datetimes[$key] = $value->calendar_date.' '.$hour.':00:00';
    }
    $next_training_session = closest_date($datetimes, current_time('Y-m-d H:i:s'));

    return date('d/m/Y g:i a',$next_training_session);
  }
  else
    return false;
}



function get_last_updated_client(){
  	global $wpdb;
  	$clients = accepted_connection_requests('standard');
  	if($clients)
    	return $wpdb->get_var( "SELECT user_id FROM {$wpdb->prefix}bfc_results WHERE user_id IN(".implode(',',$clients).") ORDER BY added DESC" );
	return false;
}



function delete_post_children($post_id) {
  global $wpdb;

  $ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_parent = $post_id AND post_type = 'attachment'");

  foreach ( $ids as $id )
    wp_delete_attachment($id);
}
add_action('delete_post', 'delete_post_children');




function get_top5_clients_by_bfc(){
  	global $wpdb;
  	$top5 = array();
  	//get all connected clients
  	$clients = accepted_connection_requests('standard');
  	
  	if($clients){
  		//get connected clients all bfc results for last 3 months
    	$clients_results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bfc_results WHERE user_id IN(".implode(',',$clients).") AND DATE(added) >= DATE(curdate() - INTERVAL 3 MONTH - INTERVAL 7 DAY)" );
  		
  		$clients_with_results = array();
  		foreach ($clients_results as $key => $value)
  			$clients_with_results[$value->user_id][] = $value;

  		foreach ($clients_with_results as $user_id => $result)
  			//if user as more than one saved result
  			if( sizeof($clients_with_results[$user_id]) > 1 ){
  				//if there is only 2 saved results
  				if(sizeof($clients_with_results[$user_id]) == 2 ){
          			$first = reset($clients_with_results[$user_id]);
          			$last = end($clients_with_results[$user_id]);
          		//if there is more than 2 results
        		}else{

			        $last = end($clients_with_results[$user_id]);
        			
        			//grab all results dates
          			$dates = array();
          			foreach($result as $key=>$value)
            			$dates[] = $value->added;
            		
            		//graab intervals in days beetween saved results dates and day in past(3 month)
            		$day_intervals = array();
					foreach($dates as $day){
						$dateStart = new DateTime(date('Y-m-d',strtotime('-3 months')));
						$dateEnd  = new DateTime(date('Y-m-d',strtotime($day)));
						$dateDiff = $dateStart->diff($dateEnd);
						$day_intervals[] = $dateDiff->days;
					}
					//sort intervals by value
					asort($day_intervals);
					//remove duplicated values
					$equal_day_intervals = array_unique( array_diff_assoc( $day_intervals, array_unique( $day_intervals ) ) );
					$key = ($equal_day_intervals) ? key($equal_day_intervals) : key($day_intervals);

					$first = $clients_with_results[$user_id][$key];
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

		        $top5[$user_id]['user_id'] = $user_id;
		        $top5[$user_id]['first_bodyfat'] = $first->bodyfat;
		        $top5[$user_id]['last_weight'] = $last_weight;
		        $top5[$user_id]['last_units'] = $last->units;
		        $top5[$user_id]['last_bodyfat'] = $last->bodyfat;
		        $top5[$user_id]['last_leanmass'] = round($last_weight-$last_fatmass,1);
		        $top5[$user_id]['last_fatmass'] = round(($last->bodyfat*$last_weight)/100,1);
		        $top5[$user_id]['last_category'] = $last->category;
		        $top5[$user_id]['result'] = $first->bodyfat - $last->bodyfat;
		        $top5[$user_id]['added'] = $last->added;

  			}elseif( sizeof($clients_with_results[$user_id]) == 1 ){
  				$last = end($clients_with_results[$user_id]);
        		
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

				$top5[$user_id]['user_id'] = $user_id;
				$top5[$user_id]['first_bodyfat'] = $last->bodyfat;
				$top5[$user_id]['last_weight'] = $last_weight;
				$top5[$user_id]['last_units'] = $last->units;
				$top5[$user_id]['last_bodyfat'] = $last->bodyfat;
				$top5[$user_id]['last_leanmass'] = $last_leanmass;
				$top5[$user_id]['last_fatmass'] = $last_fatmass;
				$top5[$user_id]['last_category'] = $last->category.'(only one result).';
				$top5[$user_id]['result'] = '-';
  			}

  			usort($top5, function($a, $b) {
          		if($a['result']==$b['result'])
          			return 0;
          		return $a['result'] < $b['result'] ? 1 : -1;
      		});
      		
      		return array_slice($top5, 0, 5, true);
      		
  	}
	return false;
}


/*08.06.2016!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
function get_my_clients($limit = false){
  global $wpdb;
  global $bp;
  $students = array();
  $students_list = '';
  $table_name = $wpdb->prefix . 'members_connections';
  
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE status = 'accepted' AND (request_sender_id = ".$bp->loggedin_user->id." OR request_reciver_id = ".$bp->loggedin_user->id.")" );
  

  if(!empty($results)){
    foreach($results as $key=>$result){
      $initiator_member_type = bp_get_member_type($result->request_reciver_id);
      $friend_member_type = bp_get_member_type($result->request_sender_id);
      if($initiator_member_type == 'standard'){
        $students_list .= (!next( $results )) ? $result->request_reciver_id : $result->request_reciver_id.', ';
      }elseif($friend_member_type == 'standard'){
          $students_list .= (!next( $results )) ? $result->request_sender_id : $result->request_sender_id.', ';
      }
    }

    $users_measures = array();

    $mesur_table = $wpdb->prefix . 'bfc_results';                                               
    $measures = $wpdb->get_results( "SELECT * FROM ".$mesur_table." WHERE user_id IN(".$students_list.") AND DATE(added) >= DATE(curdate() - INTERVAL 3 MONTH - INTERVAL 7 DAY) ORDER BY added" );
    foreach ($measures as $key => $measure) {
      $users_measures[$measure->user_id][] = $measure;
    }

    $students_all = explode(',',$students_list);

    foreach($users_measures as $uid=>$result){
      //get keys of students with measurments
      $students_arr_key = array_search ($uid, $students_all);
      //remove users with measurments from all students array
      if ($students_arr_key || $students_arr_key === (integer)0){
        unset($students_all[$students_arr_key]);
      }

    if (sizeof($users_measures[$uid])>1){
        if(sizeof($users_measures[$uid])==2){
          $first = reset($users_measures[$uid]);
          $last = end($users_measures[$uid]);
        }else{
          $last = end($users_measures[$uid]);
        
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

          $first = $users_measures[$uid][$key];
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


        $clients_results[$uid]['user_id'] = $uid;
        $clients_results[$uid]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$uid);
        $clients_results[$uid]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$uid);
        $clients_results[$uid]['first_bodyfat'] = $first->bodyfat;
        $clients_results[$uid]['last_weight'] = $last_weight;
        $clients_results[$uid]['last_bodyfat'] = $last->bodyfat;
        $clients_results[$uid]['last_leanmass'] = $last_leanmass;
        $clients_results[$uid]['last_fatmass'] = $last_fatmass;
        $clients_results[$uid]['last_category'] = $last->category;
        $clients_results[$uid]['result'] = $first->bodyfat - $last->bodyfat;
        $clients_results[$uid]['added'] = $last->added;

      }elseif(sizeof($users_measures[$uid])==1){
        
        $last = end($users_measures[$uid]);
        
        $last_skinfolds = $last->chest+$last->axilla+$last->triceps+$last->subscapular+$last->abdominal+$last->suprailiac+$last->thigh;
        $last_bodydensity = ( $last->gender == 'Male' ) ? 1.112-(0.00043499*$last_skinfolds)+(0.00000055*$last_skinfolds*$last_skinfolds)-(0.00028826*$last->age) : 1.097-(0.00046971*$last_skinfolds)+(0.00000056*$last_skinfolds*$last_skinfolds)-(0.00012828*$last->age);
        
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

        $clients_results[$uid]['user_id'] = $uid;
        $clients_results[$uid]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$uid);
        $clients_results[$uid]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$uid);
        $clients_results[$uid]['first_bodyfat'] = $last->bodyfat;
        $clients_results[$uid]['last_weight'] = $last_weight;
        $clients_results[$uid]['last_bodyfat'] = $last->bodyfat;
        $clients_results[$uid]['last_leanmass'] = $last_leanmass;
        $clients_results[$uid]['last_fatmass'] = $last_fatmass;
        $clients_results[$uid]['last_category'] = $last->category.'(only one result).';
        $clients_results[$uid]['result'] = '-';
      }

    }

    foreach($students_all as $key=>$uid){
      //for rest of users(that has no saved measurments)
      $clients_results_empty[$uid]['user_id'] = trim($uid);
      $clients_results_empty[$uid]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.trim($uid));
      $clients_results_empty[$uid]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.trim($uid));
      $clients_results_empty[$uid]['first_bodyfat'] = '-';
      $clients_results_empty[$uid]['last_weight'] = '-';
      $clients_results_empty[$uid]['last_bodyfat'] = '-';
      $clients_results_empty[$uid]['last_leanmass'] = '-';
      $clients_results_empty[$uid]['last_fatmass'] = '-';
      $clients_results_empty[$uid]['last_category'] = '-';
      $clients_results_empty[$uid]['result'] = '';
    }

    if(!empty($clients_results)){
      //sort client with measures by max bodyfat loss
      usort($clients_results, function($a, $b) {
          if($a['result']==$b['result']) return 0;
          return $a['result'] < $b['result'] ? 1 : -1;
      });
      //sort clients without measures by firstname
      if(!empty($clients_results_empty)){
        usort($clients_results_empty, function($a, $b) {
          return strcmp($a['firstname'], $b['firstname']);
        });
        $clients_results = array_merge($clients_results,$clients_results_empty);
      }

      if($limit){
        $clients_results = array_slice($clients_results, 0, 5, true);
      }
      return $clients_results;
    }
    else
      return $clients_results = array();
  }
  return false;
}


*/

   


   //need functions!!!

function get_transformation_winner(){
  $mm_transformation_winner = get_option("mm_transformation_winner");
  $result['user_id'] = $mm_transformation_winner['mm_winner_user_id'];
  $result['firstname'] = $mm_transformation_winner['mm_winner_firstname'];
  $result['lastname'] = $mm_transformation_winner['mm_winner_lastname'];
  $result['first_bodyfat'] = $mm_transformation_winner['mm_winner_first_bodyfat'];
  $result['last_weight'] = $mm_transformation_winner['mm_winner_last_weight'];
  $result['last_bodyfat'] = $mm_transformation_winner['mm_winner_last_bodyfat'];
  $result['last_leanmass'] = $mm_transformation_winner['mm_winner_last_leanmass'];
  $result['last_fatmass'] = $mm_transformation_winner['mm_winner_last_fatmass'];
  $result['last_category'] = $mm_transformation_winner['mm_winner_last_category'];
  $result['result'] = $mm_transformation_winner['mm_winner_result'];
  return $result;
}



function get_client_last_bfc_result($client_id){
  global $wpdb;
  return $wpdb->get_row( "SELECT id FROM {$wpdb->prefix}bfc_results WHERE user_id = ".$client_id." ORDER BY id DESC" );
}


function get_client_last_shared_bfc_result($client_id){
  global $wpdb;

  $table_name = $wpdb->prefix . 'sharing_requests';
  $results = $wpdb->get_row( "SELECT * FROM ".$table_name." AS sr
                            LEFT JOIN ".$wpdb->prefix."bfc_results AS sm ON sm.id = sr.result_id
                            WHERE sr.trainer_id = ".get_current_user_id()." AND sr.client_id = ".$client_id." ORDER BY sr.id DESC" );
  if($results){

    $fullname = get_fullname($results->client_id); 
  
    switch ($results->units) {
            case 'kg':
                $weight = number_format((float)$results->weight, 2, '.', '');
                break;
            case 'lbs':
                $weight =  number_format((float)$results->weight*0.45359237, 2, '.', '');
                break;
            case 'oz':
                $weight =  number_format((float)$results->weight*0.0283495231, 2, '.', '');
                break;
            default:
                $weight = number_format((float)$results->weight, 2, '.', '');
          }
    $fatmass = round(($results->bodyfat*$weight)/100,1);
    $leanmass = round($weight-$fatmass,1);

    $results->share_text = 'My client '.$fullname.' results are: Bodyfat-'.$results->bodyfat.'%,Fatmass-'.$fatmass.'kg,Leanmass-'.$leanmass.'kg,Category-'.$results->category.'.';

  }

  return $results;
}


function get_client_pending_sharing_requests($client_id){
  global $wpdb;
  return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}sharing_requests WHERE client_id = ".$client_id." AND status = 'pending'" );
}
//!!need functions end




//set content type to allow html tags in emails
function set_content_type( $content_type ) {
    return 'text/html';
}
add_filter( 'wp_mail_content_type', 'set_content_type' );


























function get_never_active_users(){
  
  $args = array(
  'meta_query'=> array( 
            array( 
                 'key'=> 'last_activity', 
                 'compare' => 'NOT EXISTS' 
            ) 
        )
 );

  $users = get_users( $args );
  $excluded = array_map(function($user) { return $user->data->ID; }, $users);

  return implode(',',$excluded);
}


// Remove admin from the member directory
function bpdev_exclude_users($qs=false,$object=false){

    $excluded_admin='1'; // Id's to remove, separated by comma
  
    if($object != 'members' && $object != 'friends')// hide admin to members & friends 
        return $qs;
  
    $args=wp_parse_args($qs);

    if(!empty($args['user_id']))
        return $qs;

    if($args['type']=='alphabetical')
      $excluded_users = get_never_active_users();
  
    if(!empty($args['exclude']))
        $args['exclude'] = $args['exclude'].','.$excluded_admin.''.$excluded_users;
    else
        $args['exclude'] = $excluded_admin.','.$excluded_users;
    
    if( $args['type']=='undefined' && $args['action'] == 'undefined' )
      $args['type'] = $args['action'] = 'active';

    $qs = build_query($args);

    return $qs;
}
add_action('bp_ajax_querystring','bpdev_exclude_users',20,2);



// once admin is removed, we must recount the members !
function bpfr_hide_get_total_filter($count){
    return $count-1;
}
add_filter('bp_get_total_member_count','bpfr_hide_get_total_filter');


// hide admin's activities from all activity feeds
function bpfr_hide_admin_activity( $a, $activities ) {  
  
  if ( is_site_admin() )  
    return $activities; 
  
  foreach ( $activities->activities as $key => $activity ) {  
    // ID's to exclude, separated by commas. ID 1 is always the superadmin
    if ( $activity->user_id == 1  ) {     
      
      unset( $activities->activities[$key] );     
      
      $activities->activity_count = $activities->activity_count-1;      
      $activities->total_activity_count = $activities->total_activity_count-1;      
          $activities->pag_num = $activities->pag_num -1;       
    }   
  }   
  // Renumber the array keys to account for missing items   
  $activities_new = array_values( $activities->activities );    
  $activities->activities = $activities_new;  
  
  return $activities;
  
}
add_action( 'bp_has_activities', 'bpfr_hide_admin_activity', 10, 2 );




function add_counter_to_friendship_subnavs() {
  global $bp;

  $counter_f = '<span class="count">'.friends_get_total_friend_count().'</span>';
  $counter_r = '<span class="count">'.bp_friend_get_total_requests_count().'</span>';
  $bp->bp_options_nav['friends']['my-friends']['name'] = 'Friendships '.$counter_f;
  $bp->bp_options_nav['friends']['requests']['name'] = 'Requests '.$counter_r;
  
}
add_action( 'bp_setup_nav', 'add_counter_to_friendship_subnavs', 15 );





function manage_profile_nav_tabs() {
    
    global $bp;
    
   $options = get_option('reviews_options');

    if($reviews_allowed > 0 || ($reviews_allowed == 0 && !isset($options['hide_reviews']))) {
        function prorevs_reviews_tab() {

            function prorevs_reviews_tab_title() {
                //echo 'Reviews';
            }

            function prorevs_reviews_tab_content() {
                require(PROREVS_ROOT . '/css/customstylemembertwo.php');
                require(get_stylesheet_directory() . '/templates/postreviewform.php');
            }

            add_action('bp_template_title', 'prorevs_reviews_tab_title');
            add_action('bp_template_content', 'prorevs_reviews_tab_content');
            bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
        }

        bp_core_new_nav_item(array(
            'name' => 'Reviews',
            'slug' => 'reviews',
            'screen_function' => 'prorevs_reviews_tab',
            'position' => 70,
            'default_subnav_slug' => 'reviews',
            'item_css_id' => 'prorevs-reviews-tab'
        ));
    }
             
    
}
remove_action('bp_setup_nav', 'prorevs_profile_nav');//remove reviws plugin tab & add this tab in next action
add_action( 'bp_setup_nav', 'manage_profile_nav_tabs', 10 );







/**************************************************************
    NOTIFICATIONS
***************************************************************/

//register new notifications component for handling connections notifications
function custom_filter_notifications_get_registered_components( $component_names = array() ) {
    // Force $component_names to be an array
    if ( ! is_array( $component_names ) ) {
        $component_names = array();
    }
    // Add 'connections' component to registered components array
    array_push( $component_names, 'connections' );
    // Return component's with 'connections' appended
    return $component_names;
}
add_filter( 'bp_notifications_get_registered_components', 'custom_filter_notifications_get_registered_components' );



function get_connection($id){
  global $wpdb;
  return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}members_connections WHERE id = %d",$id));
}

function get_notification_connections_link($sender_type,$reciver_type){
  switch ($reciver_type) {
    case 'standard':
        return home_url().( ($sender_type == 'pt') ? '/my-trainers/' : '/my-gym/' );
    break;
    case 'pt':
        return home_url().( ($sender_type == 'standard') ? '/trainer-clients/' : '/my-gym/' );
    break;
    case 'gym':
        return home_url().( ($sender_type == 'standard') ? '/gym-members/' : '/gym-trainers/' );
    break;
  }
}


remove_filter( 'bp_notifications_get_notifications_for_user', 'bbp_format_buddypress_notifications', 10, 5 );
function custom_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {

    /*bbPress BUG OVERRIDING*/
    // New reply notifications
	if ( 'bbp_new_reply' === $action ) {
      $topic_id    = bbp_get_reply_topic_id( $item_id );
      $topic_title = bbp_get_topic_title( $topic_id );
      $topic_link  = wp_nonce_url( add_query_arg( array( 'action' => 'bbp_mark_read', 'topic_id' => $topic_id ), bbp_get_reply_url( $item_id ) ), 'bbp_mark_topic_' . $topic_id );
      $title_attr  = __( 'Topic Replies', 'bbpress' );

      if ( (int) $total_items > 1 ) {
        $text   = sprintf( __( 'You have %d new replies', 'bbpress' ), (int) $total_items );
        $filter = 'bbp_multiple_new_subscription_notification';
      } else {
        if ( !empty( $secondary_item_id ) ) {
          $text = sprintf( __( 'You have %d new reply to %2$s from %3$s', 'bbpress' ), (int) $total_items, $topic_title, bp_core_get_user_displayname( $secondary_item_id ) );
        } else {
          $text = sprintf( __( 'You have %d new reply to %s', 'bbpress' ), (int) $total_items, $topic_title );
        }
        $filter = 'bbp_single_new_subscription_notification';
      }

      // WordPress Toolbar
      if ( 'string' === $format ) {
        $return = apply_filters( $filter, '<a href="' . esc_url( $topic_link ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $text ) . '</a>', (int) $total_items, $text, $topic_link );

      // Deprecated BuddyBar
      } else {
        $return = apply_filters( $filter, array(
          'text' => $text,
          'link' => $topic_link
        ), $topic_link, (int) $total_items, $text, $topic_title );
      }

      //do_action( 'bbp_format_buddypress_notifications', $action, $item_id, $secondary_item_id, $total_items );
	  
      return $return;
    }


    
    //if this is notification from 'connections' object
    if($action=='connections_request' || $action=='connections_accept' || $action=='connections_cancel'
        || $action=='connections_reject' || $action=='connections_disconnect'
          || $action=='connections_parq_complete' || $action=='connections_resend_parq'){

      $mm_connection_options = get_option("mm_connection_options");

      //get notification
      $n = BP_Notifications_Notification::get( array(
        'user_id'           => get_current_user_id(),
        'item_id'           => $item_id,
        'secondary_item_id' => $secondary_item_id,
        'component_name'    => 'connections',
        'component_action'  => $action,
        'is_new'            => 1
      ) )[0];

      switch ($action) {
        case 'connections_request':
          $sender_type = bp_get_member_type($secondary_item_id);
          $reciver_type = bp_get_member_type($item_id);
          $request_text = ($reciver_type == 'standard') ? $mm_connection_options["connection_request_add_client"] : (($reciver_type == 'pt') ? $mm_connection_options["connection_request_add_trainer"] : $mm_connection_options["connection_request_add_gym"]);
          
          $custom_title = 'Connection request to add as '.( ($reciver_type=='standard') ? 'Client' : (($reciver_type=='pt') ? 'Trainer' : 'GYM') );
          $custom_text = $request_text.' '.get_fullname($secondary_item_id);
          $custom_link  = wp_nonce_url( get_notification_connections_link($sender_type,$reciver_type).'?action=read&nid='.$n->id.'#requested', $item_id );
          break;
        
        case 'connections_accept':
          $sender_type = bp_get_member_type($secondary_item_id);
          $reciver_type = bp_get_member_type($item_id);
          
          $custom_title = 'Connection request accepted by '.get_fullname($secondary_item_id);
          $custom_text = get_fullname($secondary_item_id).' '.$mm_connection_options["connection_request_accept"];
          $custom_link  = wp_nonce_url( get_notification_connections_link($sender_type,$reciver_type).'?action=read&nid='.$n->id.'#connected', $item_id );
          break;

        case 'connections_cancel':
          $custom_title = 'Connection request canceled by '.get_fullname($secondary_item_id);
          $custom_text =  get_fullname($secondary_item_id).' '.$mm_connection_options["connection_request_cancel"];
          $sender_type = bp_get_member_type($secondary_item_id);
          $reciver_type = bp_get_member_type($item_id);
          $custom_link  = wp_nonce_url( get_notification_connections_link($sender_type,$reciver_type).'?action=read&nid='.$n->id.'#requested', $item_id );
          break;

        case 'connections_reject':
          $custom_title = 'Connection rejected by '.get_fullname($secondary_item_id);
          $custom_text =  get_fullname($secondary_item_id).' '.$mm_connection_options["connection_request_reject"];
          $sender_type = bp_get_member_type($secondary_item_id);
          $reciver_type = bp_get_member_type($item_id);
          $custom_link  = wp_nonce_url( get_notification_connections_link($sender_type,$reciver_type).'?action=read&nid='.$n->id.'#connected', $item_id );
          break;

        case 'connections_disconnect':
          $custom_title = 'Connection refused by '.get_fullname($secondary_item_id);
          $custom_text =  get_fullname($secondary_item_id).' '.$mm_connection_options["connection_request_refuse"];
          $sender_type = bp_get_member_type($secondary_item_id);
          $reciver_type = bp_get_member_type($item_id);
          $custom_link  = wp_nonce_url( get_notification_connections_link($sender_type,$reciver_type).'?action=read&nid='.$n->id.'#connected', $item_id );
          break;

        case 'connections_resend_parq':
          $custom_title = 'New PAR-Q request from'.get_fullname($secondary_item_id);
          $custom_text =  'You have new PAR-Q request from '.get_fullname($secondary_item_id);
          $custom_link  = wp_nonce_url( home_url().'/parq/?action=read&nid='.$n->id.'&parq_id='.$item_id, $n->user_id );
          break;

        case 'connections_parq_complete':
          $custom_title = 'PAR-Q saved by'.get_fullname($secondary_item_id);
          $custom_text =  get_fullname($secondary_item_id).' saved PAR-Q.';
          $custom_link  = wp_nonce_url( home_url().'/parq/?action=read&nid='.$n->id.'&parq_id='.$item_id, $n->user_id );
          break;
      }


      // WordPress Toolbar
      if ( 'string' === $format ) {
          $return = apply_filters( 'custom_filter', '<a href="' . $custom_link. '" title="' . $custom_title. '">' . $custom_text. '</a>', $custom_text, $custom_link );

      // Deprecated BuddyBar
      } else {
          $return = apply_filters( 'custom_filter', array(
              'text' => $custom_text,
              'link' => $custom_link
          ), $custom_link, (int) $total_items, $custom_text, $custom_title );
      }
        
        return $return;


    }
	
	if('book_gym_timetable_'.$secondary_item_id === $action ) {
		global $wpdb;
		$trainner = get_userdata($item_id);
		$currentuser = get_userdata(get_current_user_id()); 
		
		$timetables = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}timetables WHERE id=".$secondary_item_id);
		
		$custom_title = "You book new Gym time table of ".$trainner->data->user_login." at class ".$timetables[0]->classname;
		$custom_link  = wp_nonce_url( home_url().'/members/'.$currentuser->data->user_nicename.'/notifications/');
		$custom_text = "You Book New Gym time table of ".$trainner->data->user_login." at class ".$timetables[0]->classname;
		
		// WordPress Toolbar
		if ( 'string' == $format ) {
			$return = '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>';
		// Deprecated BuddyBar
		} else {
			$return = '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>';
		} 
		return $return;
	}
	
	if('owner_gym_timetable_'.$secondary_item_id === $action ) {
		global $wpdb;
		$trainner = get_userdata($item_id);
		$currentuser = get_userdata(get_current_user_id()); 
		
		$timetables = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}timetables WHERE id=".$secondary_item_id);
		
		$custom_title = $trainner->data->user_login." book your Gym time table in class ".$timetables[0]->classname;
		$custom_link  = wp_nonce_url( home_url().'/members/'.$currentuser->data->user_nicename.'/notifications/');
		$custom_text = $trainner->data->user_login." book your Gym time table in class ".$timetables[0]->classname;
		
		// WordPress Toolbar
		if ( 'string' == $format ) {
			$return = '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>';
		// Deprecated BuddyBar
		} else {
			$return = '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_title ) . '">' . esc_html( $custom_text ) . '</a>';
		} 
		return $return;
	}

}
add_filter( 'bp_notifications_get_notifications_for_user', 'custom_format_buddypress_notifications', 10, 5 );

add_action( 'after_setup_theme', 'remove_buddyboss_customizer_css') ;
function remove_buddyboss_customizer_css() {
remove_action('wp_head', 'buddyboss_customizer_css');
}

function bp_new_link_class_get_add_friend_button( $button ) {
   // add new css classes to the button   
  //$button['link_class'] .= '';

  switch($button['id']){
    case 'not_friends' :
      $button['link_class'] = 'btn default';
      break;
    case 'pending' :
      $button['link_class'] = 'btn warning';
      break;

    case 'is_friend' :
      $button['link_class'] = 'btn danger';
      $button['link_text'] = 'Disconnect Friendship';
      break;

    default :
      $button['link_class'] = 'btn';
      break;
    default: 

      break;
  }
  return $button;
}
add_filter( 'bp_get_add_friend_button', 'bp_new_link_class_get_add_friend_button' , 1);



function get_search_members_ids($usertype ='', $firstname='', $lastname='', $gymname='', $location='', $specialization=''){
  global $wpdb;

  switch ($usertype) {
    case 'standard':
        $members = $wpdb->get_results("SELECT `w_firstname`.`user_id` 
          FROM `wp_bp_xprofile_data` as `w_firstname`
          LEFT JOIN `wp_bp_xprofile_data` as `w_membertype`
            ON `w_membertype`.`user_id`=`w_firstname`.`user_id` AND `w_membertype`.`field_id` = 4
          LEFT JOIN `wp_bp_xprofile_data` as `w_lastname`
            ON `w_lastname`.`user_id`=`w_membertype`.`user_id` AND `w_lastname`.`field_id` = 2 
          LEFT JOIN `wp_bp_xprofile_data` as `w_location`
            ON `w_firstname`.`user_id`=`w_location`.`user_id` AND `w_location`.`field_id` = 10 
          WHERE
            `w_firstname`.`field_id` = 1
            AND `w_membertype`.`value` = 'standard' 
            AND `w_firstname`.`value` LIKE '%".$firstname."%'
            AND `w_lastname`.`value` LIKE '%".$lastname."%'
            AND `w_location`.`value` LIKE '%".$location."%'", ARRAY_A);
      break;
    case 'pt':
      $members = $wpdb->get_results("SELECT `w_firstname`.`user_id` 
        FROM `wp_bp_xprofile_data` as `w_firstname`
        LEFT JOIN `wp_bp_xprofile_data` as `w_membertype`
            ON `w_membertype`.`user_id`=`w_firstname`.`user_id` AND `w_membertype`.`field_id` = 4
        LEFT JOIN `wp_bp_xprofile_data` as `w_lastname`
          ON `w_lastname`.`user_id`=`w_membertype`.`user_id` AND `w_lastname`.`field_id` = 2 
        LEFT JOIN `wp_bp_xprofile_data` as `w_location` 
          ON `w_firstname`.`user_id`=`w_location`.`user_id` AND `w_location`.`field_id` = 10
        LEFT JOIN `wp_bp_xprofile_data` as `w_specialization` 
          ON `w_firstname`.`user_id`=`w_specialization`.`user_id` AND `w_specialization`.`field_id` = 12
        WHERE 
          `w_firstname`.`field_id` = 1
          AND `w_membertype`.`value` = 'pt' 
          AND `w_firstname`.`value` LIKE '%".$firstname."%'
          AND `w_lastname`.`value` LIKE '%".$lastname."%'
          AND `w_location`.`value` LIKE '%".$location."%' 
          AND `w_specialization`.`value` LIKE '%".$specialization."%'", ARRAY_A);
      break;
    case 'gym':
        $members = $wpdb->get_results("SELECT `w_gymname`.`user_id` 
          FROM `wp_bp_xprofile_data` as `w_gymname`
          LEFT JOIN `wp_bp_xprofile_data` as `w_membertype`
            ON `w_membertype`.`user_id`=`w_gymname`.`user_id` AND `w_membertype`.`field_id` = 4
          LEFT JOIN `wp_bp_xprofile_data` as `w_location` 
            ON `w_location`.`user_id`=`w_membertype`.`user_id` AND `w_location`.`field_id` = 10
          LEFT JOIN `wp_bp_xprofile_data` as `w_specialization` 
            ON `w_gymname`.`user_id`=`w_specialization`.`user_id` AND `w_specialization`.`field_id` = 12
          WHERE 
            `w_gymname`.`field_id` = 3
            AND `w_membertype`.`value` = 'gym' 
            AND `w_gymname`.`value` LIKE '%".$gymname."%'
            AND `w_location`.`value` LIKE '%".$location."%' 
            AND `w_specialization`.`value` LIKE '%".$specialization."%'", ARRAY_A);
          break;
	  case 'all':	
        $members = $wpdb->get_results("SELECT `w_firstname`.`user_id` 
        FROM `wp_bp_xprofile_data` as `w_firstname`
        LEFT JOIN `wp_bp_xprofile_data` as `w_membertype`
            ON `w_membertype`.`user_id`=`w_firstname`.`user_id` AND `w_membertype`.`field_id` = 4
        LEFT JOIN `wp_bp_xprofile_data` as `w_lastname`
          ON `w_lastname`.`user_id`=`w_membertype`.`user_id` AND `w_lastname`.`field_id` = 2 
	    LEFT JOIN `wp_bp_xprofile_data` as `w_gymname`
            ON `w_gymname`.`user_id`=`w_membertype`.`user_id` AND `w_membertype`.`field_id` = 4 
        LEFT JOIN `wp_bp_xprofile_data` as `w_location` 
          ON `w_firstname`.`user_id`=`w_location`.`user_id` AND `w_location`.`field_id` = 10
        LEFT JOIN `wp_bp_xprofile_data` as `w_specialization` 
          ON `w_firstname`.`user_id`=`w_specialization`.`user_id` AND `w_specialization`.`field_id` = 12
        WHERE 
          `w_firstname`.`field_id` = 1
		  AND `w_gymname`.`value` LIKE '%".$gymname."%'
          AND `w_firstname`.`value` LIKE '%".$firstname."%'
          AND `w_lastname`.`value` LIKE '%".$lastname."%'
          AND `w_location`.`value` LIKE '%".$location."%' 
          AND `w_specialization`.`value` LIKE '%".$specialization."%'", ARRAY_A);
          break;
  }

  $search_members_ids = array_map(function($member) { return $member['user_id']; }, $members);
  return $search_members_ids;
}



         
function my_bp_loop_querystring( $query_string,  $object,  $object_filter,  $object_scope,  $object_page,  $object_search_terms,  $object_extras ) {
    
    if($object == 'members'){

      if(!empty($_GET) && isset($_GET['search_members'])){//if this is search request throw search form

        $usertype =  urldecode($_GET['usertype']);
        $firstname = urldecode($_GET['firstname']);
        $lastname = urldecode($_GET['lastname']);
        $gymname = urldecode($_GET['gymname']);
        $location = urldecode($_GET['location']);
        $specialization = urldecode($_GET['specialization']);
        $ids = get_search_members_ids($usertype, $firstname, $lastname, $gymname, $location, $specialization);
        $str = $query_string.$ids;
        return $str;
      }else{
        if (defined('DOING_AJAX') && DOING_AJAX) {

          $search_uri = parse_url( $_SERVER['HTTP_REFERER'] );

          $search_uri_vars = explode("&", $search_uri["query"] );

          foreach ($search_uri_vars as $var) {
            $search_var = explode("=", $var);

            switch ($search_var[0]) {
              case 'firstname':
                $firstname = urldecode($search_var[1]);
                break;
              case 'lastname':
                $lastname = urldecode($search_var[1]);
                break;
              case 'location':
                $location = urldecode($search_var[1]);
                break;
              case 'specialization':
                $specialization = urldecode($search_var[1]);
                break;
            }//end switch
          }//end foreach
        }//end check is_ajax
      }
        parse_str($query_string,$query_vars);

        if ($query_vars['scope'] == 'standard' || $query_vars['scope'] == 'pt' || $query_vars['scope'] == 'gym' ) {
          $ids = get_search_members_ids($query_vars['scope'], $firstname, $lastname, $gymname, $location, $specialization);
          return $query_string.$ids;
        }
        else
          return  $query_string;
      
    }else{
      return $query_string;
    }

    
}
//add_action( 'bp_legacy_theme_ajax_querystring', 'my_bp_loop_querystring', 20, 2 );



function remove_action_boss_edu(){
  remove_action( 'bp_init', 'buddyboss_wall_remove_original_update_functions',9999);
}
add_action( 'bp_init', 'remove_action_boss_edu' );


function mm_buddyboss_wall_remove_original_update_functions()
{
  if(function_exists('buddyboss_wall'))
  {
      /* actions */
      if ( buddyboss_wall()->is_enabled() )
      {
        // Remove actions related to posting and likes
        remove_action( 'wp_ajax_post_update', 'bp_dtheme_post_update' );
        remove_action( 'wp_ajax_post_update', 'bp_legacy_theme_post_update' );
        remove_action( 'wp_ajax_activity_mark_fav',   'bp_legacy_theme_mark_activity_favorite' );
        remove_action( 'wp_ajax_activity_mark_unfav', 'bp_legacy_theme_unmark_activity_favorite' );

        // Add our custom actions to handle posting and likes
        add_action( 'wp_ajax_activity_mark_unfav', 'buddyboss_wall_unmark_activity_favorite' );
        add_action( 'wp_ajax_activity_mark_fav', 'buddyboss_wall_mark_activity_favorite' );
        add_action( 'wp_ajax_post_update', 'mm_buddyboss_wall_post_update' );//this is deference from standard boss function

        // Add action for read more links to handle embeds,
        // this was left out of BP's legacy theme support
        add_action( 'bp_legacy_theme_get_single_activity_content', 'bp_dtheme_embed_read_more' );
      }
  }

}
add_action( 'bp_init', 'mm_buddyboss_wall_remove_original_update_functions', 9999 );

function mm_buddyboss_wall_post_update()
{
  global $bp;

  // Check the nonce
  check_admin_referer( 'post_update', '_wpnonce_post_update' );

  if ( !is_user_logged_in() ) {
    echo '';
    return false;
  }

  if ( empty( $_POST['content'] ) ) {

    if($_SERVER['HTTP_REFERER'] == home_url().'/' && bp_get_member_type($bp->loggedin_user->id) == 'enchanced'){
      return false;
    }elseif($_SERVER['HTTP_REFERER'] == home_url().'/' && bp_get_member_type($bp->loggedin_user->id) == 'standard'){
       echo '<div id="message" class="error"><p>' . __( 'Please enter some content to post.', 'buddyboss-wall' ) . '</p></div><br>';
    }else{
       echo '<div id="message" class="error"><p>' . __( 'Please enter some content to post.', 'buddyboss-wall' ) . '</p></div><br>';
    }

    return false;
  }

  $activity_id = false;

  if ( empty( $_POST['object'] ) && function_exists( 'bp_activity_post_update' ) )
  {
    if ( ! bp_is_my_profile() && bp_is_user() )
    {
      $content = "@". bp_get_displayed_user_username()." ".$_POST['content'];
    }
    else {
      $content = $_POST['content'];
    }

    $activity_id = bp_activity_post_update( array( 'content' => $content ) );
  }
  elseif ( $_POST['object'] == 'groups' )
  {
    if ( !empty( $_POST['item_id'] ) && function_exists( 'groups_post_update' ) )
    {
      $activity_id = groups_post_update( array( 'content' => $_POST['content'], 'group_id' => $_POST['item_id'] ) );
    }
  }
  else {
    $activity_id = apply_filters( 'bp_activity_custom_update', $_POST['object'], $_POST['item_id'], $_POST['content'] );
  }

  if ( ! $activity_id )
  {
    echo '<div id="message" class="error"><p>' . __( 'There was a problem posting your update, please try again.', 'buddyboss-wall' ) . '</p></div>';
    return false;
  }

  if($_SERVER['HTTP_REFERER'] == home_url().'/' && bp_get_member_type($bp->loggedin_user->id) == ('pt' || 'gym') ){
    return false;
  }else{
    if ( bp_has_activities ( 'include=' . $activity_id ) ) : ?>
      <?php while ( bp_activities() ) : bp_the_activity(); ?>
        <?php bp_get_template_part( 'activity/entry' ) ?>
      <?php endwhile; ?>
    <?php endif;
  }
  


}






/* = Custom Activity Stream
-----------------------------------------------
 * Place this function in bp-custom.php (in your plugins directory)
 */
function my_activity_stream($args ) {
  if ( bp_has_activities($args) ) : ?>

  <div class="pagination">
    <div class="pag-count"><?php bp_activity_pagination_count() ?></div>
    <div class="pagination-links"><?php bp_activity_pagination_links() ?></div>
  </div>

  <ul id="activity-stream" class="activity-list item-list">

  <?php while ( bp_activities() ) : bp_the_activity(); ?>

    <li class="<?php bp_activity_css_class() ?>" id="activity-<?php bp_activity_id() ?>">

      <div class="activity-avatar">
        <a href="<?php bp_activity_user_link() ?>">
          <?php bp_activity_avatar( 'type=full&width=100&height=100' ) ?>
        </a>
      </div>

      <div class="activity-content">

        <div class="activity-header">
          <?php bp_activity_action() ?>
        </div>

        <?php if ( bp_get_activity_content_body() ) : ?>
          <div class="activity-inner">
            <?php bp_activity_content_body() ?>
          </div>
        <?php endif; ?>

        <?php do_action( 'bp_activity_entry_content' ) ?>

      </div>
    </li>

  <?php endwhile; ?>

  </ul>

<?php else : ?>
  <div id="message" class="info">
    <p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ) ?></p>
  </div>
<?php endif;
wp_reset_query();
}






















function print_video_container(){
    

    if(is_home()){
        $source = (wp_is_mobile()) ? get_stylesheet_directory_uri().'/videos/home_full' : get_stylesheet_directory_uri().'/videos/home';
    }
    elseif(is_page('my-progress')){
        $source = (wp_is_mobile()) ? get_stylesheet_directory_uri().'/videos/my-progress_full' : get_stylesheet_directory_uri().'/videos/my-progress';
    }
    elseif(is_page('my-fitbit')){
        $source = (wp_is_mobile()) ? get_stylesheet_directory_uri().'/videos/my-fitbit_full' : get_stylesheet_directory_uri().'/videos/my-fitbit';
    }
    elseif(is_page('training-schedule')||is_page('trainer-calendar')){
        $source = (wp_is_mobile()) ? get_stylesheet_directory_uri().'/videos/training-schedule_full' :  get_stylesheet_directory_uri().'/videos/training-schedule';
    }
    elseif(is_page('food-supplement-diary')){
        $source = (wp_is_mobile()) ? get_stylesheet_directory_uri().'/videos/nutrition-diary_full' : get_stylesheet_directory_uri().'/videos/nutrition-diary';
    }
    elseif(is_page('one-rep-max')){
        $source = (wp_is_mobile()) ? get_stylesheet_directory_uri().'/videos/one-rep-max_full' : get_stylesheet_directory_uri().'/videos/one-rep-max';
    }

    if(isset($source)){
        $autoplay = (wp_is_mobile()) ? '' : 'autoplay';

        echo  '<div id="mm-video-container">
                <video width="230" height="90" '.$autoplay.' muted poster="'.get_stylesheet_directory_uri().'/videos/no-video-support.png">
                    <source src="'.$source.'.mp4" type="video/mp4">
                    <source src="'.$source.'.webm" type="video/webm">
                    <source src="'.$source.'.ogg" type="video/ogg">
                </video>
                <i id="mm-video-sound" class="fa fa-volume-off"></i>';
        echo (wp_is_mobile()) ? '<i id="mm-video-play" class="fa fa-lg fa-play-circle-o"></i>' : '';
        echo  '</div>';
    }
}


function workout_exercise_selectbox( $id, $name, $class, $required = false){
  
  global $wpdb;

  $exercises = $wpdb->get_results("SELECT e.*, `ei`.`image`, `com`.`comment`, `c`.`id` AS category_id, `c`.`name` AS category_name FROM `{$wpdb->prefix}workout_exercise` AS e 
                      LEFT JOIN {$wpdb->prefix}workout_exercisecategory AS c ON `c`.`id`=`e`.`category`
                      LEFT JOIN {$wpdb->prefix}workout_exerciseimage AS ei ON `ei`.`exercise`=`e`.`id` 
                      LEFT JOIN {$wpdb->prefix}workout_exercisecomment AS com ON `com`.`exercise`=`e`.`id` 
                      #WHERE `e`.`id`= 345
                      ORDER BY category_name ASC, name ASC", ARRAY_A);
  $muscles = get_workout_muscles();
  $equipments = get_workout_equipment();

  $exercises_ = array();
  $exercises_allready = array();

  foreach ($exercises as $key => $exercise) {
    $m_arr = $m_sec_arr = $m_is_front = $m_sec_is_front = $equip_arr = array();

	if($exercise['muscles'] != 'null'){
		foreach (json_decode($exercise['muscles']) as $muscle){
		  array_push($m_arr,$muscles[$muscle]['name']);
		  array_push($m_is_front,$muscles[$muscle]['is_front']);
		}
	}

	if($exercise['muscles_secondary']){
		foreach (json_decode($exercise['muscles_secondary']) as $muscle){
		  array_push($m_sec_arr,$muscles[$muscle]['name']);
		  array_push($m_sec_is_front,$muscles[$muscle]['is_front']);
		}
	}
    
	if($exercise['equipment'] != 'null'){
		foreach (json_decode($exercise['equipment']) as $equipment){
		  array_push($equip_arr,$equipments[$equipment]['name']);
		}
	}

    $exercises_[$exercise['id']]['id'] = $exercise['id'];
    $exercises_[$exercise['id']]['name'] = $exercise['name'];
    $exercises_[$exercise['id']]['category_id'] = $exercise['category_id'];
    $exercises_[$exercise['id']]['category_name'] = $exercise['category_name'];
    $exercises_[$exercise['id']]['description'] = $exercise['description'];
    $exercises_[$exercise['id']]['muscles'] = implode(', ', $m_arr);
    $exercises_[$exercise['id']]['muscles_secondary'] = implode(', ', $m_sec_arr);
    $exercises_[$exercise['id']]['muscles_is_front'] = implode(', ', $m_is_front);
	if($exercise['muscles'] != 'null'){
    	$exercises_[$exercise['id']]['muscles_ids'] = implode(', ', json_decode($exercise['muscles']));
	}
    $exercises_[$exercise['id']]['muscles_secondary_is_front'] = implode(', ', $m_sec_is_front);
    $exercises_[$exercise['id']]['muscles_secondary_ids'] = implode(', ', json_decode($exercise['muscles_secondary']));
    $exercises_[$exercise['id']]['equipment'] = implode(', ', $equip_arr);
    $exercises_[$exercise['id']]['images'] = ( in_array($exercise['id'], $exercises_allready) && strlen($exercises_[$exercise['id']]['images']) > 0 ) 
                                              ? $exercises_[$exercise['id']]['images'].'&&&'.$exercise['image'] 
                                              : $exercise['image'];
    $exercises_[$exercise['id']]['comment'] = ( in_array($exercise['id'], $exercises_allready) && strlen($exercises_[$exercise['id']]['comment']) > 0 ) 
                                              ? $exercises_[$exercise['id']]['comment'].'<br>'.$exercise['comment'] 
                                              : $exercise['comment'];

    array_push($exercises_allready, $exercise['id']);
  }

  $selectbox =  '<select id="'.$id.'" name="'.$name.'" class="'.$class.'" ';
  $selectbox .= ($required) ? 'required' : '';
  $selectbox .= '>';
  $selectbox .= '<option value="">-</option>';
  $category = ''; 
  foreach ($exercises_ as $exercise) {
    if ($category != $exercise['category_id']){
      if ($category != '')
        $selectbox .= '</optgroup>';
      $selectbox .= '<optgroup label="'.$exercise['category_name'].'">';
    }
    $selectbox .= '<option 
                    data-name="'.$exercise['name'].'" 
                    data-category-id="'.$exercise['category_id'].'"
                    data-category-name="'.$exercise['category_name'].'"
                    data-description="'.$exercise['description'].'"
					data-video="'.stripslashes(esc_html(get_option('excercisevideo'.$exercise['id']))).'"
                    data-muscles="'.$exercise['muscles'].'"
                    data-muscles-secondary="'.$exercise['muscles_secondary'].'"
                    data-muscles-is-front="'.$exercise['muscles_is_front'].'"
                    data-muscles-ids="'.$exercise['muscles_ids'].'"
                    data-muscles-secondary-is-front="'.$exercise['muscles_secondary_is_front'].'"
                    data-muscles-secondary-ids="'.$exercise['muscles_secondary_ids'].'"
                    data-equipment="'.$exercise['equipment'].'"
                    data-comment="'.$exercise['comment'].'"
                    data-images="'.$exercise['images'].'"
                    value="'.$exercise['id'].'">'.$exercise['name'].'</option>';
    $category = $exercise['category_id'];    
  }
  if ($category != '')
    $selectbox .= '</optgroup>';
  $selectbox .= '</select>';

  return $selectbox;

}

function get_workout_muscles(){
    global $wpdb;
    $muscles = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "workout_muscles",ARRAY_A);
    foreach ($muscles as $muscle)
      $muscles_[$muscle['id']] = $muscle;
    return $muscles_;
}


function get_workout_equipment(){
    global $wpdb;
    $equipments = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "workout_equipment",ARRAY_A);

    foreach ($equipments as $equipment)
      $equipments_[$equipment['id']] = $equipment;

    return $equipments_;
}


function get_workout_categories(){
    global $wpdb;
    $categories = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "workout_exercisecategory");
    return $categories;
}





function get_workout_logs($user_id){
    global $wpdb;
    $workout_logs = array();
    $logs = $wpdb->get_results("SELECT `l`.* FROM {$wpdb->prefix}workout_logs AS l
                                LEFT JOIN {$wpdb->prefix}workout_exercise AS e ON `e`.`id` = `l`.`exercise_id`
                                WHERE user_id=".$user_id." OR client_id=".$user_id." 
                                ORDER BY `l`.`LogOrder` ASC");
      if($logs)
        foreach ($logs as $key => $value)
          $workout_logs[$value->uniq_id][] = $value;
    return $workout_logs;
}

function get_workout_logs_sample($user_id){
    global $wpdb;
    $workout_logs = array();
    $logs = $wpdb->get_results("SELECT `l`.* FROM {$wpdb->prefix}workout_logs_sample AS l
                                LEFT JOIN {$wpdb->prefix}workout_exercise AS e ON `e`.`id` = `l`.`exercise_id`
                                ORDER BY `l`.`LogOrder` ASC");
      if($logs)
        foreach ($logs as $key => $value)
          $workout_logs[$value->uniq_id][] = $value;
    return $workout_logs;
}

function get_adv_workout_logs($user_id){
    global $wpdb;
    $adv_workout_logs = array();
    $logs = $wpdb->get_results("SELECT `l`.* FROM {$wpdb->prefix}workout_logs_adv AS l
                                LEFT JOIN {$wpdb->prefix}workout_exercise AS e ON `e`.`id` = `l`.`exercise_id`
                                WHERE user_id=".$user_id." OR client_id=".$user_id." 
                                ORDER BY `l`.`LogOrder` ASC");
      if($logs)
        foreach ($logs as $key => $value)
          $adv_workout_logs[$value->uniq_id][] = $value;
    return $adv_workout_logs;
}





/**************************************************************
        FAQ POSTS
***************************************************************/

function get_member_help(){
  global $wpdb;
  global $bp;
  $member_type_search = '%"'.bp_get_member_type($bp->loggedin_user->id).'"%';

  $query = $wpdb->prepare("
    SELECT {$wpdb->prefix}posts.* FROM {$wpdb->prefix}posts  
      LEFT JOIN {$wpdb->prefix}postmeta AS m ON ( {$wpdb->prefix}posts.ID = m.post_id )
      WHERE 
        {$wpdb->prefix}posts.post_type = 'faqs-post' 
        AND {$wpdb->prefix}posts.post_status = 'publish'
        AND (
                 (m.meta_key = 'wpcf-user-type') 
                 AND
                 (m.meta_value LIKE '%s')
          )
        GROUP BY {$wpdb->prefix}posts.ID 
        ORDER BY {$wpdb->prefix}posts.post_date DESC
    ", $member_type_search);
    
  $help = $wpdb->get_results($query);
  
  return $help;
}

function add_faqs_post_columns($gallery_columns) {
  $return['title'] = "Question";
  $return['user-type'] = "User Type";
  $return['date'] = 'Published';
  return $return;
}
add_filter('manage_edit-faqs-post_columns', 'add_faqs_post_columns');


 
function manage_faqs_post_columns($column_name, $id) {
      if($column_name=='user-type'){
        $types = get_post_meta($id, 'wpcf-user-type',true);
        foreach ($types as $key => $value)
          echo (reset($value) == 'standard') ? 'Standard User<br>' : ((reset($value)=='pt') ? 'Personal Trainer<br>' : 'GYM User<br>');
      }
}
add_action('manage_faqs-post_posts_custom_column', 'manage_faqs_post_columns', 10, 2);



/**************************************************************
		END FAQ POSTS
***************************************************************/


/**************************************************************
		TIMETABLES
***************************************************************/

function get_timetables($user_id,$date){
	global $wpdb;
  //	return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}timetables WHERE user_id = ".$user_id." AND date=".$date." ORDER BY time ASC" );
  
  return $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}timetables WHERE user_id = ".$user_id." ORDER BY time ASC" );
}



/**************************************************************
		END TIMETABLES
***************************************************************/










/* Creates the list of members on the Sent Invite screen */
function bp_new_group_invite_member_list_() {
  echo bp_get_new_group_invite_member_list_();
}
  function bp_get_new_group_invite_member_list_( $args = '' ) {
    global $bp;

    $defaults = array(
      'group_id' => false,
      'separator' => 'li'
    );

    $r = wp_parse_args( $args, $defaults );
    extract( $r, EXTR_SKIP );

    if ( !$group_id )
      $group_id = isset( $bp->groups->new_group_id ) ? $bp->groups->new_group_id : $bp->groups->current_group->id;

    $items = array();

    $friends = get_members_invite_list( $bp->loggedin_user->id, $group_id );

    if ( $friends ) {
      $invites = groups_get_invites_for_group( $bp->loggedin_user->id, $group_id );

      for ( $i = 0; $i < count( $friends ); $i++ ) {
        $checked = '';
        if ( $invites ) {
          if ( in_array( $friends[$i]['id'], $invites ) ) {
            $checked = ' checked="checked"';
          }
        }

        $items[] = '<label><' . $separator . '><input' . $checked . ' type="checkbox" name="friends[]" id="f-' . $friends[$i]['id'] . '" value="' . esc_html( $friends[$i]['id'] ) . '" /> ' . get_fullname($friends[$i]['id']) . '</' . $separator . '></label><br>';
      }
    }

    return implode( "\n", (array)$items );
  }





/**************************************************************
    SOCIAK INVITES
***************************************************************/


add_action( 'after_setup_theme', 'calling_child_theme_setup' );

function calling_child_theme_setup() {
  remove_shortcode('SociaPlugin');
  add_shortcode('SociaPlugin', 'social_invites_shortcode');
}
function social_invites_shortcode($atts) {
  $atts['shortcode']=1;
  $google = intval($atts['google']);
  $facebook = intval($atts['facebook']);
  $twitter = intval($atts['twitter']);
  $email = intval($atts['email']);
  
  $content = '<ul class="social-invites-buttons">';
    if($google)
      $content .= '<li><a href="'.site_url('/?get_contact=google').'" class="popupwindow social-invite-google" rel="windowCallUnload"><i class="fa fa-2x fa-google-plus"></i></a></li>';
    if($facebook){
      $fb = new AheadzenFacebook();
      $content .= '<li><a href="'.$fb->inviter_url().'" class="popupwindow social-invite-fb" rel="windowCallUnload"><i class="fa fa-2x fa-facebook"></i></a></li>';
    }
    if($twitter)
      $content .= '<li><a href="'.site_url('/?get_contact=twitter').'" class="popupwindow social-invite-twitter" rel="windowCallUnload"><i class="fa fa-2x fa-twitter"></i></a></li>';
    if($email)
      $content .= '<li><a href="'.site_url('/?get_contact=email').'" class="popupwindow social-invite-email" rel="windowCallUnload"><i class="fa fa-2x fa-envelope"></i></a></li>';
  $content .= '</ul>';
  
  return $content;
}

/**************************************************************
    END SOCIAL INVITES
***************************************************************/



/*
function get_clients_by_calendar($trainer){
  global $wpdb;
  $students = array();
  $students_list = '';
  $table_name = $wpdb->prefix . 'members_connections';
 
  $results = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE status = 'connected' AND (request_sender_id = ".$trainer." OR request_reciver_id = ".$trainer.")" );
  if(!empty($results)){
    foreach($results as $key=>$result){
      $initiator_member_type = bp_get_member_type($result->request_reciver_id);
      $friend_member_type = bp_get_member_type($result->request_sender_id);
      if($initiator_member_type == 'standard'){
        $students[$key]['ID'] = $result->request_reciver_id;
        $students[$key]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$result->request_reciver_id);
        $students[$key]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$result->request_reciver_id);
        if( !next( $results ) ){
          $students_list .= $result->request_reciver_id;
        }
        else{
           $students_list .= $result->request_reciver_id.', ';
        }
        
      }elseif($friend_member_type == 'standard'){
        $students[$key]['ID'] = $result->request_sender_id;
        $students[$key]['firstname'] = bp_get_profile_field_data('field=First Name&user_id='.$result->request_sender_id);
        $students[$key]['lastname'] = bp_get_profile_field_data('field=Last Name&user_id='.$result->request_sender_id);
        if( !next( $results ) ){
          $students_list .= $result->request_sender_id;
        }
        else{
           $students_list .= $result->request_sender_id.', ';
        }
      }
    }


    $students_arr = explode(',',$students_list);

    $table_name = $wpdb->prefix . 'calendars_schedules';
    $users_in_calendar = $wpdb->get_results( "SELECT `person_id`,`time_row`,`calendar_date` FROM ".$table_name." WHERE calendar_date >= CURDATE() AND user_id=".$trainer.' AND person_id IN ('.$students_list.') ORDER BY calendar_date ASC, time_row ASC', ARRAY_A );
    
    foreach($users_in_calendar as $key=>$user){
      $scheduled_users[$user['person_id']][] = $user;
    }

    $current_date = current_time('Y-m-d');
    $current_hour = current_time('H');
    //loop throu all users in schedule
    if(!empty($scheduled_users)){
      foreach($scheduled_users as $key=>$events){
        //loop howmany times user in schedule
        foreach($events as $k=>$event){
          if(($event['calendar_date'] == $current_date && intval($event['time_row']+5)>=intval($current_hour)) || $event['calendar_date']>$current_date)
            $scheduled_users_valid[$key][] = $event;
        }
      }

    }


    if(!empty($scheduled_users_valid)){
    
      //retrive only first(earliest) event in schedule for client
      $scheduled_users_valid = array_map(function($i) {return $i[0];}, $scheduled_users_valid);
    
      foreach($scheduled_users_valid as $key=>$result){
        
        $students_arr_key = array_search ($result['person_id'], $students_arr);
        
        if ($students_arr_key || $students_arr_key === (integer)0){
          $temp_student = $students[$students_arr_key];
          unset($students[$students_arr_key]);
          //array_unshift($students, $temp_student);
          $toped_users[] = $temp_student;
        }
        
      }
      //sort students that not in schedule by firstname
      usort($students, function($a, $b) {
        return strcmp($a['firstname'], $b['firstname']);
      });
      //merge students in schedule and students not in schedule
      return array_merge ( $toped_users, $students );
    }else{
      usort($students, function($a, $b) {
        return strcmp($a['firstname'], $b['firstname']);
      });
      return $students;
    }
  
  }

  return false;
}
*/

add_action('wp_head','ajaxurl');
function ajaxurl() {
	?>
	<script type="text/javascript">
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
	<?php
}

add_action('wp_ajax_saveorderform', 'saveorderformFunction');
add_action('wp_ajax_nopriv_saveorderform', 'saveorderformFunction');
function saveorderformFunction() { 
	global $wpdb;
	$i = 1;
	foreach($_POST['logid2'] as $logid){
		$sql = "UPDATE ".$wpdb->prefix."workout_logs SET  
			LogOrder = '".$i."'
			WHERE id = '".$logid."'";
		$wpdb->query($sql);
		$i++;
	}
	exit();
}

add_action('wp_ajax_saveorderformsample', 'saveorderformsampleFunction');
add_action('wp_ajax_nopriv_saveorderformsample', 'saveorderformsampleFunction');
function saveorderformsampleFunction() { 
	global $wpdb;
	$i = 1;
	foreach($_POST['logid22'] as $logid){
		$sql = "UPDATE ".$wpdb->prefix."workout_logs_sample SET  
			LogOrder = '".$i."'
			WHERE id = '".$logid."'";
		$wpdb->query($sql);
		$i++;
	}
	exit();
}

add_action('wp_ajax_saveorderformadv', 'saveorderformadvFunction');
add_action('wp_ajax_nopriv_saveorderformadv', 'saveorderformadvFunction');
function saveorderformadvFunction() { 
	global $wpdb;
	$i = 1;
	foreach($_POST['logid'] as $logid){
		$sql = "UPDATE ".$wpdb->prefix."workout_logs_adv SET  
			LogOrder = '".$i."'
			WHERE id = '".$logid."'";
		$wpdb->query($sql);
		$i++;
	}
	exit();
}
function your_bp_admin_bar_add() {
  global $wp_admin_bar, $bp, $current_user;
  get_currentuserinfo();
  $userLogin = $current_user->user_login;
  $user = new WP_User( $current_user->ID );
  $mevendor = false;
  if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
	foreach ( $user->roles as $role ){
		$role = $role;
		if($role == 'vendor'){
		   $mevendor = true;
		}
	}	
  }	
  
  $member_type = bp_get_member_type($current_user->ID);
 
  if(!current_user_can('manage_options') && (bp_get_member_type(get_current_user_id()) == 'pt' || bp_get_member_type(get_current_user_id())=='gym')){
		 
	if($mevendor){
		 
	          if ( !bp_use_wp_admin_bar() || defined( 'DOING_AJAX' ) )
		    return;
		 
		  $user_domain = bp_loggedin_user_domain();
		  $item_link = trailingslashit( $user_domain . 'vendor-dashboard');
		 
		  $wp_admin_bar->add_menu( array(
		    'parent'  => $bp->my_account_menu_id,
		    'id'      => 'vendor-dashboard',
		    'title'   => __( 'Shop Dashboard', 'your-plugin-domain' ),
		    'href'    => trailingslashit( $item_link ),
		    'meta'    => array( 'class' => 'menupop' )
		  ) );
		 
		  // add submenu item
		  $wp_admin_bar->add_menu( array(
		    'parent' => $bp->my_account_menu_id,
		    'id'     => 'vendor-dashboard-shop',
		    'title'  => __( 'My Shop', 'your-plugin-domain' ),
		    'href'   => home_url() . '/vendors/'.$userLogin
		  ) );	   
		 
		  
	}else{		  
  
		if ( !bp_use_wp_admin_bar() || defined( 'DOING_AJAX' ) )
		   return;
					 
		$user_domain = bp_loggedin_user_domain();
		$item_link = trailingslashit( home_url() . '/vendor_dashboard');
					 
		$wp_admin_bar->add_menu( array(
					    'parent'  => $bp->my_account_menu_id,
					    'id'      => 'vendor_dashboard',
					    'title'   => __( 'Become A Vendor', 'your-plugin-domain' ),
					    'href'    => trailingslashit( $item_link ),
					    'meta'    => array( 'class' => 'menupop' )
		) );
				  
	}
  }
	  	  
  
  
  
}
add_action( 'bp_setup_admin_bar', 'your_bp_admin_bar_add', 300 );

register_nav_menus( array(
	'home_logout' => 'HomePage Menu'
) );

function custome_booking_table() {
	
	$timtable	= $_POST['timetable'];
	$gymid		= $_POST['gymid'];
	$user		= $_POST['user'];
	
	$args = array(
			'user_id' => $user,
			'item_id' => $gymid,
			'secondary_item_id' => $timtable,
			'component_name' => 'book_gym_timetable',
			'component_action' => 'book_gym_timetable_'.$timtable,
			'date_notified' => bp_core_current_time(),
			'is_new' => 1, );
			
	$ownerargs = array(
			'user_id' => $gymid,
			'item_id' => $user,
			'secondary_item_id' => $timtable,
			'component_name' => 'owner_gym_timetable',
			'component_action' => 'owner_gym_timetable_'.$timtable,
			'date_notified' => bp_core_current_time(),
			'is_new' => 1, );		
			
	$booker_notification = bp_notifications_add_notification( $args );
	$owner_notification = bp_notifications_add_notification( $ownerargs );
	
	global $wpdb;

	$myrows = $wpdb->get_results( "SELECT classsize FROM {$wpdb->prefix}timetables WHERE id = ".$timtable);
	
	$available_class_size = $myrows[0]->classsize - 1;
	
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}timetables SET classsize = ".$available_class_size." WHERE id = ".$timtable));
}

add_action ('wp_ajax_custome_booking_table', 'custome_booking_table');

/** New functions   **/

function custome_table_notification() {
	
	$timtable	= $_POST['timetable'];
	$gymid		= $_POST['gymid'];
	$user		= $_POST['user'];
	
	custom_filter_notifications_get_registered_components_book();
}

add_action ('wp_ajax_custome_table_notification', 'custome_table_notification');

function custom_filter_notifications_get_registered_components_book( $component_names = array() ) {
	
	// Force $component_names to be an array
	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}
	// Add 'custom' component to registered components array
	array_push( $component_names, 'book_gym_timetable' );
	array_push( $component_names, 'owner_gym_timetable' );
	// Return component's with 'custom' appended
	return $component_names; 
	custom_format_buddypress_notifications();
}
add_filter( 'bp_notifications_get_registered_components', 'custom_filter_notifications_get_registered_components_book' );

function _remove_script_version( $src ){
$parts = explode( '?ver', $src );
return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );


function defer_parsing_of_js ( $url ) {
if ( FALSE === strpos( $url, '.js' ) ) return $url;
if ( strpos( $url, 'jquery.js' ) ) return $url;
return "$url' defer ";
}
add_filter( 'clean_url', 'defer_parsing_of_js', 11, 1 );


?>