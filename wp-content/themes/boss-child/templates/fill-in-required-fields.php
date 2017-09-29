<?php
/*
Template Name: Fillin required
*/
get_header();
?>

<?php 

$empty_fields = get_empty_required_fields();

if(!$empty_fields)
    wp_redirect(home_url());

$member_type = bp_get_member_type($bp->loggedin_user->id);
$mm_regpage_options = get_option('theme_mm_regpage_options');

?>

<div id="buddypress" class="buddypress-reg">
    <div class="template" id="fill-in-required-fields">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-xs-10 col-xs-offset-1">
                    <form id="signup_form" name="signup_form" class="standard-form" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data" style="margin:0; float:none;">
                        <div class="regpage-submit-block" >

                        <div class="col-md-12 col-sm-12 col-xs-12 text-center regblock_header">
                            Please fill in required Profile fields.
                        </div>              
                            
                        <!-- User type - field_4 -->
                        <div class="radio-container">
                            <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'user_id'=>$bp->loggedin_user->id,'profile_group_id' => 1, 'fetch_field_data' => true, 'hide_empty_fields'=>0 ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                                <?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
                                    <?php if(bp_get_the_profile_field_name()=='User Type'):?>
                                        <?php
                                            $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                            $field_type->edit_field_html();
                                        ?>
                                        <div id="error-field_4"></div>
                                    <?php endif;?>
                                <?php endwhile; ?>
                            <?php endwhile; endif; endif; ?>
                        </div>
                        <!-- Time Zone - field_100 -->
                        <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array('fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                            <?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
                                <?php if(bp_get_the_profile_field_name()=='Time Zone'):?>
                                    <?php
                                        $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                        $field_type->edit_field_html();
                                    ?>
                                    <div id="error-field_100"></div>
                                <?php endif;?>
                            <?php endwhile; ?>
                        <?php endwhile; endif; endif; ?>

                            <!-- First Name - field_1 & Last Name - field_2 -->
                            <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'user_id'=>$bp->loggedin_user->id,'profile_group_id' => 1, 'fetch_field_data' => true, 'hide_empty_fields'=>0 ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                                <?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
                                    <?php if(bp_get_the_profile_field_name()=='First Name'||bp_get_the_profile_field_name()=='Last Name'):?>
                                        <?php
                                            $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                            $field_type->edit_field_html();
                                        ?>
                                        <?php if(bp_get_the_profile_field_input_name()=='field_1'):?>
                                            <div id="error-field_1"></div>
                                        <?php else:?>
                                            <div id="error-field_2"></div>
                                        <?php endif;?>
                                    <?php endif;?>
                                <?php endwhile; ?>
                            <?php endwhile; endif; endif; ?>

                            <!-- GYM Name - field_3 -->
                            <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'user_id'=>$bp->loggedin_user->id,'profile_group_id' => 1, 'fetch_field_data' => true, 'hide_empty_fields'=>0 ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                                <?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
                                    <?php if(bp_get_the_profile_field_name()=='GYM Name'):?>
                                        <?php
                                            $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                            $field_type->edit_field_html();
                                        ?>
                                        <div id="error-field_3"></div>
                                    <?php endif;?>
                                <?php endwhile; ?>
                            <?php endwhile; endif; endif; ?>

                            <!-- Specialization - field_12 -->
                            <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'user_id'=>$bp->loggedin_user->id,'profile_group_id' => 1, 'fetch_field_data' => true, 'hide_empty_fields'=>0 ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                                <?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
                                    <?php if(bp_get_the_profile_field_name()=='Specialization'):?>
                                        <?php
                                            $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                            $field_type->edit_field_html();
                                        ?>
                                        <div id="error-field_12"></div>
                                    <?php endif;?>
                                <?php endwhile; ?>
                            <?php endwhile; endif; endif; ?>

                            <!-- Birthday - field_5, Gender - field_7, Location - field_10, Phone - field_11 -->
                            <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'user_id'=>$bp->loggedin_user->id,'profile_group_id' => 1, 'fetch_field_data' => true, 'hide_empty_fields'=>0 ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>
                                <?php while (bp_profile_fields()) : bp_the_profile_field(); ?>
                                    <?php if(bp_get_the_profile_field_name()=='Birthday'||
                                            bp_get_the_profile_field_name()=='Gender'||
                                            bp_get_the_profile_field_name()=='Location'||
                                            bp_get_the_profile_field_name()=='Phone'):?>
                                    
                                    <?php if(bp_get_the_profile_field_input_name()=='field_7'):?>
                                        <div class="radio-container" style="margin-top: 0; margin-bottom: 2px;">
                                    <?php endif;?>

                                        <?php
                                            $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                            $field_type->edit_field_html();
                                        ?>
                                    
                                    <?php if(bp_get_the_profile_field_input_name()=='field_7'):?>
                                        </div>
                                    <?php endif;?>

                                        <?php if(bp_get_the_profile_field_input_name()=='field_5'):?>
                                            <div id="error-field_5_day"></div>
                                            <div id="error-field_5_month"></div>
                                            <div id="error-field_5_year"></div>
                                        <?php elseif(bp_get_the_profile_field_input_name()=='field_7'):?>
                                            <div id="error-field_7"></div>
                                        <?php elseif(bp_get_the_profile_field_input_name()=='field_10'):?>
                                            <div id="error-field_10"></div>
                                        <?php elseif(bp_get_the_profile_field_input_name()=='field_11'):?>
                                            <div id="error-field_11"></div>
                                        <?php endif;?>
                                    <?php endif;?>
                                <?php endwhile; ?>
                            <?php endwhile; endif; endif; ?>

                            <div class="col-md-12 text-center">
                                <input type="hidden" name="action" value="fill_in_required" />
                                <input class="btn" type="submit" name="signup_submit" id="signup_submit" value="<?php esc_attr_e( 'Complete', 'buddypress' ); ?>" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

   jQuery(document).ready(function(){

        //set Location geocomplete dropdown
        jQuery('#field_10').geocomplete().attr('placeholder','');
        
        
        var telInput = jQuery("#field_11");
        telInput.intlTelInput({
            initialCountry: "auto",
            geoIpLookup: function(callback) {
                jQuery.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            }
        });

        if (telInput.val().length>0)
            telInput.val(telInput.intlTelInput("getNumber"));

        telInput.on("keyup change", function() {
            var intlNumber = telInput.intlTelInput("getNumber");
              if (intlNumber)
                telInput.val(intlNumber);
        });

        jQuery('#field_100 option').each(function(i,e){
            var val = jQuery(this).val();
            /*
            var offset = val.split('(')
              .filter(function(v){ return v.indexOf(')') > -1})
              .map( function(value) { 
                 var gmt = value.split(')')[0];
                 return gmt.split('GMT')[1];
                 //returns -11:00...+14:00;
              });*/

            var offset = val.split('(')
              .filter(function(v){ return v.indexOf(')') > -1})
              .map( function(value) { 
                 return value.split(')')[1].trim();
                 //returns Pacific/Wallis, Europe/Kiev...
              });

            if(offset.toString() === moment.tz.guess())
                jQuery(this).attr('selected','selected');
        });


        var switch_usertype = function(type){
            jQuery('div[id^=error]').empty();
            //remove 'reqired' from field labels
            jQuery('input[id^="field_"], select[id^="field_"], input[name="field_7"]').attr('disabled','disabled')
            jQuery('label[for^="field_"]:not(label[for="field_4"])').text(function(i, val) {
                return val.replace(/\(required\)/g,'');
            });
            jQuery('div#field_7').prev('legend').text(function(i, val) {
                return val.replace(/ \(required\)/g,'');
            });

            jQuery('select[id^=field_]').attr('disabled','disabled')
            jQuery('select[id^=field_] option').attr('selected',false);
            jQuery('input[name^=field_]:not([name="field_4"])').attr('selected',false).attr('checked',false).attr('disabled','disabled');
            
            switch(type){
                case 'standard':
                    jQuery('label[for="field_1"],label[for="field_2"], label[for^="field_5"],legend,label[for="field_10"],label[for="field_11"],label[for="field_100"]').text(function(i, val) {
                        return val+' (required)';
                    });
                    jQuery('#field_1,#field_2,#field_100,select[id^="field_5"],input[name=field_7],#field_10,#field_11').attr('disabled',false);
                break;
                case 'pt':
                    jQuery('label[for="field_1"],label[for="field_2"], label[for^="field_5"],label[for="field_100"],legend,label[for="field_10"],label[for="field_11"],label[for^="field_12"],label[for="field_100"]').text(function(i, val) {
                        return val+' (required)';
                    });
                    jQuery('#field_1,#field_2,#field_100,select[id^="field_5"],input[name=field_7],#field_10,#field_11,select[name^=field_12]').attr('disabled',false);
                break;
                case 'gym':
                    jQuery('label[for="field_3"],label[for="field_10"],label[for="field_11"],label[for="field_100"],label[for^="field_12"]').text(function(i, val) {
                        return val+' (required)';
                    });
                    jQuery('#field_3,#field_100,#field_10,#field_11,select[name^=field_12]').attr('disabled',false);
                break;
            }   
        }


        var type = jQuery('input[name="field_4"]:checked').val();
        switch_usertype(type);

        jQuery(document).on('change','input[name="field_4"]',function(){
            switch_usertype(jQuery(this).val());
        });
        


        jQuery.validator.addMethod("nowhitespace", function(value, element)
        { return jQuery.trim(value) && value != ""; }, "No space please and don't leave it empty");

        jQuery.validator.addMethod("isvalidphone", function(value, element)
        { return telInput.intlTelInput("isValidNumber"); }, "Mobile phone number is invalid.");

        jQuery.validator.addMethod('username', function (value) { 
            return /^([a-zA-Z0-9.-@]+)$/.test(value); 
        }, 'Usernames can contain only letters, numbers, ., -, and @');

        jQuery.validator.addMethod("nowhitespace", function(value, element)
        { return jQuery.trim(value) && value != ""; }, "No space please and don't leave it empty");

        jQuery.validator.addMethod("isvalidphone", function(value, element)
        { return telInput.intlTelInput("isValidNumber"); }, "Mobile phone number is invalid.");

        jQuery("#signup_form").validate({
            ignore: "",
            rules: {

                field_1: {
                  required: true,
                  nowhitespace: true
                },
                field_2: {
                  required: true,
                  nowhitespace: true
                },
                field_3: {
                  required: true,
                  nowhitespace: true
                },
                field_4: {
                    required: true
                },
                field_5_day:{
                    required: true,
                    minlength: 1
                },
                field_5_month:{
                    required: true,
                    minlength: 1
                },
                field_5_year:{
                    required: true,
                    minlength: 1
                },
                field_7:{
                    required: true,
                    minlength: 1
                },
                field_10: {
                  required: true,
                  nowhitespace: true
                },
                field_11: {
                  required: true,
                  isvalidphone: true,
                  nowhitespace: true,
                },
                field_12: {
                  required: true
                },
                field_100: {
                  required: true
                }
            },
            messages: {
                field_1: "Enter your First Name",
                field_2: "Enter your Last Name",
                field_3: "Enter your GYM Name",
                field_4: "Choose your User Type",
                field_5_day: "Select your Birthday day",
                field_5_month: "Select your Birthday month",
                field_5_year: "Select your Birthday year",
                field_7: "Select your Gender",
                field_10: "Enter your Location",
                field_11: "Mobile phone number is invalid",
                field_12: "Enter your Specialization",
                field_100: "Select your Time Zone",
            },
            errorPlacement: function(error, element){
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

        jQuery('#signup_submit').on('click',function(event){
            event.preventDefault();
            jQuery(this).addClass('loading').attr('disabled','disabled');
            
            if(jQuery("#signup_form").valid())
                jQuery("#signup_form").submit();
            else
                jQuery(this).removeClass('loading').attr('disabled',false);
            
        });

    });
</script>
<?php get_footer(); ?>