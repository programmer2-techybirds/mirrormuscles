<?php
/*
Template Name: Measurments
*/
get_header(); ?>
<div id="buddypress">
<div id="primary">
    <div id class="template measurments-template">
                <div class="col-sm-8 col-sm-offset-2">
                    
                    <?php if (have_posts()) : while (have_posts()) : the_post();?>
                    <h1 class="text-center" style="margin-top:0;"><?php the_title();?></h1>
                    <?php the_content(); ?>
                    <?php endwhile; endif; ?>
                </div>
    </div>
    
</div>
</div>
<?php get_footer(); ?>
