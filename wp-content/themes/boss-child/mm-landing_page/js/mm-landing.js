jQuery(document).ready(function() {
	
	jQuery('#individual_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#individual_img').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#individual_img').addClass('active');
        return false;
    });
	
	jQuery('#gym_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#gym_img').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#gym_img').addClass('active');
        return false;
    });
	
	jQuery('#pt_image_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#pt_img').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#pt_img').addClass('active');
        return false;
    });
	
	window.send_to_editor = function(html) {
		
		imgurl = jQuery('img',html).attr('src');
		if(jQuery('#individual_img').hasClass('active')){
			jQuery('#individual_img').val(imgurl);
			jQuery('#individual_image_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#gym_img').hasClass('active')){
			jQuery('#gym_img').val(imgurl);
			jQuery('#gym_image_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#pt_img').hasClass('active')){
			jQuery('#pt_img').val(imgurl);
			jQuery('#pt_image_preview img').attr('src',imgurl);
		}
			
	 tb_remove();
    }
});

jQuery(document).ready(function() {
jQuery('#icon_image1_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img1').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img1').addClass('active');
        return false;
    });
	jQuery('#icon_image2_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img2').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img2').addClass('active');
        return false;
    });
	jQuery('#icon_image3_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img3').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img3').addClass('active');
        return false;
    });
	jQuery('#icon_image4_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img4').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img4').addClass('active');
        return false;
    });
	jQuery('#icon_image5_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img5').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img5').addClass('active');
        return false;
    });
	jQuery('#icon_image6_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img6').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img6').addClass('active');
        return false;
    });
	jQuery('#icon_image7_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img7').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img7').addClass('active');
        return false;
    });
	jQuery('#icon_image8_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img8').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img8').addClass('active');
        return false;
    });
	jQuery('#icon_image9_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img9').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img9').addClass('active');
        return false;
    });
	jQuery('#icon_image10_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img10').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img10').addClass('active');
        return false;
    });
	jQuery('#icon_image11_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img11').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img11').addClass('active');
        return false;
    });
	jQuery('#icon_image12_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img12').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img12').addClass('active');
        return false;
    });
	
		window.send_to_editor = function(html) {
		
		imgurl = jQuery('img',html).attr('src');
		if(jQuery('#icon_img1').hasClass('active')){
			jQuery('#icon_img1').val(imgurl);
			jQuery('#icon_image1_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img2').hasClass('active')){
			jQuery('#icon_img2').val(imgurl);
			jQuery('#icon_image2_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img3').hasClass('active')){
			jQuery('#icon_img3').val(imgurl);
			jQuery('#icon_image3_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img4').hasClass('active')){
			jQuery('#icon_img4').val(imgurl);
			jQuery('#icon_image4_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img5').hasClass('active')){
			jQuery('#icon_img5').val(imgurl);
			jQuery('#icon_image5_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img6').hasClass('active')){
			jQuery('#icon_img6').val(imgurl);
			jQuery('#icon_image6_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img7').hasClass('active')){
			jQuery('#icon_img7').val(imgurl);
			jQuery('#icon_image7_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img8').hasClass('active')){
			jQuery('#icon_img8').val(imgurl);
			jQuery('#icon_image8_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img9').hasClass('active')){
			jQuery('#icon_img9').val(imgurl);
			jQuery('#icon_image9_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img10').hasClass('active')){
			jQuery('#icon_img10').val(imgurl);
			jQuery('#icon_image10_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img11').hasClass('active')){
			jQuery('#icon_img11').val(imgurl);
			jQuery('#icon_image11_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img12').hasClass('active')){
			jQuery('#icon_img12').val(imgurl);
			jQuery('#icon_image12_preview img').attr('src',imgurl);
		}
		
		 tb_remove();
	}
});

jQuery(document).ready(function() {
jQuery('#icon_image_1_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_1').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_1').addClass('active');
        return false;
    });
	jQuery('#icon_image_2_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_2').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_2').addClass('active');
        return false;
    });
	jQuery('#icon_image_3_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_3').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_3').addClass('active');
        return false;
    });
	jQuery('#icon_image_4_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_4').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_4').addClass('active');
        return false;
    });
	jQuery('#icon_image_5_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_5').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_5').addClass('active');
        return false;
    });
	jQuery('#icon_image_6_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_6').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_6').addClass('active');
        return false;
    });
	jQuery('#icon_image_7_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_7').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_7').addClass('active');
        return false;
    });
	jQuery('#icon_image_8_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_8').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_8').addClass('active');
        return false;
    });
	jQuery('#icon_image_9_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_9').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_9').addClass('active');
        return false;
    });
	jQuery('#icon_image_10_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_10').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_10').addClass('active');
        return false;
    });
	jQuery('#icon_image_11_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_11').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_11').addClass('active');
        return false;
    });
	jQuery('#icon_image_12_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_12').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_12').addClass('active');
        return false;
    });
	jQuery('#icon_image_13_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_13').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_13').addClass('active');
        return false;
    });
	
	jQuery('#icon_image_14_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_14').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_14').addClass('active');
        return false;
    });
	jQuery('#icon_image_15_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_15').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_15').addClass('active');
        return false;
    });
	
		window.send_to_editor = function(html) {
		
		imgurl = jQuery('img',html).attr('src');
		if(jQuery('#icon_img_1').hasClass('active')){
			jQuery('#icon_img_1').val(imgurl);
			jQuery('#icon_image_1_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img_2').hasClass('active')){
			jQuery('#icon_img_2').val(imgurl);
			jQuery('#icon_image_2_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img_3').hasClass('active')){
			jQuery('#icon_img_3').val(imgurl);
			jQuery('#icon_image_3_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img_4').hasClass('active')){
			jQuery('#icon_img_4').val(imgurl);
			jQuery('#icon_image_4_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_5').hasClass('active')){
			jQuery('#icon_img_5').val(imgurl);
			jQuery('#icon_image_5_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_6').hasClass('active')){
			jQuery('#icon_img_6').val(imgurl);
			jQuery('#icon_image_6_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_7').hasClass('active')){
			jQuery('#icon_img_7').val(imgurl);
			jQuery('#icon_image_7_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_8').hasClass('active')){
			jQuery('#icon_img_8').val(imgurl);
			jQuery('#icon_image_8_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_9').hasClass('active')){
			jQuery('#icon_img_9').val(imgurl);
			jQuery('#icon_image_9_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_10').hasClass('active')){
			jQuery('#icon_img_10').val(imgurl);
			jQuery('#icon_image_10_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_11').hasClass('active')){
			jQuery('#icon_img_11').val(imgurl);
			jQuery('#icon_image_11_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_12').hasClass('active')){
			jQuery('#icon_img_12').val(imgurl);
			jQuery('#icon_image_12_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_13').hasClass('active')){
			jQuery('#icon_img_13').val(imgurl);
			jQuery('#icon_image_13_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_14').hasClass('active')){
			jQuery('#icon_img_14').val(imgurl);
			jQuery('#icon_image_14_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_15').hasClass('active')){
			jQuery('#icon_img_15').val(imgurl);
			jQuery('#icon_image_15_preview img').attr('src',imgurl);
		}
	}
});


jQuery(document).ready(function() {
jQuery('#icon_image_1_1_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_1_1').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_1_1').addClass('active');
        return false;
    });
	jQuery('#icon_image_2_2_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_2_2').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_2_2').addClass('active');
        return false;
    });
	jQuery('#icon_image_3_3_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_3_3').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_3_').addClass('active');
        return false;
    });
	jQuery('#icon_image_4_4_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_4_4').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_4_4').addClass('active');
        return false;
    });
	jQuery('#icon_image_5_5_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_5_5').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_5_5').addClass('active');
        return false;
    });
	jQuery('#icon_image_6_6_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_6_6').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_6_6').addClass('active');
        return false;
    });
	jQuery('#icon_image_7_7_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_7_7').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_7_7').addClass('active');
        return false;
    });
	jQuery('#icon_image_8_8_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_8_8').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_8_8').addClass('active');
        return false;
    });
	jQuery('#icon_image_9_9_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_9_9').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_9_9').addClass('active');
        return false;
    });
	jQuery('#icon_image_10_10_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_10_10').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_10_10').addClass('active');
        return false;
    });
	jQuery('#icon_image_11_11_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_11_11').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_11_11').addClass('active');
        return false;
    });
	jQuery('#icon_image_12_12_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_12_12').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_12_12').addClass('active');
        return false;
    });
	jQuery('#icon_image_13_13_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_13_13').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_13_13').addClass('active');
        return false;
    });
	
	jQuery('#icon_image_14_14_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_14_14').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_14_14').addClass('active');
        return false;
    });
	jQuery('#icon_image_15_15_btn').click(function() {
        jQuery('.image-upl').removeClass('active');
        formfield = jQuery('#icon_img_15_15').attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        jQuery('#icon_img_15_15').addClass('active');
        return false;
    });
	
		window.send_to_editor = function(html) {
		
		imgurl = jQuery('img',html).attr('src');
		if(jQuery('#icon_img_1_1').hasClass('active')){
			jQuery('#icon_img_1_1').val(imgurl);
			jQuery('#icon_image_1_1_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img_2_2').hasClass('active')){
			jQuery('#icon_img_2_2').val(imgurl);
			jQuery('#icon_image_2_2_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img_3_3').hasClass('active')){
			jQuery('#icon_img_3_3').val(imgurl);
			jQuery('#icon_image_3_3_preview img').attr('src',imgurl);
		} 
		else if(jQuery('#icon_img_4_4').hasClass('active')){
			jQuery('#icon_img_4_4').val(imgurl);
			jQuery('#icon_image_4_4_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_5_5').hasClass('active')){
			jQuery('#icon_img_5_5').val(imgurl);
			jQuery('#icon_image_5_5_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_6_6').hasClass('active')){
			jQuery('#icon_img_6_6').val(imgurl);
			jQuery('#icon_image_6_6_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_7_7').hasClass('active')){
			jQuery('#icon_img_7_7').val(imgurl);
			jQuery('#icon_image_7_7_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_8_8').hasClass('active')){
			jQuery('#icon_img_8_8').val(imgurl);
			jQuery('#icon_image_8_8_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_9_9').hasClass('active')){
			jQuery('#icon_img_9_9').val(imgurl);
			jQuery('#icon_image_9_9_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_10_10').hasClass('active')){
			jQuery('#icon_img_10_10').val(imgurl);
			jQuery('#icon_image_10_10_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_11_11').hasClass('active')){
			jQuery('#icon_img_11_11').val(imgurl);
			jQuery('#icon_image_11_11_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_12_12').hasClass('active')){
			jQuery('#icon_img_12_12').val(imgurl);
			jQuery('#icon_image_12_12_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_13_13').hasClass('active')){
			jQuery('#icon_img_13_13').val(imgurl);
			jQuery('#icon_image_13_13_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_14_14').hasClass('active')){
			jQuery('#icon_img_14_14').val(imgurl);
			jQuery('#icon_image_14_14_preview img').attr('src',imgurl);
		}
		else if(jQuery('#icon_img_15_15').hasClass('active')){
			jQuery('#icon_img_15_15').val(imgurl);
			jQuery('#icon_image_15_15_preview img').attr('src',imgurl);
		}
	}
});

