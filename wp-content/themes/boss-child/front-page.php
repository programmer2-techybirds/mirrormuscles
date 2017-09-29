<?php

/**

 * Template Name: Front Page Template

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
<style>
div#about {
    display: none;
}
div#services {
    display: none;
}
div#testimonials {
    display: none;
}
.tbl{
display:none; 
}

</style>
<?php endif; ?>

    

		<!-- Frontpage Slider -->

		<!--?php get_template_part( 'content', 'slides' ); ?-->

		<?php if(!is_user_logged_in()): ?>
		
		
		<div class="slider_remove">
  			<?php

				/*$mm_regpage_options = get_option("mm_regpage_options");

        		$regpage_image_std = $mm_regpage_options["regpage_image_std"];

        		$regpage_image_enc = $mm_regpage_options["regpage_image_enc"];

        		$regpage_title_std = $mm_regpage_options["regpage_title_std"];

        		$regpage_title_enc = $mm_regpage_options["regpage_title_enc"];

        		$regpage_desc_std = $mm_regpage_options["regpage_desc_std"];

        		$regpage_desc_enc = $mm_regpage_options["regpage_desc_enc"];*/
				
  			?>

			<div id="primary" class="site-content entry-content container">

				<input type="hidden" id="imgstd" value="<?php //echo $regpage_image_std;?>">

				<input type="hidden" id="imgenc" value="<?php //echo $regpage_image_enc;?>">

				<input type="hidden" id="imgactive" value="enc">
				
			<?php
				$individual_video = get_option('all_individual_content');
				$pt_video = get_option('all_pt_content');
				$gym_video = get_option('all_gym_content');
			?>

				<table class="tbl">

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



						

			</div>
			</div>
			<style>
				.slider_remove, div#individual, div#personal, div#gym, div#primary {
					display: none;
				}
				h2#pt_ttl {
					padding: 0;
				}
				/* div#inner-wrap.decrease_height_inner-wrap {
					height: 485px!important;
				} */
			</style>
			
			<?php
					$all_home_content = get_option('all_home_content');
					$all_individual_content = get_option('all_individual_content');
					$all_pt_content = get_option('all_pt_content');
					$all_gym_content = get_option('all_gym_content');
			
			?>
			<div id="row">
				<div id="slider_home_page" class="slider_main_home">
					<div class="videoo" id="individual_frame">
						<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php //echo $individual_video['video_individual']; ?>" frameborder="0" allowfullscreen></iframe>-->
						<div class="fullscreen">
						  <div class="video">
							<div class="wrapper">
							  <iframe src="//player.vimeo.com/video/<?php echo $individual_video['video_individual'];?>?title=0&byline=0&portrait=0" width="1280" height="600" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>
						  </div>
						</div>

					</div>
					<div class="videoo" id="personal_frame">
						<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php //echo $pt_video['video_pt']; ?>" frameborder="0" allowfullscreen></iframe>-->
						<div class="fullscreen">
						  <div class="video">
							<div class="wrapper">
							  <iframe src="//player.vimeo.com/video/<?php echo $pt_video['video_pt'];?>?title=0&byline=0&portrait=0" width="1280" height="600" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>
						  </div>
						</div>
					</div>
					<div class="videoo" id="gym_frame">
						<!--<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php //echo $gym_video['video_gym']; ?>" frameborder="0" allowfullscreen></iframe> -->
						<div class="fullscreen">
						  <div class="video">
							<div class="wrapper">
							  <iframe src="//player.vimeo.com/video/<?php echo $gym_video['video_gym'];?>?title=0&byline=0&portrait=0" width="1280" height="600" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>
						  </div>
						</div>
					</div>
				</div>
				</div>
				<div class="landing col-md-12" id="landing_main">
					<div id="main_landing"><h2 class="landing-tital" align="center"><?php echo $all_home_content['main_title'];  ?></h2></div>
					<div class="col-md-4">
						<div class="col-md-12" id="go_individual">
							<img class="main-img img-responsive" src="<?php echo $all_home_content['individual_img'];  ?>" alt="" height="300" width="400">
						</div>
						<div class="col-md-12">
							
							<h2 class="landing-tital" align="center"><?php echo $all_home_content['individual_title'];  ?></h2>
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-12" id="go_pt">
							<img class="main-img img-responsive" src="<?php echo $all_home_content['pt_img'];  ?>" alt="" height="300" width="400">
						</div>
						<div class="col-md-12">
							<h2 class="landing-tital" align="center"><?php echo $all_home_content['pt_title'];  ?></h2>
							
						</div>
					</div>
					<div class="col-md-4">
						<div class="col-md-12" id="go_gym">
							<img class="main-img img-responsive" src="<?php echo $all_home_content['gym_img'];  ?>" alt="" height="300" width="400">
						</div>
						<div class="col-md-12">
							<h2 class="landing-tital" align="center"><?php echo $all_home_content['gym_title'];  ?></h2>
							
						</div>
					</div>
				</div>
				
				<div class="landing col-md-12" id="individual">
				<div><h2 class="landing-tital" align="center" id="pt_ttl">Individual</h2></div>
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img1'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title1'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img2'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title2'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img3'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title3'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img4'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title4'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img5'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title5'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img6'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title6'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img7'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title7'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img8'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title8'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img9'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title9'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img10'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title10'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img11'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title11'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_individual_content['icon_img12'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_individual_content['icon_title12'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
					</div>
					</div>
				</div>
				
				<div class="landing col-md-12" id="personal">
				<div><h2 class="landing-tital" align="center" id="pt_ttl">Personal Trainer</h2></div>
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_1'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_1'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_2'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_2'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_3'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_3'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_4'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_4'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_5'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_5'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_6'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_6'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_7'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_7'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_8'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_8'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_9'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_9'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_10'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_10'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_11'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_11'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_12'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_12'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_13'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_13'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_14'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_14'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_pt_content['icon_img_15'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_pt_content['icon_title_15'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
				</div>
				
				<div class="landing col-md-12" id="gym">
				<div><h2 class="landing-tital" align="center" id="pt_ttl">Gym</h2></div>
					<div class="row">
						<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_1_1'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_1_1'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_2_2'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_2_2'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_3_3'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_3_3'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_4_4'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_4_4'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_5_5'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_5_5'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_6_6'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_6_6'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_7_7'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_7_7'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_8_8'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_8_8'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_9_9'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_9_9'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_10_10'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_10_10'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_11_11'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_11_11'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_12_12'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_12_12'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
					
					<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_13_13'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_13_13'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_14_14'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_14_14'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						<div class="col-md-4 col-sm-4">
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<img class="icon-img img-responsive" src="<?php echo $all_gym_content['icon_img_15_15'];  ?>" alt="" height="60" width="60">
							</div>
							<div class="col-md-5">
								<div class="pt-des"><?php echo $all_gym_content['icon_title_15_15'];  ?></div>
							</div>
							<div class="col-md-2"></div>
						</div>
						</div>
					</div>
				</div>
<style>
/* .landing.col-md-12 {
    background: #262626;
} */
#go_individual, #go_pt, #go_gym{
	cursor:pointer;
}

.landing-tital
{
	color: #aa2d2a;
    margin-bottom: 3%;
    margin-top: 3%;
}

.pt-des
{
    font-size: 16px;
    margin-top: 7px;
    margin-left: -18%;
    margin-bottom: 15px;
	    color: #c8c5c5;
}
.videoo
{
	margin-left: 25%;
}
@media only screen and (max-width: 500px) {
    .main-img
	{
	height: 80%;
    width: 100%;
	}
	.icon-img{
		    margin-left: 36%;
	}
	.pt-des {
    
    margin-left: 4%!important;
    
}
.videoo
{
	margin-left: 0%;
}
}
</style>
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
							
							$(".main-img").click(function(){
								$("#individual").show();
								$("#landing_main").hide();
							});
						</script>

				</div><!--/#primary-->

			</div><!--/#buddypress-->

		<?php endif; ?>

     <?php if ( is_active_sidebar('home-right') ) : get_sidebar('home-right'); endif; ?>

</div><!-- .page-left-sidebar -->

<?php get_footer(); ?>
