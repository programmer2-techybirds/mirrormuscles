<?php
/*
Template Name: Training Plans
*/
require_once WPCF_EMBEDDED_INC_ABSPATH . '/fields.php';
get_header();?>
<?php if(is_user_logged_in()): ?>
<?php $mm_plans_options = get_option("mm_plans_options"); ?>
<div id="buddypress" class="template-training-plans">
    <div class="site-content">
        <h3 class="template-title">Training Plans</h3>
        <p class="template-description-text"><?php echo nl2br(stripslashes($mm_plans_options['mm_plans_training_desc']));?></p>
        <?php

            $genders = wpcf_admin_fields_get_field('training-plan-gender');
            if(!empty($genders)){
                foreach($genders['data']['options'] as $key => $value){
                    
                    if (strpos($key, 'wpcf-fields-radio-option-') !== false)
                    {
                        $plan_genders[] = array('title'=>$value['title'], 'value' => $value['value']);
                    }
                }
            }

            $categories = wpcf_admin_fields_get_field('training-plan-category');
            if(!empty($categories)){
                foreach($categories['data']['options'] as $key => $value){
                    
                    if (strpos($key, 'wpcf-fields-select-option-') !== false)
                    {
                        $plan_categories[] = array('title'=>$value['title'], 'value' => $value['value']);
                    }
                }
            }

            $bodyparts = wpcf_admin_fields_get_field('training-plan-bodyparts');
            if(!empty($bodyparts)){
                foreach($bodyparts['data']['options'] as $key => $value){
                    
                    if (strpos($key, 'wpcf-fields-checkboxes-option-') !== false)
                    {
                        $plan_bodyparts[] = array('title'=>$value['title'], 'value' => $value['set_value']);
                    }
                }
            }
        ?>

        <div id="training_plans_form">
         <form id="get_training_plans_form">

            <h4 class="text-center">Let's Go:</h4>
            <div id="error-plan_gender"></div>
            <div class="form-group genders_checkboxes row">
                <div class="radio">
                    <div id="field_10">
                        <?php foreach($plan_genders as $key=>$gender): ?>
                            <label class="checkbox-inline">
                            <input type="radio" name="plan_gender" value="<?php _e($gender['value']);?>" required>
                            <?php _e($gender['title']);?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            
            <div id="error-plan_category"></div>
            <div class="form-group">
                <div class="form-group">
                    <div class="col-md-12 input-group"  style="padding: 0;" >
                    <select id="plan_category" name="plan_category" class="form-control search-group">
                        <option value="">Choose your goal</option>
                        <?php foreach($plan_categories as $key=>$category): ?>
                            <option value="<?php _e($category['value']);?>"><?php _e($category['title']);?></option>
                            </label>
                        <?php endforeach; ?>
                    </select>
                    </div>
                </div>
            </div>

            
            <div id="error-plan_bodyparts"></div>
            <div class="form-group bodyparts_checkboxes row">
                <?php foreach($plan_bodyparts as $key=>$bodypart): ?>
                    <label class="checkbox-inline col-md-6 bodyparts_label">
                        <input class="bodyparts_checkboxes search-group" type="checkbox" name="plan_bodyparts" value="<?php _e($bodypart['value']);?>"> <?php _e($bodypart['title']);?>
                    </label>
                <?php endforeach; ?>
            </div>
            

            <div class="text-center">
                <button id="get_training_plans_submit" type="submit">Get Training Plans</button>
            </div>
            
        </form>
        </div>

         <div id="training_plans_results" class="col-md-12 text-center">
             <!--PLANS HERE-->
         </div>           

    </div>                   
                            
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $("#plan_category").chosen({disable_search_threshold: 10});

        $("#get_training_plans_form").validate({
            rules: {
            plan_category: {
              require_from_group: [1, ".search-group"]
            },
            plan_bodyparts: {
              require_from_group: [1, ".search-group"]
            }
          },
            errorPlacement: function(error, element){
                var name = element.attr("name");
                $('#error-' + name).append(error);
            }
        });

        $(document).on('change', '#plan_category',function(){
            if($('option:selected',this).val().length>0){
                $('input[name="plan_bodyparts"]').each(function(){
                    $(this).attr('checked',false).addClass('unused');
                });
            }
        });

        $(document).on('click', 'label.bodyparts_label',function(){
            $('input[name="plan_bodyparts"]').each(function(){
                if($(this).is(':checked'));
                    $('#plan_category option:first').attr('selected','selected').trigger("chosen:updated");
            });
        });

        $(document).on('click','#get_training_plans_submit',function(event){
            
            event.preventDefault();
            var _this = $(this);
            $('#training_plans_results').empty();
            if($("#get_training_plans_form").valid()){
                $('#training_plans_results').append('<i class="fa fa-4x fa-spinner fa-spin"></i>');
                $(this).addClass('loading')
                
                var gender = $('input[name="plan_gender"]:checked').val();
                var category = $('select[name="plan_category"] option:selected').val();
                var bodyparts = [];
                $('.bodyparts_checkboxes:checked').each(function(i,e){
                    bodyparts.push($(e).val());
                });

                $.ajax({
                    type: "POST",
                    url: mirrorMuscles.ajaxPath,
                    data: {get_training_plans: 1, gender: gender, category: category, bodyparts: bodyparts},
                    success: function(data) {
                            $('#training_plans_results').empty();
                            var data = $.parseJSON(data);
                            if(data !== null){
                                var results_table = '<table class="plans_results"><thead><tr><th colspan="2">Results:</th></tr></thead><tbody>';
                                $.each(data,function(i,e){
                                    results_table += '<tr><td width="80%">'+e.title+'</td><td><a href="'+e.attachment+'" target="_blank"><i class="fa fa-3x fa-file-text-o"></i></td></tr>';
                                });
                                results_table += '</tbody></table>';
                                $('#training_plans_results').append(results_table);
                                _this.removeClass('loading');
                                
                            }else{
                                $('#training_plans_results').append('<div id="message" class="info"><p>Sorry, no results found.</p></div>');
                                _this.removeClass('loading');
                            }
                            
                        }
                    });
            }
            
        });
    });

</script>
        
<?php endif;?>
<?php get_footer(); ?>