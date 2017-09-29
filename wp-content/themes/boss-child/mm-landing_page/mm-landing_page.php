<?php



// Add "Mirror Muscles Landing Page" link to the "Appearance" menu
add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page() {
  // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
  add_menu_page( 'Landing page', 'Landing Page', 'manage_options', 'mm-landing.php', 'mm_landing', 'dashicons-format-aside', 90 );
}

function mm_landing_enqueue_scripts($suffix) {
    if($suffix=='toplevel_page_mm-landing'){
        wp_enqueue_script('jquery');
        wp_register_script( 'mm-landing', get_stylesheet_directory_uri() .'/mm-landing_page/js/mm-landing.js', array('jquery','media-upload','thickbox') );
        
            wp_enqueue_script( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
            wp_enqueue_style( 'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css', false ); 
        


        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');

        wp_enqueue_script('media-upload');
        wp_enqueue_script('mm-landing');
     }
}
add_action('admin_enqueue_scripts', 'mm_landing_enqueue_scripts');

function mm_landing(){
?>

	<div id="tabs">
        <ul>
            <li class="tab_landing"><a href="#tabs-1">All Homepages</a></li>
            <li class="tab_landing"><a href="#tabs-2">Individual Page</a></li>
			<li class="tab_landing"><a href="#tabs-3">PT Page</a></li>
			<li class="tab_landing"><a href="#tabs-4">Gym Page</a></li>
        </ul>
		
		<div id="tabs-1">
		<div>
		
		<?php 
		
		if(isset($_POST['all_home_update'])){
			
			$setting = array(
				'main_title'=>stripslashes_deep($_POST["main_title"]),
				'individual_title'=>stripslashes_deep($_POST["individual_title"]),
				'individual_img'=>esc_attr($_POST["individual_img"]),
				'pt_title'=>stripslashes_deep($_POST["pt_title"]),
				'pt_img'=>esc_attr($_POST["pt_img"]),
				'gym_title'=>stripslashes_deep($_POST["gym_title"]),
				'gym_img'=>esc_attr($_POST["gym_img"]),
			);
			
			update_option("all_home_content", $setting);
		}
		
		if ( ! function_exists( 'wp_handle_upload' ) )
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );	
		
			$upload_overrides = array( 'test_form' => false );

			$movefile1 = wp_handle_upload( $_FILES['individual_img'], $upload_overrides );
			$movefile2 = wp_handle_upload( $_FILES['pt_img'], $upload_overrides );
			$movefile3 = wp_handle_upload( $_FILES['gym_img'], $upload_overrides );
			
			$all_home_content = get_option('all_home_content');
			//echo "<pre>";
			//print_r($all_home_content); 
			$main_title = $all_home_content["main_title"];
			$individual_title = $all_home_content["individual_title"];
			$individual_img = $all_home_content["individual_img"];
			$pt_title = $all_home_content["pt_title"];
			$pt_img = $all_home_content["pt_img"];
			$gym_title = $all_home_content["gym_title"];
			$gym_img = $all_home_content["gym_img"];
			
						
			
		
		?>
		
			<h2>Landing Page Contents</h2>
				<form method="POST" action="">
						<input type="hidden" name="message" value="Homepage Content Save Successfully">
						<table class="form-table">
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									   Main Title
									</label> 
								</th>
								<td>
									<input type="text" name="main_title" style="width:60%;padding:10px;" value="<?php echo $main_title;  ?>">
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Individual Title
									</label> 
								</th>
								<td>
									<input type="text" name="individual_title" style="width:60%;padding:10px;" value="<?php echo $individual_title; ?>">
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Individual Image
									</label> 
								</th>
								<td>
									<input id="individual_img" class="image-upl" type="text" size="36" name="individual_img" value="<?php echo esc_url( $individual_img ); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="individual_image_btn" type="button" value="Upload Image" />
									<div id="individual_image_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url( $individual_img ); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Personal Triner Title
									</label> 
								</th>
								<td>
									<input type="text" name="pt_title" style="width:60%;padding:10px;" value="<?php echo $pt_title; ?>">
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Personal Trainer Image
									</label> 
								</th>
								<td>
									<input id="pt_img" class="image-upl" type="text" size="36" name="pt_img" value="<?php echo esc_url($pt_img); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="pt_image_btn" type="button" value="Upload Image" />
									<div id="pt_image_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($pt_img); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Gym Title
									</label> 
								</th>
								<td>
									<input type="text" name="gym_title" style="width:60%;padding:10px;" value="<?php echo $gym_title; ?>">
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Gym Trainer Image
									</label> 
								</th>
								<td>
									<input id="gym_img" class="image-upl" type="text" size="36" name="gym_img" value="<?php echo esc_url($gym_img); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="gym_image_btn" type="button" value="Upload Image" />
									<div id="gym_image_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($gym_img); ?>" />
									</div>
								</td>
							</tr>
						</table>
						<input type="hidden" name="all_home_update" value="Y" />
						<p><input type="submit" name="all_home_update" value="Save" class="button-primary"/></p>
				</form>
			</div>
		</div>
		
		<?php 
		if(isset($_POST['individual_page'])){
			$settingg = array(

				'video_individual'=> stripslashes_deep($_POST["video_individual"]),
				
				'icon_img1'=>esc_attr($_POST["icon_img1"]),
				'icon_title1'=>stripslashes_deep($_POST["icon_title1"]),
				
				'icon_img2'=>esc_attr($_POST["icon_img2"]),
				'icon_title2'=>stripslashes_deep($_POST["icon_title2"]),
				
				'icon_img3'=>esc_attr($_POST["icon_img3"]),
				'icon_title3'=>stripslashes_deep($_POST["icon_title3"]),
				
				'icon_img4'=>esc_attr($_POST["icon_img4"]),
				'icon_title4'=>stripslashes_deep($_POST["icon_title4"]),
				
				'icon_img5'=>esc_attr($_POST["icon_img5"]),
				'icon_title5'=>stripslashes_deep($_POST["icon_title5"]),
				
				'icon_img6'=>esc_attr($_POST["icon_img6"]),
				'icon_title6'=>stripslashes_deep($_POST["icon_title6"]),
				
				'icon_img7'=>esc_attr($_POST["icon_img7"]),
				'icon_title7'=>stripslashes_deep($_POST["icon_title7"]),
				
				'icon_img8'=>esc_attr($_POST["icon_img8"]),
				'icon_title8'=>stripslashes_deep($_POST["icon_title8"]),
				
				'icon_img9'=>esc_attr($_POST["icon_img9"]),
				'icon_title9'=>stripslashes_deep($_POST["icon_title9"]),
				
				'icon_img10'=>esc_attr($_POST["icon_img10"]),
				'icon_title10'=>stripslashes_deep($_POST["icon_title10"]),
				
				'icon_img11'=>esc_attr($_POST["icon_img11"]),
				'icon_title11'=>stripslashes_deep($_POST["icon_title11"]),
				
				'icon_img12'=>esc_attr($_POST["icon_img12"]),
				'icon_title12'=>stripslashes_deep($_POST["icon_title12"]),
				
			);
			
			update_option("all_individual_content", $settingg);
			
		}
		
		if ( ! function_exists( 'wp_handle_upload' ) )
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );	
		
			$upload_overrides = array( 'test_form' => false );

			$movefile4 = wp_handle_upload( $_FILES['icon_img1'], $upload_overrides );
			$movefile5 = wp_handle_upload( $_FILES['icon_img2'], $upload_overrides );
			$movefile6 = wp_handle_upload( $_FILES['icon_img3'], $upload_overrides );
			
			$movefile7 = wp_handle_upload( $_FILES['icon_img4'], $upload_overrides );
			$movefile8 = wp_handle_upload( $_FILES['icon_img5'], $upload_overrides );
			$movefile9 = wp_handle_upload( $_FILES['icon_img6'], $upload_overrides );
			
			$movefile10 = wp_handle_upload( $_FILES['icon_img7'], $upload_overrides );
			$movefile11 = wp_handle_upload( $_FILES['icon_img8'], $upload_overrides );
			$movefile12 = wp_handle_upload( $_FILES['icon_img9'], $upload_overrides );
			
			$movefile13 = wp_handle_upload( $_FILES['icon_img10'], $upload_overrides );
			$movefile14 = wp_handle_upload( $_FILES['icon_img11'], $upload_overrides );
			$movefile15 = wp_handle_upload( $_FILES['icon_img12'], $upload_overrides );
			
			
			$all_individual_content = get_option('all_individual_content');
			//echo "<pre>";
			//print_r($all_individual_content); 
			
			

			$video_individual = $all_individual_content["video_individual"];
			
			$icon_img1 = $all_individual_content["icon_img1"];
			$icon_title1 = $all_individual_content["icon_title1"];
			
			$icon_img2 = $all_individual_content["icon_img2"];
			$icon_title2 = $all_individual_content["icon_title2"];
			
			$icon_img3 = $all_individual_content["icon_img3"];
			$icon_title3 = $all_individual_content["icon_title3"];
			
			$icon_img4 = $all_individual_content["icon_img4"];
			$icon_title4 = $all_individual_content["icon_title4"];
			
			$icon_img5 = $all_individual_content["icon_img5"];
			$icon_title5 = $all_individual_content["icon_title5"];
			
			$icon_img6 = $all_individual_content["icon_img6"];
			$icon_title6 = $all_individual_content["icon_title6"];
			
			$icon_img7 = $all_individual_content["icon_img7"];
			$icon_title7 = $all_individual_content["icon_title7"];
			
			$icon_img8 = $all_individual_content["icon_img8"];
			$icon_title8 = $all_individual_content["icon_title8"];
			
			$icon_img9 = $all_individual_content["icon_img9"];
			$icon_title9 = $all_individual_content["icon_title9"];
			
			$icon_img10 = $all_individual_content["icon_img10"];
			$icon_title10 = $all_individual_content["icon_title10"];
			
			$icon_img11 = $all_individual_content["icon_img11"];
			$icon_title11 = $all_individual_content["icon_title11"];
			
			$icon_img12 = $all_individual_content["icon_img12"];
			$icon_title12 = $all_individual_content["icon_title12"];
		
			
			
		?>
		
		<div id="tabs-2">
			<div>
				<h2>Landing Page Contents</h2>
				<form method="POST" action="">
						<input type="hidden" name="message" value="Homepage Content Save Successfully">
						<table class="form-table">
							<!-- Icon Image 1 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Video for Gym Home
									</label> 
								</th>
								<td>
									<input id="video_individual"  type="text" size="36" name="video_individual" value="<?php echo $video_individual; ?>" style="width:60%;height:40px;padding:10px;" /><br />
									<span class="notes">Please add only last youtube video charecters like: xNVxEvhqMPU </span>
									<?php
									/* $old_description = ''; 
									$editor_id = 'video_gym';
									$settings = array( 'media_buttons' => true, 'textarea_name' => 'video_gym' );

									wp_editor( $old_description , $editor_id, $settings ); */
									?>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 1
									</label> 
								</th>
								<td>
									<input id="icon_img1" class="image-upl" type="text" size="36" name="icon_img1" value="<?php echo esc_url($icon_img1); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image1_btn" type="button" value="Upload Image" />
									<div id="icon_image1_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img1); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title1" style="width:60%;padding:10px;" value="<?php echo $icon_title1; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 2 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 2
									</label> 
								</th>
								<td>
									<input id="icon_img2" class="image-upl" type="text" size="36" name="icon_img2" value="<?php echo esc_url($icon_img2); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image2_btn" type="button" value="Upload Image" />
									<div id="icon_image2_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img2); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title2" style="width:60%;padding:10px;" value="<?php echo $icon_title2; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 3 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 3
									</label> 
								</th>
								<td>
									<input id="icon_img3" class="image-upl" type="text" size="36" name="icon_img3" value="<?php echo esc_url($icon_img3); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image3_btn" type="button" value="Upload Image" />
									<div id="icon_image3_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img3); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title3" style="width:60%;padding:10px;" value="<?php echo $icon_title3; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 4 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 4
									</label> 
								</th>
								<td>
									<input id="icon_img4" class="image-upl" type="text" size="36" name="icon_img4" value="<?php echo esc_url($icon_img4); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image4_btn" type="button" value="Upload Image" />
									<div id="icon_image4_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img4); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title4" style="width:60%;padding:10px;" value="<?php echo $icon_title4; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 5 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 5
									</label> 
								</th>
								<td>
									<input id="icon_img5" class="image-upl" type="text" size="36" name="icon_img5" value="<?php echo esc_url($icon_img5); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image5_btn" type="button" value="Upload Image" />
									<div id="icon_image5_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img5); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title5" style="width:60%;padding:10px;" value="<?php echo $icon_title5; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 6 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 6
									</label> 
								</th>
								<td>
									<input id="icon_img6" class="image-upl" type="text" size="36" name="icon_img6" value="<?php echo esc_url($icon_img6); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image6_btn" type="button" value="Upload Image" />
									<div id="icon_image6_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img6); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title6" style="width:60%;padding:10px;" value="<?php echo $icon_title6; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 7 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 7
									</label> 
								</th>
								<td>
									<input id="icon_img7" class="image-upl" type="text" size="36" name="icon_img7" value="<?php echo esc_url($icon_img7); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image7_btn" type="button" value="Upload Image" />
									<div id="icon_image7_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img7); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title7" style="width:60%;padding:10px;" value="<?php echo $icon_title7; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 8 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 8
									</label> 
								</th>
								<td>
									<input id="icon_img8" class="image-upl" type="text" size="36" name="icon_img8" value="<?php echo esc_url($icon_img8); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image8_btn" type="button" value="Upload Image" />
									<div id="icon_image8_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img8); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title8" style="width:60%;padding:10px;" value="<?php echo $icon_title8; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 9 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 9
									</label> 
								</th>
								<td>
									<input id="icon_img9" class="image-upl" type="text" size="36" name="icon_img9" value="<?php echo esc_url($icon_img9); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image9_btn" type="button" value="Upload Image" />
									<div id="icon_image9_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img9); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title9" style="width:60%;padding:10px;" value="<?php echo $icon_title9; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 10 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 10
									</label> 
								</th>
								<td>
									<input id="icon_img10" class="image-upl" type="text" size="36" name="icon_img10" value="<?php echo esc_url($icon_img10); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image10_btn" type="button" value="Upload Image" />
									<div id="icon_image10_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img10); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title10" style="width:60%;padding:10px;" value="<?php echo $icon_title10; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 11 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 11
									</label> 
								</th>
								<td>
									<input id="icon_img11" class="image-upl" type="text" size="36" name="icon_img11" value="<?php echo esc_url($icon_img11); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image11_btn" type="button" value="Upload Image" />
									<div id="icon_image11_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img11); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title11" style="width:60%;padding:10px;" value="<?php echo $icon_title11; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 12 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 12
									</label> 
								</th>
								<td>
									<input id="icon_img12" class="image-upl" type="text" size="36" name="icon_img12" value="<?php echo esc_url($icon_img12); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image12_btn" type="button" value="Upload Image" />
									<div id="icon_image12_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img12); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title12" style="width:60%;padding:10px;" value="<?php echo $icon_title12; ?>">
								</td>
							</tr>
							
							
							
							
						</table>
						<input type="hidden" name="individual_page" value="Y" />
						<p><input type="submit" name="individual_page" value="Save" class="button-primary"/></p>
				</form>
			</div>
		</div>
		
		
		<?php 
		if(isset($_POST['pt_page'])){
			$settingg = array(
			
				'video_pt'=>stripslashes_deep($_POST["video_pt"]),
			
				'icon_img_1'=>esc_attr($_POST["icon_img_1"]),
				'icon_title_1'=>stripslashes_deep($_POST["icon_title_1"]),
				
				
				'icon_img_2'=>esc_attr($_POST["icon_img_2"]),
				'icon_title_2'=>stripslashes_deep($_POST["icon_title_2"]),
				
				'icon_img_3'=>esc_attr($_POST["icon_img_3"]),
				'icon_title_3'=>stripslashes_deep($_POST["icon_title_3"]),
				
				'icon_img_4'=>esc_attr($_POST["icon_img_4"]),
				'icon_title_4'=>stripslashes_deep($_POST["icon_title_4"]),
				
				'icon_img_5'=>esc_attr($_POST["icon_img_5"]),
				'icon_title_5'=>stripslashes_deep($_POST["icon_title_5"]),
				
				'icon_img_6'=>esc_attr($_POST["icon_img_6"]),
				'icon_title_6'=>stripslashes_deep($_POST["icon_title_6"]),
				
				'icon_img_7'=>esc_attr($_POST["icon_img_7"]),
				'icon_title_7'=>stripslashes_deep($_POST["icon_title_7"]),
				
				'icon_img_8'=>esc_attr($_POST["icon_img_8"]),
				'icon_title_8'=>stripslashes_deep($_POST["icon_title_8"]),
				
				'icon_img_9'=>esc_attr($_POST["icon_img_9"]),
				'icon_title_9'=>stripslashes_deep($_POST["icon_title_9"]),
				
				'icon_img_10'=>esc_attr($_POST["icon_img_10"]),
				'icon_title_10'=>stripslashes_deep($_POST["icon_title_10"]),
				
				'icon_img_11'=>esc_attr($_POST["icon_img_11"]),
				'icon_title_11'=>stripslashes_deep($_POST["icon_title_11"]),
				
				'icon_img_12'=>esc_attr($_POST["icon_img_12"]),
				'icon_title_12'=>stripslashes_deep($_POST["icon_title_12"]),
				
				'icon_img_13'=>esc_attr($_POST["icon_img_13"]),
				'icon_title_13'=>stripslashes_deep($_POST["icon_title_13"]),
				
				'icon_img_14'=>esc_attr($_POST["icon_img_14"]),
				'icon_title_14'=>stripslashes_deep($_POST["icon_title_14"]),
				
				'icon_img_15'=>esc_attr($_POST["icon_img_15"]),
				'icon_title_15'=>stripslashes_deep($_POST["icon_title_15"]),
			);
			
			update_option("all_pt_content", $settingg);
			
		}
		
		if ( ! function_exists( 'wp_handle_upload' ) )
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );	
		
			$upload_overrides = array( 'test_form' => false );

			$movefile16 = wp_handle_upload( $_FILES['icon_img_1'], $upload_overrides );
			$movefile17 = wp_handle_upload( $_FILES['icon_img_2'], $upload_overrides );
			$movefile18 = wp_handle_upload( $_FILES['icon_img_3'], $upload_overrides );
			
			$movefile19 = wp_handle_upload( $_FILES['icon_img_4'], $upload_overrides );
			$movefile20 = wp_handle_upload( $_FILES['icon_img_5'], $upload_overrides );
			$movefile21 = wp_handle_upload( $_FILES['icon_img_6'], $upload_overrides );
			
			$movefile22 = wp_handle_upload( $_FILES['icon_img_7'], $upload_overrides );
			$movefile23 = wp_handle_upload( $_FILES['icon_img_8'], $upload_overrides );
			$movefile24 = wp_handle_upload( $_FILES['icon_img_9'], $upload_overrides );
			
			$movefile25 = wp_handle_upload( $_FILES['icon_img_10'], $upload_overrides );
			$movefile26 = wp_handle_upload( $_FILES['icon_img_11'], $upload_overrides );
			$movefile27 = wp_handle_upload( $_FILES['icon_img_12'], $upload_overrides );
			
			$movefile25 = wp_handle_upload( $_FILES['icon_img_13'], $upload_overrides );
			$movefile26 = wp_handle_upload( $_FILES['icon_img_14'], $upload_overrides );
			$movefile27 = wp_handle_upload( $_FILES['icon_img_15'], $upload_overrides );
			
			
			$all_pt_content = get_option('all_pt_content');
			//echo "<pre>";
			//print_r($all_pt_content); 
			
			$video_pt = $all_pt_content["video_pt"];
			
			
			$icon_img_1 = $all_pt_content["icon_img_1"];
			$icon_title_1 = $all_pt_content["icon_title_1"];
			
			$icon_img_2 = $all_pt_content["icon_img_2"];
			$icon_title_2 = $all_pt_content["icon_title_2"];
			
			$icon_img_3 = $all_pt_content["icon_img_3"];
			$icon_title_3 = $all_pt_content["icon_title_3"];
			
			$icon_img_4 = $all_pt_content["icon_img_4"];
			$icon_title_4 = $all_pt_content["icon_title_4"];
			
			$icon_img_5 = $all_pt_content["icon_img_5"];
			$icon_title_5 = $all_pt_content["icon_title_5"];
			
			$icon_img_6 = $all_pt_content["icon_img_6"];
			$icon_title_6 = $all_pt_content["icon_title_6"];
			
			$icon_img_7 = $all_pt_content["icon_img_7"];
			$icon_title_7 = $all_pt_content["icon_title_7"];
			
			$icon_img_8 = $all_pt_content["icon_img_8"];
			$icon_title_8 = $all_pt_content["icon_title_8"];
			
			$icon_img_9 = $all_pt_content["icon_img_9"];
			$icon_title_9 = $all_pt_content["icon_title_9"];
			
			$icon_img_10 = $all_pt_content["icon_img_10"];
			$icon_title_10 = $all_pt_content["icon_title_10"];
			
			$icon_img_11 = $all_pt_content["icon_img_11"];
			$icon_title_11 = $all_pt_content["icon_title_11"];
			
			$icon_img_12 = $all_pt_content["icon_img_12"];
			$icon_title_12 = $all_pt_content["icon_title_12"];
			
			$icon_img_13 = $all_pt_content["icon_img_13"];
			$icon_title_13 = $all_pt_content["icon_title_13"];
			
			$icon_img_14 = $all_pt_content["icon_img_14"];
			$icon_title_14 = $all_pt_content["icon_title_14"];
			
			$icon_img_15 = $all_pt_content["icon_img_15"];
			$icon_title_15 = $all_pt_content["icon_title_15"];
		
			
			
		?>
		
		<div id="tabs-3">
			<div>
				<h2>Landing Page Contents</h2>
				<form method="POST" action="">
						<input type="hidden" name="message" value="Homepage Content Save Successfully">
						<table class="form-table">
						<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Video for Personal Trainer Home
									</label> 
								</th>
								<td>
									<input id="video_pt"  type="text" size="36" name="video_pt" value="<?php echo $video_pt; ?>" style="width:60%;height:40px;padding:10px;" /><br />
									<span class="notes">Please add only last youtube video charecters like: xNVxEvhqMPU </span>
								</td>
							</tr>
							<!-- Icon Image 1 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 1
									</label> 
								</th>
								<td>
									<input id="icon_img_1" class="image-upl" type="text" size="36" name="icon_img_1" value="<?php echo esc_url($icon_img_1); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_1_btn" type="button" value="Upload Image" />
									<div id="icon_image_1_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_1); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_1" style="width:60%;padding:10px;" value="<?php echo $icon_title_1; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 2 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 2
									</label> 
								</th>
								<td>
									<input id="icon_img_2" class="image-upl" type="text" size="36" name="icon_img_2" value="<?php echo esc_url($icon_img_2); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_2_btn" type="button" value="Upload Image" />
									<div id="icon_image_2_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_2); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_2" style="width:60%;padding:10px;" value="<?php echo $icon_title_2; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 3 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 3
									</label> 
								</th>
								<td>
									<input id="icon_img_3" class="image-upl" type="text" size="36" name="icon_img_3" value="<?php echo esc_url($icon_img_3); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_3_btn" type="button" value="Upload Image" />
									<div id="icon_image_3_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_3); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_3" style="width:60%;padding:10px;" value="<?php echo $icon_title_3; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 4 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 4
									</label> 
								</th>
								<td>
									<input id="icon_img_4" class="image-upl" type="text" size="36" name="icon_img_4" value="<?php echo esc_url($icon_img_4); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_4_btn" type="button" value="Upload Image" />
									<div id="icon_image_4_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_4); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_4" style="width:60%;padding:10px;" value="<?php echo $icon_title_4; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 5 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 5
									</label> 
								</th>
								<td>
									<input id="icon_img_5" class="image-upl" type="text" size="36" name="icon_img_5" value="<?php echo esc_url($icon_img_5); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_5_btn" type="button" value="Upload Image" />
									<div id="icon_image_5_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_5); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_5" style="width:60%;padding:10px;" value="<?php echo $icon_title_5; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 6 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 6
									</label> 
								</th>
								<td>
									<input id="icon_img_6" class="image-upl" type="text" size="36" name="icon_img_6" value="<?php echo esc_url($icon_img_6); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_6_btn" type="button" value="Upload Image" />
									<div id="icon_image_6_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_6); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_6" style="width:60%;padding:10px;" value="<?php echo $icon_title_6; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 7 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 7
									</label> 
								</th>
								<td>
									<input id="icon_img_7" class="image-upl" type="text" size="36" name="icon_img_7" value="<?php echo esc_url($icon_img_7); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_7_btn" type="button" value="Upload Image" />
									<div id="icon_image_7_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_7); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_7" style="width:60%;padding:10px;" value="<?php echo $icon_title_7; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 8 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 8
									</label> 
								</th>
								<td>
									<input id="icon_img_8" class="image-upl" type="text" size="36" name="icon_img_8" value="<?php echo esc_url($icon_img_8); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_8_btn" type="button" value="Upload Image" />
									<div id="icon_image_8_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_8); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_8" style="width:60%;padding:10px;" value="<?php echo $icon_title_8; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 9 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 9
									</label> 
								</th>
								<td>
									<input id="icon_img_9" class="image-upl" type="text" size="36" name="icon_img_9" value="<?php echo esc_url($icon_img_9); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_9_btn" type="button" value="Upload Image" />
									<div id="icon_image_9_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_9); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_9" style="width:60%;padding:10px;" value="<?php echo $icon_title_9; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 10 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 10
									</label> 
								</th>
								<td>
									<input id="icon_img_10" class="image-upl" type="text" size="36" name="icon_img_10" value="<?php echo esc_url($icon_img_10); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_10_btn" type="button" value="Upload Image" />
									<div id="icon_image_10_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_10); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_10" style="width:60%;padding:10px;" value="<?php echo $icon_title_10; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 11 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 11
									</label> 
								</th>
								<td>
									<input id="icon_img_11" class="image-upl" type="text" size="36" name="icon_img_11" value="<?php echo esc_url($icon_img_11); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_11_btn" type="button" value="Upload Image" />
									<div id="icon_image_11_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_11); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_11" style="width:60%;padding:10px;" value="<?php echo $icon_title_11; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 12 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 12
									</label> 
								</th>
								<td>
									<input id="icon_img_12" class="image-upl" type="text" size="36" name="icon_img_12" value="<?php echo esc_url($icon_img_12); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_12_btn" type="button" value="Upload Image" />
									<div id="icon_image_12_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_12); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_12" style="width:60%;padding:10px;" value="<?php echo $icon_title_12; ?>">
								</td>
							</tr>
							
							
							<!-- Icon Image 13 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 13
									</label> 
								</th>
								<td>
									<input id="icon_img_13" class="image-upl" type="text" size="36" name="icon_img_13" value="<?php echo esc_url($icon_img_13); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_13_btn" type="button" value="Upload Image" />
									<div id="icon_image_13_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_13); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_13" style="width:60%;padding:10px;" value="<?php echo $icon_title_13; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 14 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 14
									</label> 
								</th>
								<td>
									<input id="icon_img_14" class="image-upl" type="text" size="36" name="icon_img_14" value="<?php echo esc_url($icon_img_14); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_14_btn" type="button" value="Upload Image" />
									<div id="icon_image_14_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_14); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_14" style="width:60%;padding:10px;" value="<?php echo $icon_title_14; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 15 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 15
									</label> 
								</th>
								<td>
									<input id="icon_img_15" class="image-upl" type="text" size="36" name="icon_img_15" value="<?php echo esc_url($icon_img_15); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_15_btn" type="button" value="Upload Image" />
									<div id="icon_image_15_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_15); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_15" style="width:60%;padding:10px;" value="<?php echo $icon_title_15; ?>">
								</td>
							</tr>
							
							
						</table>
						<input type="hidden" name="pt_page" value="Y" />
						<p><input type="submit" name="pt_page" value="Save" class="button-primary"/></p>
				</form>
			</div>
		</div>
		
		
		<?php 
		if(isset($_POST['gym_page'])){
			$settingg = array(
				'video_gym'=>stripslashes_deep($_POST["video_gym"]),
				
				'icon_img_1_1'=>esc_attr($_POST["icon_img_1_1"]),
				'icon_title_1_1'=>stripslashes_deep($_POST["icon_title_1_1"]),
				
				'icon_img_2_2'=>esc_attr($_POST["icon_img_2_2"]),
				'icon_title_2_2'=>stripslashes_deep($_POST["icon_title_2_2"]),
				
				'icon_img_3_3'=>esc_attr($_POST["icon_img_3_3"]),
				'icon_title_3_3'=>stripslashes_deep($_POST["icon_title_3_3"]),
				
				'icon_img_4_4'=>esc_attr($_POST["icon_img_4_4"]),
				'icon_title_4_4'=>stripslashes_deep($_POST["icon_title_4_4"]),
				
				'icon_img_5_5'=>esc_attr($_POST["icon_img_5_5"]),
				'icon_title_5_5'=>stripslashes_deep($_POST["icon_title_5_5"]),
				
				'icon_img_6_6'=>esc_attr($_POST["icon_img_6_6"]),
				'icon_title_6_6'=>stripslashes_deep($_POST["icon_title_6_6"]),
				
				'icon_img_7_7'=>esc_attr($_POST["icon_img_7_7"]),
				'icon_title_7_7'=>stripslashes_deep($_POST["icon_title_7_7"]),
				
				'icon_img_8_8'=>esc_attr($_POST["icon_img_8_8"]),
				'icon_title_8_8'=>stripslashes_deep($_POST["icon_title_8_8"]),
				
				'icon_img_9_9'=>esc_attr($_POST["icon_img_9_9"]),
				'icon_title_9_9'=>stripslashes_deep($_POST["icon_title_9_9"]),
				
				'icon_img_10_10'=>esc_attr($_POST["icon_img_10_10"]),
				'icon_title_10_10'=>stripslashes_deep($_POST["icon_title_10_10"]),
				
				'icon_img_11_11'=>esc_attr($_POST["icon_img_11_11"]),
				'icon_title_11_11'=>stripslashes_deep($_POST["icon_title_11_11"]),
				
				'icon_img_12_12'=>esc_attr($_POST["icon_img_12_12"]),
				'icon_title_12_12'=>stripslashes_deep($_POST["icon_title_12_12"]),
				
				'icon_img_13_13'=>esc_attr($_POST["icon_img_13_13"]),
				'icon_title_13_13'=>stripslashes_deep($_POST["icon_title_13_13"]),
				
				'icon_img_14_14'=>esc_attr($_POST["icon_img_14_14"]),
				'icon_title_14_14'=>stripslashes_deep($_POST["icon_title_14_14"]),
				
				'icon_img_15_15'=>esc_attr($_POST["icon_img_15_15"]),
				'icon_title_15_15'=>stripslashes_deep($_POST["icon_title_15_15"]),
			);
			
			update_option("all_gym_content", $settingg);
			
		}
		
		if ( ! function_exists( 'wp_handle_upload' ) )
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );	
		
			$upload_overrides = array( 'test_form' => false );

			$movefile28 = wp_handle_upload( $_FILES['icon_img_1_1'], $upload_overrides );
			$movefile29 = wp_handle_upload( $_FILES['icon_img_2_2'], $upload_overrides );
			$movefile30 = wp_handle_upload( $_FILES['icon_img_3_3'], $upload_overrides );
			
			$movefile31 = wp_handle_upload( $_FILES['icon_img_4_4'], $upload_overrides );
			$movefile32 = wp_handle_upload( $_FILES['icon_img_5_5'], $upload_overrides );
			$movefile33 = wp_handle_upload( $_FILES['icon_img_6_6'], $upload_overrides );
			
			$movefile34 = wp_handle_upload( $_FILES['icon_img_7_7'], $upload_overrides );
			$movefile35 = wp_handle_upload( $_FILES['icon_img_8_8'], $upload_overrides );
			$movefile36 = wp_handle_upload( $_FILES['icon_img_9_9'], $upload_overrides );
			
			$movefile37 = wp_handle_upload( $_FILES['icon_img_10_10'], $upload_overrides );
			$movefile38 = wp_handle_upload( $_FILES['icon_img_11_11'], $upload_overrides );
			$movefile39 = wp_handle_upload( $_FILES['icon_img_12_12'], $upload_overrides );
			
			$movefile40 = wp_handle_upload( $_FILES['icon_img_13_13'], $upload_overrides );
			$movefile41 = wp_handle_upload( $_FILES['icon_img_14_14'], $upload_overrides );
			$movefile42 = wp_handle_upload( $_FILES['icon_img_15_15'], $upload_overrides );
			
			
			$all_gym_content = get_option('all_gym_content');
			//echo "<pre>";
			//print_r($all_gym_content); 
			
			$video_gym = $all_gym_content["video_gym"];
				
			$icon_img_1_1 = $all_gym_content["icon_img_1_1"];
			$icon_title_1_1 = $all_gym_content["icon_title_1_1"];
			
			$icon_img_2_2 = $all_gym_content["icon_img_2_2"];
			$icon_title_2_2 = $all_gym_content["icon_title_2_2"];
			
			$icon_img_3_3 = $all_gym_content["icon_img_3_3"];
			$icon_title_3_3 = $all_gym_content["icon_title_3_3"];
			
			$icon_img_4_4 = $all_gym_content["icon_img_4_4"];
			$icon_title_4_4 = $all_gym_content["icon_title_4_4"];
			
			$icon_img_5_5 = $all_gym_content["icon_img_5_5"];
			$icon_title_5_5 = $all_gym_content["icon_title_5_5"];
			
			$icon_img_6_6 = $all_gym_content["icon_img_6_6"];
			$icon_title_6_6 = $all_gym_content["icon_title_6_6"];
			
			$icon_img_7_7 = $all_gym_content["icon_img_7_7"];
			$icon_title_7_7 = $all_gym_content["icon_title_7_7"];
			
			$icon_img_8_8 = $all_gym_content["icon_img_8_8"];
			$icon_title_8_8 = $all_gym_content["icon_title_8_8"];
			
			$icon_img_9_9 = $all_gym_content["icon_img_9_9"];
			$icon_title_9_9 = $all_gym_content["icon_title_9_9"];
			
			$icon_img_10_10 = $all_gym_content["icon_img_10_10"];
			$icon_title_10_10 = $all_gym_content["icon_title_10_10"];
			
			$icon_img_11_11 = $all_gym_content["icon_img_11_11"];
			$icon_title_11_11 = $all_gym_content["icon_title_11_11"];
			
			$icon_img_12_12 = $all_gym_content["icon_img_12_12"];
			$icon_title_12_12 = $all_gym_content["icon_title_12_12"];
			
			$icon_img_13_13 = $all_gym_content["icon_img_13_13"];
			$icon_title_13_13 = $all_gym_content["icon_title_13_13"];
			
			$icon_img_14_14 = $all_gym_content["icon_img_14_14"];
			$icon_title_14_14 = $all_gym_content["icon_title_14_14"];
			
			$icon_img_15_15 = $all_gym_content["icon_img_15_15"];
			$icon_title_15_15 = $all_gym_content["icon_title_15_15"];
		
			
			
		?>
		
		<div id="tabs-4">
			<div>
				<h2>Landing Page Contents</h2>
				<form method="POST" action="">
						<input type="hidden" name="message" value="Homepage Content Save Successfully">
						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Video for Personal gym
									</label> 
								</th>
								<td>
									<input id="video_gym"  type="text" size="36" name="video_gym" value="<?php echo $video_gym; ?>" style="width:60%;height:40px;padding:10px;" /><br />
									<span class="notes">Please add only last youtube video charecters like: xNVxEvhqMPU </span>
								</td>
							</tr>
							<!-- Icon Image 1 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 1
									</label> 
								</th>
								<td>
									<input id="icon_img_1_1" class="image-upl" type="text" size="36" name="icon_img_1_1" value="<?php echo esc_url($icon_img_1_1); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_1_1_btn" type="button" value="Upload Image" />
									<div id="icon_image_1_1_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_1_1); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_1_1" style="width:60%;padding:10px;" value="<?php echo $icon_title_1_1; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 2 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 2
									</label> 
								</th>
								<td>
									<input id="icon_img_2_2" class="image-upl" type="text" size="36" name="icon_img_2_2" value="<?php echo esc_url($icon_img_2_2); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_2_2_btn" type="button" value="Upload Image" />
									<div id="icon_image_2_2_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_2_2); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_2_2" style="width:60%;padding:10px;" value="<?php echo $icon_title_2_2; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 3 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 3
									</label> 
								</th>
								<td>
									<input id="icon_img_3_3" class="image-upl" type="text" size="36" name="icon_img_3_3" value="<?php echo esc_url($icon_img_3_3); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_3_3_btn" type="button" value="Upload Image" />
									<div id="icon_image_3_3_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_3_3); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_3_3" style="width:60%;padding:10px;" value="<?php echo $icon_title_3_3; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 4 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 4
									</label> 
								</th>
								<td>
									<input id="icon_img_4_4" class="image-upl" type="text" size="36" name="icon_img_4_4" value="<?php echo esc_url($icon_img_4_4); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_4_4_btn" type="button" value="Upload Image" />
									<div id="icon_image_4_4_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_4_4); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_4_4" style="width:60%;padding:10px;" value="<?php echo $icon_title_4_4; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 5 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 5
									</label> 
								</th>
								<td>
									<input id="icon_img_5_5" class="image-upl" type="text" size="36" name="icon_img_5_5" value="<?php echo esc_url($icon_img_5_5); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_5_5_btn" type="button" value="Upload Image" />
									<div id="icon_image_5_5_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_5); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_5_5" style="width:60%;padding:10px;" value="<?php echo $icon_title_5_5; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 6 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 6
									</label> 
								</th>
								<td>
									<input id="icon_img_6_6" class="image-upl" type="text" size="36" name="icon_img_6_6" value="<?php echo esc_url($icon_img_6_6); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_6_6_btn" type="button" value="Upload Image" />
									<div id="icon_image_6_6_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_6_6); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_6_6" style="width:60%;padding:10px;" value="<?php echo $icon_title_6_6; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 7 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 7
									</label> 
								</th>
								<td>
									<input id="icon_img_7_7" class="image-upl" type="text" size="36" name="icon_img_7_7" value="<?php echo esc_url($icon_img_7_7); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_7_7_btn" type="button" value="Upload Image" />
									<div id="icon_image_7_7_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_7_7); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_7_7" style="width:60%;padding:10px;" value="<?php echo $icon_title_7_7; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 8 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 8
									</label> 
								</th>
								<td>
									<input id="icon_img_8_8" class="image-upl" type="text" size="36" name="icon_img_8_8" value="<?php echo esc_url($icon_img_8_8); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_8_8_btn" type="button" value="Upload Image" />
									<div id="icon_image_8_8_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_8_8); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_8_8" style="width:60%;padding:10px;" value="<?php echo $icon_title_8_8; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 9 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 9
									</label> 
								</th>
								<td>
									<input id="icon_img_9_9" class="image-upl" type="text" size="36" name="icon_img_9_9" value="<?php echo esc_url($icon_img_9_9); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_9_9_btn" type="button" value="Upload Image" />
									<div id="icon_image_9_9_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_9_9); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_9_9" style="width:60%;padding:10px;" value="<?php echo $icon_title_9_9; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 10 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 10
									</label> 
								</th>
								<td>
									<input id="icon_img_10_10" class="image-upl" type="text" size="36" name="icon_img_10_10" value="<?php echo esc_url($icon_img_10_10); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_10_10_btn" type="button" value="Upload Image" />
									<div id="icon_image_10_10_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_10_10); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_10_10" style="width:60%;padding:10px;" value="<?php echo $icon_title_10_10; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 11 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 11
									</label> 
								</th>
								<td>
									<input id="icon_img_11_11" class="image-upl" type="text" size="36" name="icon_img_11_11" value="<?php echo esc_url($icon_img_11_11); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_11_11_btn" type="button" value="Upload Image" />
									<div id="icon_image_11_11_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_11_11); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_11_11" style="width:60%;padding:10px;" value="<?php echo $icon_title_11_11; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 12 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 12
									</label> 
								</th>
								<td>
									<input id="icon_img_12_12" class="image-upl" type="text" size="36" name="icon_img_12_12" value="<?php echo esc_url($icon_img_12_12); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_12_12_btn" type="button" value="Upload Image" />
									<div id="icon_image_12_12_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_12_12); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_12_12" style="width:60%;padding:10px;" value="<?php echo $icon_title_12_12; ?>">
								</td>
							</tr>
							
							
							<!-- Icon Image 13 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 13
									</label> 
								</th>
								<td>
									<input id="icon_img_13_13" class="image-upl" type="text" size="36" name="icon_img_13_13" value="<?php echo esc_url($icon_img_13_13); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_13_13_btn" type="button" value="Upload Image" />
									<div id="icon_image_13_13_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_13_13); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_13_13" style="width:60%;padding:10px;" value="<?php echo $icon_title_13_13; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 14 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 14
									</label> 
								</th>
								<td>
									<input id="icon_img_14_14" class="image-upl" type="text" size="36" name="icon_img_14_14" value="<?php echo esc_url($icon_img_14_14); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_14_14_btn" type="button" value="Upload Image" />
									<div id="icon_image_14_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_14_14); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_14_14" style="width:60%;padding:10px;" value="<?php echo $icon_title_14_14; ?>">
								</td>
							</tr>
							
							<!-- Icon Image 15 -->
							<tr>
								<th scope="row">
									<label for="mm_spamer_notification">
									   Icon Image 15
									</label> 
								</th>
								<td>
									<input id="icon_img_15_15" class="image-upl" type="text" size="36" name="icon_img_15_15" value="<?php echo esc_url($icon_img_15_15); ?>" style="width:60%;height:40px;padding:10px;" />
									<input id="icon_image_15_15_btn" type="button" value="Upload Image" />
									<div id="icon_image_15_15_preview" style="min-height: 100px; max-width: 250px;">
										<img style="max-width:100%;" src="<?php echo esc_url($icon_img_15_15); ?>" />
									</div>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
									<label for="mm_spamer_threshold">
									  Description
									</label> 
								</th>
								<td>
									<input type="text" name="icon_title_15_15" style="width:60%;padding:10px;" value="<?php echo $icon_title_15_15; ?>">
								</td>
							</tr>
							
							
						</table>
						<input type="hidden" name="gym_page" value="Y" />
						<p><input type="submit" name="gym_page" value="Save" class="button-primary"/></p>
				</form>
			</div>
		</div>
	</div>
	
<?php	
}