<?php
/**
 * The template for displaying Author bios.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

<div class="author-info">
	<div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentythirteen_author_bio_avatar_size', 74 ) ); ?>
	</div><!-- .author-avatar -->
	<div class="author-description">
		<h2 class="author-title"><?php printf( __( 'About %s', 'twentythirteen' ), get_the_author() ); ?></h2>
		<p class="author-bio">
			<!--<?php the_author_meta( 'description' ); ?>-->
			<!--<a href="#" class="next-post" style="color: #fff;"></a>
			<a href="#" class="previous-post"></a>-->
			<div class="previous-post">
				<?php previous_post('&laquo; &laquo; %',
				 'Previous Post', 'no'); ?>
			</div>
			<div class="next-post">
				<?php next_post('% &raquo; &raquo;',
				 'Next Post', 'no'); ?>
			</div>
		</p>
		<!--<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" style="color: #444;">
			<?php printf( __( 'View all posts by %s <span class="meta-nav"></span>', 'twentythirteen' ), get_the_author() ); ?>
		</a>-->
	</div><!-- .author-description -->
</div><!-- .author-info -->