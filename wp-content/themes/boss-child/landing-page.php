<?php

/**

 * Template Name: Landing Page Template

 *

 * Description: A page template that provides a key component of WordPress as a CMS

 * by meeting the need for a carefully crafted introductory page. The front page template

 * in BuddyBoss consists of a page content area for adding text, images, video --

 * anything you'd like -- followed by front-page-only widgets in one or two columns.

 *

 * @package WordPress

 * @subpackage Boss

 * @since Boss 1.0.0

 */



get_header();

$uid = bp_loggedin_user_id();

?>



<?php if ( is_active_sidebar('home-right') ) : ?>

	<div class="page-right-sidebar">

<?php else : ?>

	<div class="page-full-width">

<?php endif; ?>

    

		<!-- Frontpage Slider -->

		<!--?php get_template_part( 'content', 'slides' ); ?-->

		<?php if(!is_user_logged_in()):?>

  			<?php

				$mm_regpage_options = get_option("mm_regpage_options");

        		$regpage_image_std = $mm_regpage_options["regpage_image_std"];

        		$regpage_image_enc = $mm_regpage_options["regpage_image_enc"];

        		$regpage_title_std = $mm_regpage_options["regpage_title_std"];

        		$regpage_title_enc = $mm_regpage_options["regpage_title_enc"];

        		$regpage_desc_std = $mm_regpage_options["regpage_desc_std"];

        		$regpage_desc_enc = $mm_regpage_options["regpage_desc_enc"];
				
  			?>

			<div id="primary" class="site-content entry-content container">

				<input type="hidden" id="imgstd" value="<?php echo $regpage_image_std;?>">

				<input type="hidden" id="imgenc" value="<?php echo $regpage_image_enc;?>">

				<input type="hidden" id="imgactive" value="enc">

			

				<table>

					<tr>

						<td>

							<div class="unregistered-wrapper active">

								<div class="col-md-12">

									<h3><?php echo nl2br($regpage_title_std);?></h3>

									<p><?php echo nl2br($regpage_desc_std);?></p>

								</div>

								<div class="col-md-6">

									<a href="<?php echo site_url(); ?>/my-account/" class="btn large cs-btn">SIGN UP FREE!</a>

								</div>

							</div>



							<div class="unregistered-wrapper">

								<div class="col-md-12">

									<h3><?php echo nl2br($regpage_title_enc);?></h3>

									<p><?php echo nl2br($regpage_desc_enc);?></p>

								</div>

								<div class="col-md-6">

									<a href="<?php echo site_url(); ?>/my-account/" class="btn large cs-btn">SIGN UP FREE!</a>

								</div>

							</div>

						</td>

					</tr>

				</table>

				<div id="about" class="about primary-sec">

						<div class="container">

					    	<div class="row">

					        	<div class="col-sm-6">

					            	<div class="about-text">

					                	<h2><?php echo get_field('about_main_heading',options);?><br><strong><?php echo get_field('about_sub_heading',options);?></strong></h2>

					                    <p><?php echo get_field('about_description',options);?></p>

					                </div>

					            </div>

								<?php $image = get_field('about_image',options); ?>

					            <div class="col-sm-6">

					            	<div class="about-image">

					                	<img src="<?php  echo $image; ?>"/>

					                </div>

					            </div>

					        </div>

					    </div>

					</div>

					<?php $image1 = get_field('first_col_icon',options); ?>

					<?php $image2 = get_field('second_col_icon',options); ?>

					<?php $image3 = get_field('third_column_icon',options); ?>

					<div style="text-align:center;">

					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

					<script>

					  (adsbygoogle = window.adsbygoogle || []).push({

						google_ad_client: "ca-pub-9934042716987932",

						enable_page_level_ads: true

					  });

					</script>

					</div>

					<div id="services" class="services primary-sec">

						<div class="container">

					    	<div class="row">

					        	<div class="col-sm-4">

					            	<div class="fitnes">

					                	<img src="<?php  echo $image1; ?>" alt="">

					                    <h3><?php echo get_field('first_col_heading',options);?></h3>

					                    <p><?php echo get_field('first_col_description',options);?> </p>

					                </div>

					            </div>

					            <div class="col-sm-4">

					            	<div class="fitnes">

					                	<img src="<?php  echo $image2; ?>" alt="">

					                    <h3><?php echo get_field('second_column_heading',options);?></h3>

					                    <p><?php echo get_field('second_column_description',options);?> </p>

					                </div>

					            </div>

					            <div class="col-sm-4">

					            	<div class="fitnes">

					                	<img src="<?php  echo $image3; ?>" alt="">

					                    <h3><?php echo get_field('third_column_heading',options);?></h3>

					                    <p><?php echo get_field('third_column_description',options);?> </p>

					                </div>

					            </div>

					        </div>

					    </div>

					</div>

					

					<div id="testimonials" class="slider primary-sec">

						<div id="myCarousel2" class="carousel slide" data-ride="carousel">

						  <!-- Indicators -->

						  

						  <!-- Wrapper for slides -->

						  <div class="carousel-inner" role="listbox">

						  <?php

if( have_rows('testimoinials',options) ){

	$i = 0;

    while ( have_rows('testimoinials',options) ){

    the_row();?>

	<?php $author_image = get_sub_field('author_image',options); ?>

						    <div class="item <?php if(($i++) == 0) echo 'active'; ?>">

						      	<h2><?php echo get_field('testimonial_heading',options);?> </h2>

						      <div class="swap-slide">

						      	<div class="container">

						      	<img src="<?php  echo $author_image; ?>" alt="">

						        <p class="slider-text"><?php echo the_sub_field('author_review',options);?></p>

						        <p><strong><?php echo the_sub_field('author_name',options);?> </strong>- <?php echo the_sub_field('author_role',options);?></p>

						        </div>

						      </div>

						    </div>

							<?php

    }

}

?>

						  </div>

						  <!-- Left and right controls -->

						  <div class="container">

						  <a class="left carousel-control" href="#myCarousel2" data-slide="prev">

						    <span class="icon-prev"></span>

						  </a>

						  <a class="right carousel-control" href="#myCarousel2" data-slide="next">

						    <span class="icon-next"></span>

						  </a>  

						  </div>

					</div>

				</div> 



				<script type="text/javascript">



					var imgstd = $('#imgstd').val();

					var imgenc = $('#imgenc').val();

					

					setInterval(function(){

						var imgactive = $('#imgactive').val();

			

						if(imgactive == 'std'){

							$('#page').css('background-image', 'url("' + imgstd + '")');

							$('#imgactive').val('enc');

						}

						else{

							$('#page').css('background-image', 'url("' + imgenc + '")');

							$('#imgactive').val('std');

						}



						$('.unregistered-wrapper').each(function(){

							if($(this).hasClass('active'))

								$(this).removeClass('active');

							else

								$(this).addClass('active');

						});

					}, 10000);

				</script>		

			</div>

		<?php else: ?>

			<?php

				$uid = $current_user->ID;

				$member_type = bp_get_member_type($uid);

				$next_training = get_next_training_session($uid);

				$mm_frontpage_options = get_option("mm_frontpage_options");

	            $next_training_image = $mm_frontpage_options["next_training_image"];

			?>

			<div id="buddypress">

				<div id="primary" class="site-content entry-content container">

					

					<div class="row">

						<div class="col-md-12 text-center">

							<?php if(wp_is_mobile()):?>

								<?php print_video_container();?>

							<?php endif;?>

				        </div>

			        </div>

				

					<div class="row">

						<div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">

							<h3 class="template-title">Update Your Activity</h3>

							<?php bp_get_template_part( 'activity/post-form' ); ?>

						</div>

					<div class="clearfix"></div><hr>

						<script>

							jQuery(document).ready(function(){
								jQuery('.activity-content .activity-meta a').click(function() {
									var res = jQuery(this).attr('id');
									var result = res.substring(text.indexOf('-') +9);
								});
							});
							
							jQuery('.buddystream_share_button.mylocation').css("display","none");
						</script>
						<style type="text/css">
							#buddypress span.bp-verified img{
								height: 15px !important;
							}
							.ac-reply-content .buddyboss-comment-media-add-photo-button{
								height: 40px !important;
							}
						</style>
						

						<div class="col-md-12 fronpage-news-feed">

							

							<h3 class="template-subtitle" style="margin-top: 5px; color:#000;">News feeds</h3>


							<?php //echo do_shortcode('[activity-stream title=&nbsp; max="1000" allow_posting=1 per_page=50 pagination=1 display_comments="stream"]');?>

                            <?php 

								$friendidlist = '';

								$friendids = friends_get_friend_user_ids($uid); 

								foreach($friendids as $friendid){

									$friendidlist .= $friendid.',';

								}
								error_reporting(0);
								$a=$current_user->ID;
								$friendidlist=array_push($friendidlist,$a);
								$friendidlist = substr($friendidlist, 0, -1); 

								?>
								
                                <?php echo do_shortcode('[activity-stream title=&nbsp; max="1000" allow_posting=1 per_page=50 pagination=1 display_comments="stream" user_id='.$friendidlist.']'); ?>   

		

						</div>

						<div class="clearfix"></div><hr>

					</div>

						<script>
							jQuery('.acomment-reply.bp-primary-action').on("click",function() {
								var str = jQuery(this).attr("id");
								var res = str.split("-");
								//alert(res[2]);
								jQuery('#ac-form-'+res[2]).css("display","block");
							});
						</script>

				</div><!--/#primary-->

			</div><!--/#buddypress-->

		<?php endif; ?>

     <?php if ( is_active_sidebar('home-right') ) : get_sidebar('home-right'); endif; ?>

</div><!-- .page-left-sidebar -->

<?php get_footer(); ?>