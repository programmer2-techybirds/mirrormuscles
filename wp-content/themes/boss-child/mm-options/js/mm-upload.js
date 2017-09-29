

jQuery(document).ready(function() {
    
    jQuery('#regpage_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#regpage_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#regpage_image').addClass('active');
        return false;
    });

    jQuery('#regpage_image_std_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#regpage_image_std').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#regpage_image_std').addClass('active');
        return false;
    });

    jQuery('#regpage_image_enc_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#regpage_image_enc').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#regpage_image_enc').addClass('active');
        return false;
    });

    jQuery('#food_diary_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#food_diary_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#food_diary_image').addClass('active');
        return false;
    });

    jQuery('#supplement_diary_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#supplement_diary_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#supplement_diary_image').addClass('active');
        return false;
    });
	
	jQuery('#fatsecret_badge_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#fatsecret_badge_img').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#fatsecret_badge_img').addClass('active');
        return false;
    });

    jQuery('#next_training_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#next_training_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#next_training_image').addClass('active');
        return false;
    });

    jQuery('#std_getdiet_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#std_getdiet_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#std_getdiet_image').addClass('active');
        return false;
    });

    jQuery('#std_techniques_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#std_techniques_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#std_techniques_image').addClass('active');
        return false;
    });

    jQuery('#enc_getplans_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#enc_getplans_image').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#enc_getplans_image').addClass('active');
        return false;
    });


    jQuery('#exerciseimage_btn').click(function() {
        console.log('ddd');
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#exerciseimage').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#exerciseimage').addClass('active');
        return false;
    });

    
    window.send_to_editor = function(html) {
     imgurl = jQuery('img',html).attr('src');
     if(jQuery('#regpage_image').hasClass('active')){
        jQuery('#regpage_image').val(imgurl);
        jQuery('#regpage_image_preview img').attr('src',imgurl);
     }
     else if(jQuery('#regpage_image_std').hasClass('active')){
        jQuery('#regpage_image_std').val(imgurl);
        jQuery('#regpage_image_std_preview img').attr('src',imgurl);
     }
    else if(jQuery('#regpage_image_enc').hasClass('active')){
        jQuery('#regpage_image_enc').val(imgurl);
        jQuery('#regpage_image_enc_preview img').attr('src',imgurl);
    }
    else if(jQuery('#food_diary_image').hasClass('active')){
        jQuery('#food_diary_image').val(imgurl);
        jQuery('#food_diary_image_preview img').attr('src',imgurl);
    }
	else if(jQuery('#fatsecret_badge_img').hasClass('active')){
        jQuery('#fatsecret_badge_img').val(imgurl);
        jQuery('#fatsecret_badge_preview img').attr('src',imgurl);
    }
    else if(jQuery('#supplement_diary_image').hasClass('active')){
        jQuery('#supplement_diary_image').val(imgurl);
        jQuery('#supplement_diary_image_preview img').attr('src',imgurl);
    }
    else if(jQuery('#next_training_image').hasClass('active')){
        jQuery('#next_training_image').val(imgurl);
        jQuery('#next_training_image_preview img').attr('src',imgurl);
    }
    else if(jQuery('#std_getdiet_image').hasClass('active')){
        jQuery('#std_getdiet_image').val(imgurl);
        jQuery('#std_getdiet_image_preview img').attr('src',imgurl);
    }
    else if(jQuery('#std_techniques_image').hasClass('active')){
        jQuery('#std_techniques_image').val(imgurl);
        jQuery('#std_techniques_image_preview img').attr('src',imgurl);
    }
    else if(jQuery('#enc_getplans_image').hasClass('active')){
        jQuery('#enc_getplans_image').val(imgurl);
        jQuery('#enc_getplans_image_preview img').attr('src',imgurl);
    }
    else if(jQuery('#exerciseimage').hasClass('active')){
        jQuery('#exerciseimage').val(imgurl);
        jQuery('#exerciseimage_preview img').attr('src',imgurl);
    }
        
     tb_remove();
    }
 
});