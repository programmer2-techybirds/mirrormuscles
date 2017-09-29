var aVariable = [];
var inbox_sync;
var hide_bar;
jQuery(document).ready(function() {

    jQuery(".tab-links").find("li").click(function(){
        jQuery(".menus").removeClass("active");
        jQuery(this).addClass("active");
        var rel=jQuery(this).data("rel");
        jQuery(".tab").hide();
        jQuery("#"+rel).show();
    });

    jQuery('#save').on('click', function(e) {

        jQuery("input.test[type=checkbox]:checked").each(function() {
            aVariable.push(jQuery(this).val());
        });
        inbox_sync = jQuery("input.sync[type=checkbox]:checked").val();
        if(inbox_sync == '' || inbox_sync == undefined){
            inbox_sync = "false";
        }
        hide_bar = jQuery("input.hide[type=checkbox]:checked").val();
        if(hide_bar == '' || hide_bar == undefined){
            hide_bar = "false";
        }else{
            hide_bar = "true";
        }
        data = {
            'action': 'cometchat_friend_ajax',
            'usergroups': aVariable,
            'inbox_sync': inbox_sync,
            'hide_bar': hide_bar
        }
        jQuery.post(ajaxurl, data, function(response){
        jQuery("#success").html("<div class='updated'><p>Settings successfully saved!</p></div>");
        jQuery(".updated").fadeOut(3000);
        });
        aVariable.length = 0;
    });

    var urlHash = window.location.hash.substr(1);
    jQuery("#submenu").find("li[data-rel='"+urlHash+"']").click();
});
