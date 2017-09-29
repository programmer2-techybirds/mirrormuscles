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

		<div class="footer-inner">
			<?php get_template_part( 'template-parts/footer-copyright' ); ?>
            <?php if(is_user_logged_in()&&!is_page('fill-in-required-fields')):?>
            	<a href="<?php echo home_url().'/faqs';?>" class="faqs-link" target="_blank">FAQ
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
			<?php endif;?>
			<?php get_template_part( 'template-parts/footer-links' ); ?>
		</div><!-- .footer-inner -->

	</div><!-- .footer-inner-bottom -->

	<?php do_action( 'bp_footer' ) ?>

</footer><!-- #colophon -->
</div><!-- #right-panel-inner -->
</div><!-- #right-panel -->

</div><!-- #panels -->

<?php wp_footer(); ?>

<script src="https://apis.google.com/js/client:platform.js" async defer></script>

<?php if(is_page('dashboard')): ?>
	<script src="/cometchat/js.php?type=core&name=embedcode" type="text/javascript"></script><script>var iframeObj = {};iframeObj.module="synergy";iframeObj.style="min-height:420px;min-width:300px;";iframeObj.src="/cometchat/cometchat_popout.php"; if(typeof(addEmbedIframe)=="function"){addEmbedIframe(iframeObj);}</script>
<?php endif;?>

</body>
</html>