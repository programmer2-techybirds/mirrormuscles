<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
?>
</div><!-- #main .wrapper -->

</div><!-- #page -->

</div> <!-- #inner-wrap -->

</div><!-- #main-wrap (Wrap For Mobile) -->

<footer id="colophon" role="contentinfo">

	<?php get_template_part( 'template-parts/footer-widgets' ); ?>

	<div class="footer-inner-bottom" <?php echo ( !is_user_logged_in() || is_page('fill-in-required-fields') ) ? '"' :'';?>>
	
	<?php 
		$current_user = wp_get_current_user();
		$user_info = get_userdata($current_user->ID);
		//echo"<pre>"; print_r($user_info);
		$user_types = $user_info->roles[0];
		
	?>
	
	<?php if ( bp_get_member_type(get_current_user_id())=='standard' || current_user_can('manage_options') ) :?>
	
		<div class="footer-inner" id="std_and_admin">
			<?php get_template_part( 'template-parts/footer-copyright' ); ?>
            <?php if(is_user_logged_in()&&!is_page('fill-in-required-fields')):?>
            	<a href="https://mirrormuscles.freshservice.com/support/home" class="faqs-link" target="_blank">Help
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-question fa-stack-1x"></i>
					</span>
				</a>
				<a href="<?php echo home_url().'/privacy-policy';?>" class="privacy-link" target="_blank">Privacy Policy
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-lock fa-stack-1x"></i>
					</span>
				</a>
				<a href="<?php echo home_url().'/acceptable-use-policy';?>" class="privacy-link" target="_blank">Acceptable Use Policy
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-lock fa-stack-1x"></i>
					</span>
				</a>
			<?php endif;?>
			<?php get_template_part( 'template-parts/footer-links' ); ?>
		</div><!-- .footer-inner -->
		
		<?php elseif ( bp_get_member_type( get_current_user_id() ) == 'pt' || bp_get_member_type(get_current_user_id())=='gym' ) : ?>
		
		<div class="footer-inner" id="gym_and_pt">
			<?php get_template_part( 'template-parts/footer-copyright' ); ?>
            <?php if(is_user_logged_in()&&!is_page('fill-in-required-fields')):?>
            	<a href="https://mirrormuscles.freshservice.com/support/home" class="faqs-link" target="_blank">Help
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-question fa-stack-1x"></i>
					</span>
				</a>
				<a href="<?php echo home_url().'/privacy-policy';?>" class="privacy-link" target="_blank">Privacy Policy
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-lock fa-stack-1x"></i>
					</span>
				</a>
				<a href="<?php echo home_url().'/vendor-terms-conditions';?>" class="privacy-link" target="_blank">Vendor Terms & Conditions
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-pencil-square-o fa-stack-1x"></i>
					</span>
				</a>
				<a href="<?php echo home_url().'/acceptable-use-policy';?>" class="privacy-link" target="_blank">Acceptable Use Policy
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-lock fa-stack-1x"></i>
					</span>
				</a>
			<?php endif;?>
			<?php get_template_part( 'template-parts/footer-links' ); ?>
		</div><!-- .footer-inner -->
		<?php else:?>
		<div class="footer-inner" id="other">
			<?php get_template_part( 'template-parts/footer-copyright' ); ?>
            <?php if(is_user_logged_in()&&!is_page('fill-in-required-fields')):?>
            	<a href="https://mirrormuscles.freshservice.com/support/home" class="faqs-link" target="_blank">Help
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-question fa-stack-1x"></i>
					</span>
				</a>
				<a href="<?php echo home_url().'/privacy-policy';?>" class="privacy-link" target="_blank">Privacy Policy
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-lock fa-stack-1x"></i>
					</span>
				</a>
				<a href="<?php echo home_url().'/acceptable-use-policy';?>" class="privacy-link" target="_blank">Acceptable Use Policy
					<span class="fa-stack">
						<i class="fa fa-square-o fa-stack-2x"></i>
					    <i class="fa fa-lock fa-stack-1x"></i>
					</span>
				</a>
			<?php endif;?>
			<?php get_template_part( 'template-parts/footer-links' ); ?>
		</div><!-- .footer-inner -->
		<?php endif; ?>
	</div><!-- .footer-inner-bottom -->

	<?php do_action( 'bp_footer' ) ?>

</footer><!-- #colophon -->
</div><!-- #right-panel-inner -->
</div><!-- #right-panel -->

</div><!-- #panels -->

<?php wp_footer(); ?>
<style>
@media (min-width:320px) and (max-width:640px)
{
	.home-page .unregistered-wrapper h3 {
		font-size: 41px;
	}
	div#gym_and_pt a {
		text-align: left;
		width: 100%;
	}
	#std_and_admin a{
		text-align: left;
		width: 100%;
	}
	
	#body-fat-calculator a#bfc-calculate, a#bfc-save, a#bfc-share{
		width: 100%;
	}
	i.fa.fa-calculator {
		margin-right: 10px;
	}
	i.fa.fa-lg.fa-save {
		margin-right: 10px;
	}
	i.fa.fa-share-alt {
		margin-right: 11px;
	}
	#left-panel-inner.BeanSidebarIn {
		padding-top: 0px !important;
	}
	#right-panel {
		margin-top: 0px !important;
	}
	.notifications .menu-panel .bp_components.mobile ul.ab-top-menu {
		display: block;
	}
	#mm-video-container {
		display: none;
	}
	#buddypress form#whats-new-form #whats-new-content{
		margin-left: 0px;
		margin-right: 0px;
	}
	
	/*Search in nutrition diary */
	.page-id-1506 #meals_tbls #meal-table-1 [data-hide="phone"] {
		display: table-cell !important;
	}
	.page-id-1506 #meals_tbls #meal-table-1 .meal-total td {
		display: table-cell !important;
	}
	.page-id-1506 #meals_tbls #meal-table-1 td.search-ingredient-results.test {
		display: table-cell !important;
	}
	.page-id-1506 div#meals_tbls {
		padding: 0;
	}
	.page-id-1506 .meal-table::-webkit-scrollbar-thumb {
		-webkit-border-radius: 10px;
		border-radius: 15px;
		background: #6d6d6d;
	}
	.page-id-1506 .meal-table::-webkit-scrollbar-track {
		background-color: #ebebeb;
		-webkit-border-radius: 10px;
		border-radius: 0;
	}
	.page-id-1506 .meal-table::-webkit-scrollbar {
		width: 10px;
		background: #000 !important;
	}
	.page-id-1506 .popover{
		left: 0 !important;
	}
	
	table.meal-table {
		overflow: scroll !important;
		display: block;
	}
}

@media (min-width:768px){
	#mm-video-container {
		display: none;
	}
	.page-id-1506 #meals_tbls #meal-table-1 [data-hide="phone"] {
		display: table-cell !important;
	}
	.page-id-1506 #meals_tbls #meal-table-1 .meal-total td {
		display: table-cell !important;
	}
	.page-id-1506 #meals_tbls #meal-table-1 td.search-ingredient-results.test {
		display: table-cell !important;
	}
	.page-id-1506 div#meals_tbls {
		padding: 0;
	}
	.page-id-1506 .table-responsive::-webkit-scrollbar-thumb {
		-webkit-border-radius: 10px;
		border-radius: 15px;
		background: #6d6d6d;
	}
	.page-id-1506 .table-responsive::-webkit-scrollbar-track {
		background-color: #ebebeb;
		-webkit-border-radius: 10px;
		border-radius: 0;
	}
	.page-id-1506 .table-responsive::-webkit-scrollbar {
		width: 10px;
		background: #000 !important;
	}
}

#mm-video-container {
		display: none;
	}
.my-account #right-panel {
		margin-top: 0px !important;
}
.page-id-1506 .table-responsive {
    overflow-x: hidden;
}
/*.blur_bg{
	background: #262626;
}*/
h2.landing-tital {
    padding-bottom: 0;
}
div#landing_main {
    position: relative;
    bottom: 48px;
}

.background_gone div#main {
    background: transparent !important;
}
div#main.blur_bg {
    padding-bottom: 0px !important;
}


div#page.background_gone {
    background: none !important;
}
.blur_bg .page-full-width {
    margin: 0;
}
.body.home #page {
    background: none !important;
}
#slider_home_page{
	display:none;
}
div#slider_home_page {
    z-index: 11111 !important;
    height: 550px;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
	    margin-top: 10px;
}
.blur_bg div#primary {
    min-height: 500px;
}
[href="http://mirrormuscles.com#about"], [href="http://mirrormuscles.com#services"], [href="http://mirrormuscles.com#testimonials"] {
    display: none;
}
div#page.darkcontrols {
    min-height: 485px;
    background: #262626 !important;
}
#individual_frame, #personal_frame, #gym_frame{
	display: none;
}

/*bakend tab*/
#tabs-1 h2 {
    margin-top: 45px;
}
li.tab_landing.ui-state-default.ui-corner-top {
    width: auto;
    float: left;
    background: #ddd;
    border-right: 1px solid;
    padding-left: 33px;
    padding-right: 22px;
    padding-top: 10px;
    padding-bottom: 11px;
}
.tab_landing a {
    padding-left: 25px;
    padding-right: 25px;
    padding-top: 20px;
    text-decoration: none;
    padding-bottom: 24px;
}

/*=================== homepage ===================*/
#landing_main img.main-img.img-responsive {
    margin: 0 auto;
}
@media (min-width: 1280px){
	/*.videoo {
		margin-left: 29%;
		padding-top: 40px;
		margin-right: 29%;
	} */
	.fullscreen {
	  height: 100%;
		overflow: hidden;
		width: 100%;
	}
	.video {
	  display: block;
		left: 0px;
		overflow: hidden;
		/*padding-bottom: 40.25%; *//* 56.25% = 16:9. set ratio */
		padding-bottom: 0;
		position: absolute;
		top: 34%;
		width: 100%;
		-webkit-transform-origin: 50% 0;
		transform-origin: 50% 0;
		-webkit-transform: translateY(-50%);
		transform: translateY(-73%);
	}
	embed, iframe, object, video { width: 100%; }
	.videoo#individual_frame { margin-top: 2%; }
	.videoo#personal_frame .video {	top: 36%;}
	.videoo#personal_frame { margin-top: 3%;}
	.videoo#personal_frame .video {	top: 32%;}
	.videoo#gym_frame { margin-top: 3%; }
	
	#individual_frame .video {
		-webkit-transform: translateY(-50%);
		transform: scale(1) translateY(-61.8%) !important;
	}
	#personal_frame .video {
		-webkit-transform: translateY(-50%);
		transform: scale(1) translateY(-65.8%) !important;
	}
	#gym_frame .video {
		-webkit-transform: translateY(-50%);
		transform: scale(1) translateY(-67.5%) !important;
	}
	
	/*.video .wrapper {
	  display: block;
		height: 300%;
		left: 0px;
		overflow: hidden;
		position: absolute;
		top: 35%;
		width: 100%;
		-webkit-transform: translateY(-50%);
		transform: translateY(-50%);
	}
	.video iframe {
	  display: block;
		height: 100%;
		width: 100%;
	}*/
}


@media (min-width: 768px) and (max-width: 1024px){
	#landing_main img.main-img.img-responsive {
		margin: 0 auto;
	}
	.pt-des {
		text-align: center;
	}
	.icon-img {
		margin: 0 auto;
	}
	/*.videoo {
		margin-left: 15%;
		padding-top: 40px;
		margin-right: 15%;
	} */
	
	.video {
		display: block;
		left: 0px;
		overflow: hidden;
		padding-bottom: 22%; /* 56.25% = 16:9. set ratio */
		position: absolute;
		top: 37%;
		width: 100%;
		-webkit-transform-origin: 50% 0;
		transform-origin: 50% 0;
		-webkit-transform: translateY(-50%);
		transform: translateY(-68%) !important;
	}
	div#slider_home_page {
		z-index: 11111 !important;
		height: 455px;
		
	}
	div#personal, div#individual, div#gym {
		padding-top: 15%;
	}

}
@media (min-width: 320px) and (max-width: 736px){
	.pt-des {
		text-align: center;
	}
	.icon-img {
		margin: 0 auto;
	}
	.fullscreen .video {
		transform: scale(1) translateY(-5%) !important;
	}
	div#personal, div#individual, div#gym {
		padding-top: 15%;
	}
}

.slider_main_home{display: none;}
.video {
    z-index: 11111 !important;
}

</style>
<script>
			jQuery("#go_individual").click(function(){
				jQuery("#individual").show();
				 jQuery('.slider_main_home').css('display','block'); 
				jQuery('#slider_home_page').css('display','block');
				jQuery('#individual_frame').css('display','block');
				jQuery("#landing_main").hide();
				/* jQuery("#primary").show(); */
			});
			
			jQuery("#go_pt").click(function(){
				jQuery("#personal").show();
				jQuery('.slider_main_home').css('display','block');
				jQuery('#slider_home_page').css('display','block');
				jQuery('#personal_frame').css('display','block');
				jQuery("#landing_main").hide();
			/* 	jQuery("#primary").css('display','block'); */
			});
			jQuery("#go_gym").click(function(){
				jQuery("#gym").show();
				jQuery('.slider_main_home').css('display','block');
				jQuery('#slider_home_page').css('display','block');
				jQuery('#gym_frame').css('display','block');
				jQuery("#landing_main").hide();
				/* jQuery("#primary").css('display','block'); */
			});
			jQuery('#go_individual').click(function() {
				jQuery('#main').addClass('blur_bg');
			});
			jQuery('#go_pt').click(function() {
				jQuery('#main').addClass('blur_bg');
			});
			jQuery('#go_gym').click(function() {
				jQuery('#main').addClass('blur_bg');
			});
			
			/*jQuery( "#main" ).hasClass( "blur_bg" )*/
			
			jQuery('#go_individual').click(function() {
				jQuery('#page').addClass('background_gone');
			});
			jQuery('#go_pt').click(function() {
				jQuery('#page').addClass('background_gone');
			});
			jQuery('#go_gym').click(function() {
				jQuery('#page').addClass('background_gone');
			});
			
			jQuery(document).ready(function() {
			if (jQuery('.landing').attr("id") == "landing_main") {
					 jQuery('.site').addClass('darkcontrols');
					 jQuery('#inner-wrap').addClass('decrease_height_inner-wrap');
					 jQuery("#primary").css('display','none');
			  }
			 });
			 
			 jQuery( window ).on( "load", function() {
				
				jQuery(".darkcontrols").removeAttr("style").attr("style","");
			  }); 
			  
			  
			</script>
			
			<script type="text/javascript">
				function videoSize() {
					var $windowHeight = jQuery(window).height();
					var $videoHeight = jQuery(".video").outerHeight();
					var $scale = $windowHeight / $videoHeight;
				  
				  if ($videoHeight <= $windowHeight) {
					jQuery(".video").css({
					  "-webkit-transform" : "scale("+$scale+") translateY(-50%)",
							"transform" : "scale(1) translateY(-68%)"
						});
					};
				}

				jQuery(window).on('load resize',function(){
				  videoSize();
				});
					/* var imgstd = jQuery('#imgstd').val();
					var imgenc = jQuery('#imgenc').val();

					setInterval(function(){
						var imgactive = jQuery('#imgactive').val();

						if(imgactive == 'std'){
							jQuery('#slider_home_page').css('background-image', 'url("' + imgstd + '")');
							jQuery('#imgactive').val('enc');
						}
						else{
							jQuery('#slider_home_page').css('background-image', 'url("' + imgenc + '")');
							jQuery('#imgactive').val('std');
						}
						
						jQuery('.unregistered-wrapper').each(function(){
							if(jQuery(this).hasClass('active'))
								jQuery(this).removeClass('active');
							else
								jQuery(this).addClass('active');
							});

					}, 10000); */

				</script>
                <script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('.atcb-link').css('color', '#aa2d2a');
						jQuery('.atcb-link').css('border', '2px solid #aa2d2a');
						jQuery('.atcb-link').css('background-color', 'transparent !important');
					});
				</script>
				<script type="text/javascript">
					/* var imgstd = jQuery('#imgstd').val();
					var imgenc = jQuery('#imgenc').val();

					setInterval(function(){
						var imgactive = jQuery('#imgactive').val();

						if(imgactive == 'std'){
							jQuery('#page').css('background-image', 'url("' + imgstd + '")');
							jQuery('#imgactive').val('enc');
						}
						else{
							jQuery('#page').css('background-image', 'url("' + imgenc + '")');
							jQuery('#imgactive').val('std');
						}
						
						jQuery('.unregistered-wrapper').each(function(){
							if(jQuery(this).hasClass('active'))
								jQuery(this).removeClass('active');
							else
								jQuery(this).addClass('active');
							});

					}, 10000); */

				</script>
				
<script src="https://apis.google.com/js/client:platform.js" async defer></script>

<?php if(is_page('dashboard')): ?>
	<script src="/cometchat/js.php?type=core&name=embedcode" type="text/javascript"></script><script>var iframeObj = {};iframeObj.module="synergy";iframeObj.style="min-height:420px;min-width:300px;";iframeObj.src="/cometchat/cometchat_popout.php"; if(typeof(addEmbedIframe)=="function"){addEmbedIframe(iframeObj);}</script>
<?php endif;?>

<script type="text/javascript">
function brokenImages() {
  var totalimg = $("body").find("img").length;
  var brokenimg = 0;
  $('img').each(function() {
    if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
      brokenimg ++;
      this.src = 'https://www.mirrormuscles.com/wp-content/uploads/2017/05/logo-1.png'; 
    }
  });
}
jQuery(document).ready(function() {
	
	jQuery("p.price span.amount").tooltip({
			tooltipClass: "ui-tooltip1",
	});
	jQuery("p.price span.amount").attr("title","Price during listing needs to be in GBP to allow for commission calculations, once product posted, your product can be seen in any currency within store / marketplace");
	
  brokenImages();
});
</script>
<style>
.hide-all,.show_if_simple{
	display:block !important;
}
#wcv-attr-message,.align-right{
	display:none !important;
}
</style>	
</body>
</html>