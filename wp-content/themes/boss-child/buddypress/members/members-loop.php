<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package Boss
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php

	$members_ids_query = '';
	if( bp_current_component()=='members' ){

		parse_str($_SERVER['HTTP_REFERER'], $referer_vars);
		
		if(!$_GET['usertype']){
		
			$_GET['usertype'] = 'all';
		}

		if(isset($_GET['search_members']) && array_key_exists("usertype", $_GET)){

            $search_members_ids = get_search_members_ids($_GET['usertype'], $_GET['firstname'], $_GET['lastname'], $_GET['gymname'], $_GET['location'], $_GET['specialization']);
			$members_ids_query = (is_array($search_members_ids) && count($search_members_ids)>0 ) ? '&include='.implode(',',$search_members_ids) : '&include=0';
			$search_str = 'Search results for '.(($_GET['usertype']=='standard') ? 'Standard Users' : (($_GET['usertype']=='pt') ? 'Personal Trainers' : 'GYM Users'));
			if($_GET['usertype'] == 'all'){
				$search_str = 'Search results for all';
			}
            $members_tab = $_GET['usertype'];
            $members_ids_query .= '&scope='.$members_tab;

		}elseif( empty($_GET) && array_key_exists("usertype", $referer_vars) ){
			
            $search_members_ids = get_search_members_ids($referer_vars['usertype'], $referer_vars['firstname'], $referer_vars['lastname'], $referer_vars['gymname'], $referer_vars['location'], $referer_vars['specialization']);
			$members_ids_query = (is_array($search_members_ids) && count($search_members_ids)>0 ) ? '&include='.implode(',',$search_members_ids) : '&include=0';
	        $search_str = 'Search results for '.(($referer_vars['usertype']=='standard') ? 'Standard Users' : (($referer_vars['usertype']=='pt') ? 'Personal Trainers' : 'GYM Users'));
            $members_tab = $referer_vars['usertype'];
            $members_ids_query .= '&scope='.$members_tab;

		}elseif(empty($_GET) && array_key_exists("reset_filters", parse_str($_SERVER['HTTP_REFERER'], $search_vars))){
			$members_ids_query = '';
            $members_tab = bp_get_member_type(get_current_user_id());
		}
	}
?>

    <?php if($search_str):?>

        <h3 class="template-subtitle"><?php echo $search_str;?></h3>
        <input id="search-order-filter-scope" value="<?php echo $members_tab;?>" type="hidden">

    <?php endif;?>


    
<?php if ( bp_has_members( bp_ajax_querystring( 'members' ).$members_ids_query ) ) : ?>

	<?php do_action( 'bp_before_directory_members_list' ); ?>
	<ul id="members-list-custom" class="item-list" role="main">
    <script type="text/javascript">
		jQuery(document).ready(function(){
            jQuery('.varified-mark').popover();
            jQuery('[disabled="false"], [disabled=""]').removeAttr('disabled');
        });
    </script>
	<?php while ( bp_members() ) : bp_the_member(); ?>
		
		<?php
			$request_sender_type = bp_get_member_type(bp_loggedin_user_id());
			$request_reciver_type = bp_get_member_type(bp_get_member_user_id());
			$is_friend = friends_check_friendship(bp_loggedin_user_id(), bp_get_member_user_id());
		?>

		<li>
			<div class="item-avatar col-md-2 col-xs-12">
				<a href="<?php bp_member_permalink(); ?>">
					<?php bp_member_avatar('type=full&width=256&height=256'); ?>
				</a>
			</div>


			<div class="item col-md-4 col-xs-12">
				<div class="item-title">
					<a href="<?php bp_member_permalink(); ?>"><?php echo get_fullname(bp_get_member_user_id());?></a>
				</div>

                <?php
                	$showing = null;
	                //if bp-followers activated then show it.
	                if(function_exists("bp_follow_add_follow_button")) {
	                    $showing = "follows";
	                    $followers  = bp_follow_total_follow_counts(array("user_id"=>bp_displayed_user_id()));
	                } elseif (function_exists("bp_add_friend_button")) {
	                    $showing = "friends";
	                }
                ?>

				<div class="item-meta">
					<p class="member-loop-details">
						<strong>
							<?php 
								switch ($request_reciver_type) {
								 	case 'standard':
								 		echo 'Standard User';
								 		break;
								 	case 'pt':
								 		echo 'Personal Trainer';
								 		break;
								 	case 'gym':
								 		echo 'GYM User';
								 	break;
								}
							?>
						</strong>
						<span>
							<?php
								echo ($request_reciver_type != 'standard') ? ': '.implode(', ', bp_get_profile_field_data('field=12&user_id='.bp_get_member_user_id())) : '';
							?>
						</span>
					</p>
					<p class="member-loop-details">
						<strong>Location:</strong>
						<span>
							<?php echo bp_get_profile_field_data('field=Location&user_id='.bp_get_member_user_id());?>
						</span>
					</p>
					<p class="member-loop-details">
						<strong>Phone:</strong>
						<span>
							<?php echo bp_get_profile_field_data('field=Phone&user_id='.bp_get_member_user_id());?>
						</span>
					</p>
					<div class="activity">
						<?php bp_member_last_active(); ?>
					</div>
					
					<?php if($showing == "friends"): ?>
                    <span class="count"><?php echo friends_get_total_friend_count(bp_get_member_user_id()); ?></span>
                    	<?php if ( friends_get_total_friend_count(bp_get_member_user_id()) > 1 ) { ?>
                    		<span><?php _e("Friends","boss"); ?></span>
                        <?php } else { ?>
                        	<span><?php _e("Friend","boss"); ?></span>
                        <?php } ?>
                    <?php endif; ?>

                    <?php if($showing == "follows"): ?>
                    <span class="count"><?php $followers = bp_follow_total_follow_counts(array("user_id"=>bp_get_member_user_id())); echo $followers["followers"]; ?></span><span><?php _e("Followers","boss"); ?></span>
                    <?php endif; ?>
				</div>

				<div class="item-desc">
					<p>
						<?php if ( bp_get_member_latest_update() ) : ?>
							<?php bp_member_latest_update( array( 'view_link' => false ) ); ?>
						<?php endif; ?>
					</p>
                    <div class="clearfix"></div>
                    <?php if( $request_reciver_type == 'gym' && ($request_sender_type == 'standard' || $request_sender_type == 'pt' ) ): ?>
                        <a class="btn inverse" href="<?php echo home_url().'/timetables?show_gym_timetables=1&gym_id='.bp_get_member_user_id();?>">GYM Timetables</a>
                        &nbsp;<a class="btn inverse" href="<?php echo home_url().'/gym-trainers?show_gym_trainers=1&gym_id='.bp_get_member_user_id();?>">GYM Trainers</a>
                        &nbsp;<a class="btn inverse" href="<?php echo home_url().'/gym-noticeboard?show_gym_noticeboard=1&gym_id='.bp_get_member_user_id();?>">GYM Noticeboard</a>
                    <?php endif;?>
				</div>

				<?php do_action( 'bp_directory_members_item' ); ?>
			</div>

			<div class="action col-md-6 col-xs-12">
            			<?php do_action( 'bp_directory_members_actions' ); ?>
				<?php print_connection_status();?>
                		<?php print_members_rejected_count(); ?>
			</div>

			<div class="clear"></div>
		</li>
	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">
		<div class="pag-count" id="member-dir-count-bottom"><?php bp_members_pagination_count(); ?></div>
		<div class="pagination-links" id="member-dir-pag-bottom"><?php bp_members_pagination_links(); ?> </div>
	</div>

    <?php if( bp_get_member_type(bp_loggedin_user_id()) == 'standard' ): ?>
        <div id="parq-modal">
            <?php include_once get_stylesheet_directory().'/templates/parq-modal.php';?>
        </div>
    <?php endif;?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'boss' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>

<script type="text/javascript">
  
    jQuery(document).ready(function(){

        //send connection request
        jQuery('.connection-request').on('click',function(){
            var _this = jQuery(this);
            var reciver = _this.data('reciver');
            _this.addClass('loading').attr('disabled','disabled');

            <?php if( bp_get_member_type(bp_loggedin_user_id()) == 'standard' ): ?>
                jQuery.magnificPopup.instance.popupsCache = {};    
                var modal = jQuery.magnificPopup.instance;

                modal.open({
                    items: {
                        src: '#parq-modal',
                        type: 'inline'
                    },
                    //modal: true,
                    closeBtnInside:true,
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: true,
                    removalDelay: 600,
                    callbacks:{
                        beforeOpen: function() {},
                        close: function() {
                            _this.removeClass('loading').attr('disabled',false);
                        },
                        open: function() {

                            jQuery('[name="parq_address"]').geocomplete({componentRestrictions: {country: ''}});


                            jQuery('[name="parq_dob"]').datepicker({
                                changeYear: true,
                                dateFormat: 'MM d, yy'
                            });

                            jQuery("#parq-mobile").intlTelInput({
                                initialCountry: "auto",
                                geoIpLookup: function(callback) {
                                    jQuery.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                                        var countryCode = (resp && resp.country) ? resp.country : "";
                                        callback(countryCode);
                                    });
                                }
                            });

                            jQuery.validator.addMethod("nowhitespace", function(value, element)
                            { return jQuery.trim(value) && value != ""; }, "No space please and don't leave it empty");

                            jQuery("#parq-modal-form").validate({
                                ignore: "",
                                errorPlacement: function(error, element){
                                    console.log(error);
                                        var name = element.attr("name");
                                        jQuery('#error-' + name).append(error);
                                    },
                                invalidHandler: function(form, validator) {
                                    if (!validator.numberOfInvalids())
                                        return;
                                    jQuery('html, body').animate({
                                        scrollTop: jQuery(validator.errorList[0].element).offset().top-100
                                    }, 600);
                                }
                            });


                            jQuery(document).on('change','[name="question_7"]',function(){

                                if( jQuery(this).val() == 'yes' )
                                    jQuery('.yes_for_7').show();
                                else
                                    jQuery('.yes_for_7').hide();

                            });


                            jQuery(document).on('change','.parq_question',function(){

                                var checked = jQuery('.parq_question:checked').length;
                                if( checked == 7 ){

                                    var no_answers = 0;

                                    jQuery('.parq_question:checked').each(function(i,e){
                                        
                                        if( jQuery(e).val() == 'no' )
                                            no_answers++
                                    });

                                    if( no_answers < 7 ){
                                        jQuery('.no_for_answers_agreement').hide();
                                        jQuery('[name="agreement_no"]').attr('required',false);
                                        jQuery('.yes_for_answers_agreement').show();
                                        jQuery('[name="agreement_yes"]').attr('required',true);
                                        jQuery('[name="agreement_yes_2"]').attr('required',true);
                                    }
                                    else{
                                        jQuery('.yes_for_answers_agreement').hide();
                                        jQuery('[name="agreement_yes"]').attr('required',false); 
                                        jQuery('[name="agreement_yes_2"]').attr('required',false);   
                                        jQuery('.no_for_answers_agreement').show();
                                        jQuery('[name="agreement_no"]').attr('required',true);   
                                    }
                                }
                                
                            });

                            jQuery(document).on('click','[name="save_request_and_parq"]',function(event){
                                event.preventDefault();
                                if(jQuery('#parq-modal-form').valid()){
                                    jQuery.ajax({
                                        type: "POST",
                                        dataType: "JSON",
                                        url: mirrorMuscles.ajaxPath,
                                        data: {action: 'connection-request', reciver: reciver, data: jQuery('#parq-modal-form').serialize()},
                                        success: function(callback) {

                                            modal.close();
                                            if(callback.success)
                                                window.location = callback.success;
                                            else
                                                window.location.reload();
                                        }
                                    });        
                                }
                            });
                            
                        },
                        afterClose: function(){}
                    }
                   
                }); 
            <?php else: ?>
                jQuery.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: mirrorMuscles.ajaxPath,
                    data: {action: 'connection-request', reciver: reciver},
                    success: function(callback) {
                        if(callback.success)
                            //window.location = callback.success;
							window.location.reload();
                        else
                            window.location.reload();
                    }
                });
            <?php endif;?>
            
        });

        jQuery('.connection-cancel, .connection-reject, .connection-accept, .connection-disconnect').on('click',function(){
            var _this = jQuery(this);
            var connection = _this.data('connection');
            jQuery(this).addClass('loading').attr('disabled','disabled');

            if(_this.hasClass('connection-cancel'))
                var action = 'connection-cancel'
            else if(_this.hasClass('connection-reject'))
                var action = 'connection-reject'
            else if(_this.hasClass('connection-accept'))
                var action = 'connection-accept'
            else if(_this.hasClass('connection-disconnect'))
                var action = 'connection-disconnect'
            
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: mirrorMuscles.ajaxPath,
                data: {action: action, connection:connection},
                success: function(callback) {
                    if(callback.success)
                        window.location.reload();
                    else
                        window.location.reload();
                }
            });
        });

        //SEARCH FUNCTIONAL
        
        var switch_usertype = function(type){
            jQuery('div[id^="error-"]').text('');
            switch(type){
                case 'standard':
                    jQuery('#firstname').attr('disabled',false);
                    jQuery('#lastname').attr('disabled',false);
                    jQuery('#gymname').attr('disabled','disabled');
                    jQuery('#specialization option').attr('selected',false);
                    jQuery('#specialization').attr('disabled','disabled').trigger("chosen:updated");
                break;
                case 'pt':
                    jQuery('#firstname').attr('disabled',false);
                    jQuery('#lastname').attr('disabled',false);
                    jQuery('#gymname').attr('disabled','disabled');
                    jQuery('#specialization option:first').attr('selected','selected');
                    jQuery('#specialization').attr('disabled',false).trigger("chosen:updated");
                break;
                case 'gym':
                    jQuery('#firstname').attr('disabled','disabled');
                    jQuery('#lastname').attr('disabled','disabled');
                    jQuery('#gymname').attr('disabled',false);
                    jQuery('#specialization option:first').attr('selected','selected');
                    jQuery('#specialization').attr('disabled',false).trigger("chosen:updated");
                break;
				case 'all':
                    jQuery('#firstname').attr('disabled',false);
                    jQuery('#lastname').attr('disabled',false);
                    jQuery('#gymname').attr('disabled',false);
                    jQuery('#specialization option:first').attr('selected','selected');
                    jQuery('#specialization').attr('disabled',false).trigger("chosen:updated");
                break;
            }   
        }
    
        var type = jQuery('input[name="usertype"]:checked').val();
        switch_usertype(type);

        jQuery(document).on('change','input[name="usertype"]',function(){
            switch_usertype(jQuery(this).val());
        });

        jQuery("#specialization").chosen({no_results_text: "No matches",disable_search: true}); 
        var specialization = decodeURIComponent((new RegExp('[?|&]specialization=' + '([^&;]+?)(&|#|;|jQuery)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null;
        jQuery('#specialization').val(specialization);
        jQuery('#specialization').trigger("chosen:updated");
        
        jQuery('#location').geocomplete({componentRestrictions: {country: ''}}).attr('placeholder','');

        //for validation of chosen.js select
        jQuery.validator.setDefaults({ ignore: ":hidden:not(select)" })

        
        jQuery("#members_search").validate({
            rules: {
                firstname: {
                  require_from_group: [1, ".search-group"]
                },
                lastname: {
                  require_from_group: [1, ".search-group"]
                },
                gymname: {
                  require_from_group: [1, ".search-group"]
                },
                location: {
                  require_from_group: [1, ".search-group"]
                },
                specialization: {
                  require_from_group: [1, ".search-group"]
                }
            },
            messages: {
            },
            errorPlacement: function(error, element){
                
                if(!element.attr('disabled')){
                    var name = element.attr("name");
                    jQuery('#error-' + name).append(error);
                }
            },
            focusInvalid: false,
            invalidHandler: function(form, validator) {
                if (!validator.numberOfInvalids())
                    return;
                jQuery('html, body').animate({
                 	scrollTop: jQuery(validator.errorList[0].element).offset().top-100
                }, 600);
            }
        });

        jQuery(document).on('click','#submit_members_search', function(event){
            event.preventDefault();
            if(jQuery('[name="usertype"]').valid()){
                if(jQuery("#members_search").valid()){
                    jQuery(this).addClass('loading');
                    jQuery('#members_search').submit();
					
                }
            }else{
				
                jQuery('html, body').animate({
                    scrollTop: jQuery('[name="usertype"]').offset().top-100
                }, 600);
            }
        });

        jQuery(document).on('click','#clear_members_search', function(){
            jQuery(this).addClass('loading');
            location.href=location.href.replace(/\?.+/, '');
            location.href = '/members/?search_members=1&reset_filters=1';
        });

    });
	
/*	jQuery(".btn.default").click(function() {
    	setTimeout(function() {
        	window.location.reload();
    	}, 5000);
	});
	
	jQuery(".add").click(function() {
    	setTimeout(function() {
        	window.location.reload();
    	}, 5000);
	});
*/
</script>


