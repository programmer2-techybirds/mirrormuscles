<?php
/*
Template Name: Noticeboard
*/

get_header();

	if (isset($_GET) && !empty($_GET) && isset($_GET['show_gym_noticeboard']) ) {
        $member_id = $current_user->id;
        $gym_id = $_GET['gym_id'];
        $is_gym_member = user_is_connected($gym_id);
    }else{
    	$member_id = $current_user->ID;
        $gym_id = $current_user->id;
        $is_gym_member = false;
    }

?>

<?php if( ((bp_get_member_type($member_id) == ('standard'||'pt')) && $is_gym_member) || bp_get_member_type($member_id) == ('gym') ):?>
<div id="buddypress">
<div id="primary">
<div class="template-noticeboard">
    <div class="site-content">
    	<h3 class="template-title"><?php echo ($is_gym_member) ? get_fullname($gym_id) : '';?> Noticeboard</h3>
		<?php if(bp_get_member_type($member_id) == 'gym'):?>
	    	<form class="form-horizontal" name="new-noticeboards-post" id="new-noticeboards-post" action="<?php echo WP_PLUGIN_URL."/mirror-muscles/handler.php";?>" method="post" enctype="multipart/form-data">
            	<h3 class="template-subtitle" style="margin-top: 0;">Add new notice</h3>
                <div class="form-group">
                    <label for="noticeboard-title" class="col-md-3 control-label">Title</label>
                    <div class="col-md-9 input-group">
                    	<input type="text" class="form-control" name="noticeboard-title" id="noticeboard-title" placeholder="Title" required>
               		</div>
               	</div>
               	<div id="error-noticeboard-title" class="col-md-12"></div>
                
                <div class="form-group">
                    <label for="noticeboard-content" class="col-md-3 control-label">Content</label>
                    <div class="col-md-9 input-group">
                    	<textarea rows="8" class="form-control" name="noticeboard-content" id="noticeboard-content" placeholder="Content" required></textarea>
                	</div>
               	</div>
				<div id="error-noticeboard-content" class="col-md-12"></div>

				<div class="form-group">
					<label for="noticeboard-content" class="col-md-3 control-label">Image</label>
                    <div class="col-md-9 input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-file">
                                Browse&hellip; <input type="file" name="noticeboard-image" id="noticeboard-image">
                            </span>
                        </span>
                        <input id="filename-display" type="text" class="form-control" readonly>
                    </div>
                </div>
				<div id="error-noticeboard-image" class="col-md-12"></div>
                
                
                <div class="form-group">
                    <label for="noticeboard-link" class="col-md-3 control-label">External link</label>
                    <div class="col-md-9 input-group">
                    	<input type="text" class="form-control" name="noticeboard-link" id="noticeboard-link" placeholder="External link">
                    </div>
                </div>
                <div id="error-noticeboard-link" class="col-md-12"></div>
				
				<div class="col-md-12">
					<input type="hidden" name="save-noticeboards-post" value=""/>
					<button type="button" class="btn danger" id="clear-noticeboards-post-edit" style="display:none;">Cancel</button>
					<button type="submit" class="btn" id="save-noticeboards-post">Save</button>
	            </div>

	        </form>
	        <div class="clearfix"></div>
	        <hr>
    	<?php endif;?>

        <div id="noticeboard-posts">
			<?php
				
				if ( get_query_var('paged') )
				    $paged = get_query_var('paged');
				elseif ( get_query_var('page') ) // 'page' is used instead of 'paged' on Static Front Page
				    $paged = get_query_var('page');
				else
					$paged = 1;
				

				$custom_query_args = array(
				    'post_type' => 'noticeboards', 
				    'posts_per_page' => 10,
				    'paged' => $paged,
				    'author' => $gym_id,
				    'post_status' => 'publish',
				    'order' => 'DESC',
				    'orderby' => 'date'
				);

				$custom_query = new WP_Query( $custom_query_args );

				$counter = 0;

				if ( $custom_query->have_posts() ) :
				    while( $custom_query->have_posts() ) : $custom_query->the_post(); $counter++?>
						<div class="notice-post-container col-md-12">
							<div class="col-md-4 <?php echo ($counter%2 == 0) ? 'pull-right' : '';?>">
								<div class="notice-post-image">
									<img src="<?php the_post_thumbnail_url();?>">
								</div>
							</div>

					        <div class="col-md-8">
					            <h3 class="notice-post-title"><?php the_title(); ?></h3>
					            <small>
					            	<span class="notice-post-date"><?php the_time('F jS, Y') ?></span>,&nbsp;
					            	<a class="notice-post-author" href="<?php echo bp_core_get_user_domain(get_the_author_meta('ID'))?>">
					            		<?php echo get_fullname(get_the_author_meta('ID'));?>
					            	</a>
					            	<?php if( get_the_author_meta('ID') == $current_user->ID ):?>
					            		&nbsp;<i data-post="<?php echo get_the_ID();?>" class="fa fa-lg fa-pencil edit-noticeboards-post"></i>&nbsp;
					            		<i data-post="<?php echo get_the_ID();?>" class="fa fa-lg fa-trash delete-noticeboards-post"></i>
					            	<?php endif;?>
					            </small>
					            <div class="notice-post-content"><?php the_content();?></div>
					            <p class="notice-post-link"><a href="http://<?php echo get_post_meta(get_the_ID(), 'noticeboard-link', true);?>" target="popup" 
  onclick="window.open('http://<?php echo get_post_meta(get_the_ID(), 'noticeboard-link', true);?>','popup','width=600,height=600'); return false;"><?php echo get_post_meta(get_the_ID(), 'noticeboard-link', true);?></a></p>
					        </div>
				        </div>

				    <?php
				    endwhile;
				    ?>

				    <?php if ($custom_query->max_num_pages > 1) : // custom pagination  ?>
				        <?php
				        $orig_query = $wp_query; // fix for pagination to work
				        $wp_query = $custom_query;
				        ?>
				        <nav class="prev-next-posts col-md-12">
				            <div class="prev-posts-link pull-left">
				                <?php echo get_next_posts_link( 'Older posts', $custom_query->max_num_pages ); ?>
				            </div>
				            <div class="next-posts-link pull-right">
				                <?php echo get_previous_posts_link( 'Newer posts' ); ?>
				            </div>
				        </nav>
				        <?php
				        $wp_query = $orig_query; // fix for pagination to work
				        ?>
				    <?php endif; ?>

				<?php
				    wp_reset_postdata(); // reset the query 
				else:
				    echo '<div id="message" class="info"><p>Sorry, no notices were found.</p></div>';
				endif;
			?>

        </div><!--#noticeboard-posts-->
    </div><!--.site-content-->
</div><!--.template-noticeboard-->
</div><!--#primary-->
</div><!--#buddypress-->
<?php else: wp_redirect(home_url()); endif;?>
<?php get_footer(); ?>