<?php do_action( 'bp_before_directory_members_page' ); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHKB9Y1_R9tuSqYJMSqaAIFsFGw9qMhm8&libraries=places"></script>
<script>   
function initialize(){

   autocomplete = new google.maps.places.Autocomplete((document.getElementById('location')), {types: ['(regions)']});
   google.maps.event.addListener(autocomplete, 'place_changed', function() {});
 
}
             
google.maps.event.addDomListener(window, 'load', initialize);
	  
</script>
<style>
@media (max-width:990px){
	#members_search .filters-container{
		height: 390px;	
	}
}
</style>

<div id="buddypress">

    <?php do_action( 'bp_before_directory_members' ); ?>

    <h3 class="template-title"><?php buddyboss_page_title(); ?></h3>
       <?php do_action( 'bp_before_directory_members_content' ); ?>

    <div class="filters">
        <div class="col-md-12 text-center"> 
            <h4 class="template-subtitle" style="color:#000;">Search filters:</h4>
            <form id="members_search" method="get" action="">
                <input type="hidden" name="search_members" value="1">
                
                <div class="form-group col-md-12 text-center">
                    <label>Choose User Type:</label>
                    <div class="checkbox">
                        <label>
                            <input name="usertype" type="radio" value="standard" <?php checked( $_GET['usertype'], 'standard' ); ?> > Standard Users
                        </label>
                        <label>
                            <input name="usertype" type="radio" value="pt" <?php checked( $_GET['usertype'], 'pt' ); ?> > Personal Trainers
                        </label>
                        <label>
                            <input name="usertype" type="radio" value="gym" <?php checked( $_GET['usertype'], 'gym' ); ?> > GYM Users
                        </label>
                        <label>
                            <input name="usertype" type="radio" value="all" <?php checked( $_GET['usertype'], 'all' ); ?> > All Users
                        </label>
                    </div>
                    <div id="error-usertype"></div>
                </div>

                <div class="filters-container">
                    <div class="form-group col-md-4 col-xs-12">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control search-group" id="firstname" name="firstname" value="<?php echo (!empty($_GET['firstname'])) ? $_GET['firstname'] :'';?>">
                        <div id="error-firstname"></div>
                    </div>

                    <div class="form-group col-md-4 col-xs-12">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control search-group" id="lastname" name="lastname" value="<?php echo (!empty($_GET['lastname'])) ? $_GET['lastname'] :'';?>">
                        <div id="error-lastname"></div>
                    </div>

                    <div class="form-group col-md-4 col-xs-12">
                        <label for="lastname">GYM Name</label>
                        <input type="text" class="form-control search-group" id="gymname" name="gymname" value="<?php echo (!empty($_GET['gymname'])) ? $_GET['gymname'] :'';?>">
                        <div id="error-gymname"></div>
                    </div>
                    
                    <div class=" col-md-6 col-xs-12">
                        <div class="form-group col-md-6 col-xs-12 pull-right">
                            <label for="location">Location</label>
                            <input type="text" class="form-control search-group" id="location" name="location" value="<?php echo (!empty($_GET['location'])) ? $_GET['location'] :'';?>">
                            <div id="error-location"></div>
                        </div>
                    </div>

                    <div class=" col-md-6 col-xs-12">
                        <div class="form-group col-md-6 col-xs-12 text-center">
                            <label for="specialization">Specialization</label>

                            <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                                <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
                                    <?php if(bp_get_the_profile_field_name()=='Specialization'):?>
                                       <select data-placeholder="Choose a specialization..." class="form-control search-group" id="specialization" name="specialization">
                                            <option value="">&#151;</option>
                                            <?php echo (bp_the_profile_field_options());?>
                                        </select>
                                    <?php endif;?>
                                <?php endwhile; ?>
                            <?php endwhile; endif; endif; ?>
                            <div id="error-specialization"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 text-center">
                    <button class="btn" id="submit_members_search" type="submit">Search</button>
                    <button class="btn danger" id="clear_members_search" type="button">Clear</button>
                </div>
            </form>
        </div>
        <div class="clear"></div>
        <hr>

<?php
if(isset($_REQUEST['search_members']) && $_REQUEST['search_members']!=''){
?>
<script type="text/javascript">


$.fn.scrollBottom = function() { 
    return $(window).height() - 500; 
};
$('html, body').animate({ scrollTop: $(document).height() }, 1200);
//$("#custom-members-order-select").scrollTop();
</script>
<?php
}
?>



        <div class="col-md-12 text-center">
            <p>If you can't find someone, please</p>
            <p><a class="btn inverse" href="<?php echo bp_core_get_user_domain(get_current_user_id()).'invite-anyone'?>"><i class="fa fa-user-plus fa-lg"></i>&nbsp;Invite New Members</a></p>
            <p>or Invite your Friends</p>
            <?php echo do_shortcode('[SociaPlugin google=1 twitter=1 email=1]'); ?>
			<?php echo do_shortcode('[fib appid="1521953394504746"]'); ?>
			
        </div>
        <div class="clearfix"></div>
        <hr>
        
        <div class="col-md-12 col-xs-12 text-right members-order-by"> 
            <label class="text-right"><?php _e( 'Order By:', 'boss' ); ?></label>
           
                       <select id="custom-members-order-select">
                            <option value="active" onchange="desending_member()"><?php _e( 'Last Active', 'boss' ); ?></option>
                            <option value="newest" onchange="desending_member()"><?php _e( 'Newest Registered', 'boss' ); ?></option>
                            <option value="alphabetical" onchange="desending_member()"><?php _e( 'Alphabetical', 'boss' ); ?></option>
                            <?php 
								function desending_member(){
									do_action( 'bp_members_directory_order_options' ); 
								}?>
                        </select> 
                    
        </div>
    </div>

    <?php do_action( 'bp_before_directory_members_tabs' ); ?>

       <form action="" method="post" id="members-directory-form" class="dir-form">
        <?php if( !isset($_GET['search_members']) || (isset($_GET['search_members']) && isset($_GET['reset_filters'])) ):?>
            <div class="item-list-tabs" role="navigation">
                <ul id="custom-membertype-tabs">
                    <?php do_action( 'bp_members_directory_member_types' ); ?>
                    <?php do_action( 'bp_members_directory_member_sub_types' ); ?>
                </ul>
            </div><!-- .item-list-tabs -->
        <?php endif;?>
        <div id="members-dir-list" class="members dir-list">
            <?php bp_get_template_part( 'members/members-loop' ); ?>
        </div><!-- #members-dir-list -->

        <?php do_action( 'bp_directory_members_content' ); ?>

        <?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

        <?php do_action( 'bp_after_directory_members_content' ); ?>

    </form><!-- #members-directory-form -->

    <?php do_action( 'bp_after_directory_members' ); ?>

</div><!-- #buddypress -->

<?php do_action( 'bp_after_directory_members_page' ); ?>

