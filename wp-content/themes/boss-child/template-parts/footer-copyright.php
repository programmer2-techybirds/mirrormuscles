<?php
$boss_copyright	 = boss_get_option( 'boss_copyright' );
$show_copyright	 = boss_get_option( 'footer_copyright_content' );

if ( $show_copyright && $boss_copyright ) {
	?>

	<div class="footer-credits <?php if ( !has_nav_menu( 'secondary-menu' ) ) : ?>footer-credits-single<?php endif; ?>">
		
		<p class="footer-credits footer-credits-single">
			<?php //echo $boss_copyright; >?>
            <?php _e( "Copyright &copy;", 'boss' ); ?> <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
		</p>
	</div>

	<?php
}

?>
