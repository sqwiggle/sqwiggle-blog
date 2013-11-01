<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php /*if ( has_post_thumbnail() && ! post_password_required() ) : ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail(); ?>
		</div>
		<?php endif; */?>

		<?php if ( is_single() ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
		<?php endif; // is_single() ?>
		<span class="author-meta">by &nbsp;<a href="<?php the_author_url(); ?>" target="_blank"><?php the_author(); ?></a>, <?php the_author_description(); ?></span>
		<div class="share-meta">
			<span style="font-size: 14px; padding-right: 4px; color: #cfcfcf;">Share</span>
			<a href='https://twitter.com/share?url=&text="<?php the_title(); ?>" - <?php echo urlencode(get_permalink($post->ID)); ?>&via=sqwiggle&count=horizontal' class="custom-tweet-button" target="_blank" data-via="sqwiggle" data-lang="en"></a>
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink($post->ID)); ?>" target="_blank" class="custom-facebook-button"></a>
		</div>
		<!--<div class="entry-meta">
			<?php twentythirteen_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
		</div>-->
	</header><!-- .entry-header -->
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<?php the_content( __( 'Read More', 'twentythirteen') ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
		<!-- .entry-content -->
		<?php endif; ?>
		<!--
		<footer class="entry-meta">
			<?php if ( comments_open() && ! is_single() ) : ?>
				<div class="comments-link">
					<?php comments_popup_link( '<span class="leave-reply">' . __( 'Talk to Us!', 'twentythirteen' ) . '</span>', __( 'One comment so far', 'twentythirteen' ), __( 'View all % comments', 'twentythirteen' ) ); ?>
				</div><!-- .comments-link -->
			<?php endif; // comments_open() ?>
	
			<?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
				<?php get_template_part( 'author-bio' ); ?>
			<?php endif; ?>
		</footer>
		<!-- .entry-meta -->
	</div>
</article><!-- #post -->
