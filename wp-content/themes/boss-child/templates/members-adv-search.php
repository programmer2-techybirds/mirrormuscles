<?php
	/*
	Template Name: Members Advanced Search
	*/
	
	get_header();
	$userid = get_current_user_id();
	$member_type = bp_get_member_type($userid);
?>
<?php if(is_user_logged_in() && !current_user_can( 'manage_options' ) ): ?>
<div id="buddypress" class="template-members-advanced-search">
    <div class="site-content">
        <input type="hidden" id="current_user" value="<?php _e($userid);?>">
        <h3 class="template-title">Members Advanced Search</h3>
        <div class="adv_search_container">
            <div class="filters adv_search_filters">
                <form id="adv_search_form">
                    <div class="form-group col-md-2">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control search-group" id="firstname" name="firstname">
                        <div id="error-firstname"></div>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control search-group" id="lastname" name="lastname">
                        <div id="error-lastname"></div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="location">Location</label>
                        <input type="text" class="form-control search-group" id="location" name="location">
                        <div id="error-location"></div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="speciaization" style="margin-bottom: 1px; white-space: nowrap;overflow: hidden;-o-text-overflow: ellipsis;-ms-text-overflow: ellipsis;text-overflow: ellipsis;">Specialization for Trainer/GYM</label>
                        <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                            <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>
                                <?php if(bp_get_the_profile_field_input_name()=='field_13'):?>
                                    <select class="form-control" id="speciaization" name="speciaization">
                                        <?php echo (bp_the_profile_field_options());?>
                                    </select>
                                <?php endif;?>
                            <?php endwhile; ?>
                        <?php endwhile; endif; endif; ?>
                    </div>
                    <div class="col-md-2">
                        <button id="submit_adv_member_search" type="submit" style="margin-top: 22px; width: 100%;">Search <i class="fa fa-lg fa-search-plus"></i></button>
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-center" id="search_result">
                <h3 class="text-center">Results:</h3>
                <div id="adv_search_results"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        
        $('#speciaization option:first').attr('selected','selected');
        
        $('#location').geocomplete();
        
        $("#adv_search_form").validate({
            rules: {
            firstname: {
              require_from_group: [1, ".search-group"]
            },
            lastname: {
              require_from_group: [1, ".search-group"]
            },
            location: {
              require_from_group: [1, ".search-group"]
            }
          },
        errorPlacement: function(error, element){
                var name = element.attr("name");
                $('#error-' + name).append(error);
            }
        });

        $(document).on('click','#submit_adv_member_search', function(event){
            event.preventDefault();
            $('#adv_search_results').empty();
            if($("#adv_search_form").valid()){
                $('#adv_search_results').append('<i class="fa fa-4x fa-spinner fa-spin"></i>');
                    var data = $('#adv_search_form').serializeArray();
                    $.ajax({
                        type: "POST",
                        url: mirrorMuscles.ajaxPath,
                        data: {adv_members_search: 1, data: data},
                        success: function(data) {
                            $('#adv_search_results').empty();
                            var data = $.parseJSON(data);
                            if(data !== null){
                                var list = '<ul id="members-list" class="item-list" role="main">';
                                $.each(data,function(i,e){
                                    list += '<li class="fullname">\
                                            <div class="col-md-8 col-md-offset-2">\
                                                    <div class="item-avatar col-md-3">\
                                                        <a href="'+e.domain+'">'+e.avatar+'</a>\
                                                    </div>\
                                                    <div class="item col-md-9">\
                                                        <div class="item-title fullname">\
                                                            <h3><a href="'+e.domain+'">'+e.fullname+'</a></h3>\
                                                            <p><small><b>'+e.member_type+'</b></small></p>\
                                                            <p><small>'+e.specialization+'</small></p>\
                                                            <p><small><i>'+e.location+'</i></small></p>\
                                                        </div>\
                                                    </div>\
                                            </div>\
                                            </li>';
                                });
                                list += '</ul>';
                                $('#adv_search_results').append(list);
                            }
                            else{
                                $('#adv_search_results').append('<div id="message" class="info"><p>Sorry, no results found.</p></div>');
                            }
                        }
                    });     
                
            }
        });

    });
</script>
<?php else: ?>
    
<?php endif;?>


<?php get_footer(); ?>