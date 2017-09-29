<?php
/**
 * The Header for your theme.
 *
 * Displays all of the <head> section and everything up until <div id="main">
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
?><!DOCTYPE html>

<html <?php language_attributes(); ?> itemscope itemtype="https://schema.org/Article">

<head>
<meta name="title" content="Mirror Muscles - The Best fitness gym"/>
<meta name="description" content="Mirror Muscles."/>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="msapplication-tap-highlight" content="no"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Ubuntu:regular,bold&subset=Latin">

<!--Meta tags for google and twitter parser, to share progress image photo-->
<meta itemprop="name" content="Mirror Muscles">
	
<!--    <meta property="og:title" content="Site Title" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://www.mirrormuscles.com" />
    <meta property="og:image" content="http://www.mirrormuscles.com/wp-content/themes/boss-child/images/mm_sharing_logo.jpg" />
    <meta property="og:description" content="Site description" />-->


<!--<meta content="https://www.mirrormuscles.com/wp-content/themes/boss-child/images/mm_sharing_logo.jpg" itemprop="image"><link href="https://www.mirrormuscles.com/wp-content/themes/boss-child/images/mm_sharing_logo.jpg" rel="shortcut icon"><meta content="origin" id="mref" name="referrer">-->

<link rel="image_src" href="https://mirrormuscles.com/wp-content/themes/boss-child/images/mm_sharing_logo.jpg" />

<meta property="og:title" content="mirrormuscles"> 
<meta property="og:image" content="https://mirrormuscles.com/wp-content/themes/boss-child/images/mm_sharing_logo.jpg"> 
<meta property="og:description" content="mirrormuscles">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@mirrormuscles">
<meta name="twitter:title" content="Mirror Muscles">
<?php if(is_singular('user-progress-image')):?>
    <?php
        $mpi_options = get_option("mpi_options");
        $mpi_share_photo_desc = stripslashes_deep($mpi_options["mpi_share_photo_desc"]);
    ?>
    <meta name="twitter:text:description" content="<?php echo $mpi_share_photo_desc.' Progress on the '.date('d F Y', strtotime($post->post_date));?>">
    <meta name="twitter:image:src" content="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID));?>">

    <meta itemprop="description" content="<?php echo $mpi_share_photo_desc.' Progress on the '.date('d F Y', strtotime($post->post_date));?>">
    <meta itemprop="image" content="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID));?>">
<?php else:?>
    <meta name="twitter:text:description" content="Mirror Muscles Social Network">
    <meta name="twitter:image:src" content="<?php echo get_stylesheet_directory_uri().'/images/mm_sharing_logo.jpg';?>">
    <meta itemprop="description" content="Mirror Muscles Social Network">
    <meta itemprop="image" content="<?php echo get_stylesheet_directory_uri().'/images/mm_sharing_logo.jpg';?>">
<?php endif; ?>
<!--END Meta tags for google and twitter parser-->



<!-- BuddyPress and bbPress Stylesheets are called in wp_head, if plugins are activated -->
<?php wp_head(); ?>
<!-- Inplude google plus scripts for sharing buttons -->

<script >
  window.___gcfg = {
    lang: 'en-Us',
    parsetags: 'onload'
  };
</script>
<script type="text/javascript" src="https://sdk.clarifai.com/js/clarifai-latest.js"></script>
<script src="https://apis.google.com/js/client:platform.js" async defer></script>
<link rel="stylesheet" type="text/css" href="https://www.mirrormuscles.com/wp-content/themes/boss-child/style.css">
<link rel="stylesheet" type="text/css" href="https://www.mirrormuscles.com/wp-content/themes/boss-child/atc-style.css">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script src="https://addtocalendar.com/atc/1.5/atc.min.js" type="text/javascript"></script>
<script src="https://www.mirrormuscles.com/wp-content/themes/boss-child/js/script-for-native.js" type="text/javascript"></script>

<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-6365859513100744",
    enable_page_level_ads: true
  });
</script>
<!--<script type="text/javascript">(function () {
        if (window.addtocalendar)if(typeof window.addtocalendar.start == "function")return;
        if (window.ifaddtocalendar == undefined) { window.ifaddtocalendar = 1;
            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
            s.type = 'text/javascript';
            s.charset = 'UTF-8';
            s.async = true;
            s.src = ('https:' == window.location.protocol ? 'https' : 'http')+'://addtocalendar.com/atc/1.5/atc.min.js';
            var h = d[g]('body')[0];
            h.appendChild(s); }})();
</script>-->
</head>
<?php
global $wp_admin_bar, $bp, $current_user;
  get_currentuserinfo();
  $userLogin = $current_user->user_login;
  $thisurl = $_SERVER['REQUEST_URI'];
  $thisurl = explode('/',$thisurl);
  
  $otheruser = get_user_by('login', $thisurl[2]);
  //$otheruser = new WP_User( $otherID->ID );
  $othervendor = false;
  if ( !empty( $otheruser->roles ) && is_array( $otheruser->roles ) ) {
	foreach ( $otheruser->roles as $otherrole ){
		$otherrole = $otherrole;
		if($otherrole == 'vendor'){
		   $othervendor = true;
		}
	}	
  }	
?>

<script>
jQuery(document).ready(function(){
var url1 = '<?php echo $thisurl[1]; ?>';
var url2 = '<?php echo $thisurl[2]; ?>';
var curruser = '<?php echo $userLogin; ?>';
var othervendor = '<?php echo $othervendor; ?>';
if(url1 == 'members' && url2 != ''){

  if(url2 != curruser){
  
    if(othervendor){
    
         jQuery('#forums-personal-li').after('<li id="vendor-shop-personal-li" style=""><a id="user-vendor-shop" href="<?php echo  home_url() . '/vendors/'.$thisurl[2]; ?>">Shop</a></li>'); 
         
    }
     
  }

}
jQuery('#user-vendor-dashboard').html('Shop Dashboard'); 
jQuery('#vendor-dashboard-personal-li').after('<li id="vendor-shop-personal-li" style=""><a id="user-vendor-shop" href="<?php echo  home_url() . '/vendors/'.$userLogin; ?>">My Shop</a></li>'); 

jQuery(".store") .click(function(){
		 jQuery( ".wcv-formvalidator .tabs-tab" ).parent('li').removeClass('active');
		 jQuery( ".store").parent('li').addClass('active');
		 jQuery( ".wcv-formvalidator .tabs-content" ).toggle("Hide");
                 jQuery( ".wcv-formvalidator .tabs-content" ).removeClass( "active" );
                 jQuery( ".wcv-formvalidator .tabs-content" ).addClass( "hide-all" );	
		 jQuery( "#store" ).show();
		 jQuery( "#store" ).addClass( "active" );
		 jQuery( "#store" ).removeClass( "hide-all" );
});	
jQuery(".payment") .click(function(){
		 jQuery( ".wcv-formvalidator .tabs-tab" ).parent('li').removeClass('active');
                 jQuery( ".payment").parent('li').addClass('active');
		 jQuery( ".wcv-formvalidator .tabs-content" ).toggle("Hide");
                 jQuery( ".wcv-formvalidator .tabs-content" ).removeClass( "active" );
                 jQuery( ".wcv-formvalidator .tabs-content" ).addClass( "hide-all" );	
		 jQuery( "#payment" ).show();
		 jQuery( "#payment" ).addClass( "active" );
		 jQuery( "#payment" ).removeClass( "hide-all" );
});
jQuery(".branding") .click(function(){
		 jQuery( ".wcv-formvalidator .tabs-tab" ).parent('li').removeClass('active');
                 jQuery( ".branding").parent('li').addClass('active');
		 jQuery( ".wcv-formvalidator .tabs-content" ).toggle("Hide");
                 jQuery( ".wcv-formvalidator .tabs-content" ).removeClass( "active" );
                 jQuery( ".wcv-formvalidator .tabs-content" ).addClass( "hide-all" );	
		 jQuery( "#branding" ).show();
		 jQuery( "#branding" ).addClass( "active" );
		 jQuery( "#branding" ).removeClass( "hide-all" );
});
jQuery(".shipping") .click(function(){
		 jQuery( ".wcv-formvalidator .tabs-tab" ).parent('li').removeClass('active');
		 jQuery( ".shipping").parent('li').addClass('active');
		 jQuery( ".wcv-formvalidator .tabs-content" ).toggle("Hide");
                 jQuery( ".wcv-formvalidator .tabs-content" ).removeClass( "active" );
                 jQuery( ".wcv-formvalidator .tabs-content" ).addClass( "hide-all" );	
		 jQuery( "#shipping" ).show();
		 jQuery( "#shipping" ).addClass( "active" );
		 jQuery( "#shipping" ).removeClass( "hide-all" );
});
jQuery(".social") .click(function(){
                 jQuery( ".wcv-formvalidator .tabs-tab" ).parent('li').removeClass('active');
		 jQuery( ".social").parent('li').addClass('active');
		 jQuery( ".wcv-formvalidator .tabs-content" ).toggle("Hide");
                 jQuery( ".wcv-formvalidator .tabs-content" ).removeClass( "active" );
                 jQuery( ".wcv-formvalidator .tabs-content" ).addClass( "hide-all" );	
		 jQuery( "#social" ).show();
		 jQuery( "#social" ).addClass( "active" );
		 jQuery( "#social" ).removeClass( "hide-all" );
});
});
</script>

<script>
	jQuery(document).ready(function() {
	
		
	 	
		jQuery("#shop-personal-li a#user-shop").text("Cart");
		jQuery("#wp-admin-bar-my-account-shop a.ab-item").text("Cart");
	 	jQuery("#wp-admin-bar-my-account-shop-cart a.ab-item").text("Shopping Cart");
	 	jQuery("#wp-admin-bar-my-account-shop-history a.ab-item").text("History");
	 	jQuery("#wp-admin-bar-my-account-shop-track a.ab-item").text("Track your order");
  	});
</script>

<style>
@media (max-width:350px){
	#cometchat_userstab {
		width: 120px;
	}
	.cometchat_ccmobiletab_redirect {
		font-size: 18px !important;
		padding: 7px !important;
	}
	#cometchat_userstab_popup {
		overflow: auto !important;
	}
}
</style>
	<?php
	global $rtl;
	$logo	 = ( boss_get_option( 'logo_switch' ) && boss_get_option( 'boss_logo', 'id' ) ) ? '1' : '0';
	$inputs	 = ( boss_get_option( 'boss_inputs' ) ) ? '1' : '0';
	$boxed	 = boss_get_option( 'boss_layout_style' );
	?>


	<body <?php body_class(); ?> data-logo="<?php echo $logo; ?>" data-inputs="<?php echo $inputs; ?>" data-rtl="<?php echo ($rtl) ? 'true' : 'false'; ?>">

		<?php do_action( 'buddyboss_before_header' ); ?>
		<div id="scroll-to"></div>
		<header id="masthead" class="site-header" data-infinite="<?php echo ( boss_get_option( 'boss_activity_infinite' ) ) ? 'on' : 'off'; ?>">

			<div class="header-wrap">
				<div class="header-outher">
					<div class="header-inner">
						<?php get_template_part( 'template-parts/header-fluid-layout-column' ); ?>
						<?php get_template_part( 'template-parts/header-middle-column' ); ?>
						<?php get_template_part( 'template-parts/header-profile' ); ?>
					</div><!-- .header-inner -->
				</div><!-- .header-wrap -->
			</div><!-- .header-outher -->

			<div id="mastlogo">
				<?php get_template_part( 'template-parts/header-logo' ); ?>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			</div><!-- .mastlogo -->

		</header><!-- #masthead -->

		<?php do_action( 'buddyboss_after_header' ); ?>

		<?php get_template_part( 'template-parts/header-mobile' ); ?>

		<!--#panels closed in footer-->
		<div id="panels" class="<?php echo (boss_get_option( 'boss_adminbar' )) ? 'with-adminbar' : ''; ?>">
				
			<!-- Left Panel -->
			<?php get_template_part( 'template-parts/left-panel' ); ?>

			<!-- Left Mobile Menu -->
			<?php get_template_part( 'template-parts/left-mobile-menu' ); ?>

			<div id="right-panel">
				<div id="right-panel-inner">
					<div id="main-wrap"> <!-- Wrap for Mobile content -->
						<div id="inner-wrap"> <!-- Inner Wrap for Mobile content -->
                        
							<?php /*?><?php if( is_home() ) { 
									  if(is_user_logged_in()){
										get_search_form (); 
									  }
								  }?><?php */?>
                            
							<?php do_action( 'buddyboss_inside_wrapper' ); ?>

							<div id="page" class="hfeed site" 
	                            <?php 
	                            	$mm_regpage_options = get_option("mm_regpage_options");

	                            	echo ((is_home() || is_front_page()) && !is_user_logged_in()) ? 'style="background:url('.$mm_regpage_options["regpage_image_std"].') no-repeat center center fixed; background-size: cover;"':'';
	                            	
	                            	echo (is_page('fill-in-required-fields')) ? 'style="background:url('.$mm_regpage_options['regpage_image'].') no-repeat center center fixed; background-size: cover;"' : '';
	                            ?>
                            >
								<div id="main" class="wrapper">