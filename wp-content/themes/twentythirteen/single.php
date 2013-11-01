<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

<!--<div class="image-credit">
	<span>Image Credit:</span>
	<a href="<?php echo $custom_fields["Image Credit URL"][0] ?>" target="_blank"><?php echo $custom_fields["Image Credit"][0] ?></a>
</div>-->
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', get_post_format() ); ?>
				<?php twentythirteen_post_nav(); ?>
				<div id="disqus_thread" style="width: 960px; margin: 0 auto;"></div>
				<script type="text/javascript">
				    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
				    var disqus_shortname = 'sqwiggle'; // required: replace example with your forum shortname
			    
				    /* * * DON'T EDIT BELOW THIS LINE * * */
				    (function() {
					var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
					(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				    })();
				</script>
				<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
				

			<?php endwhile; ?>
		</div>
	</div>
	<!--<h1 class="rp-header">More Posts by the Sqwiggle Team</h1>
		<div class="recent-posts-container">
			<?php
				$args = array( 'numberposts' => '3' );
				$recent_posts = wp_get_recent_posts( $args );
				foreach( $recent_posts as $recent ){
					echo '<div class="recent-post rp-margin"><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </div> ';
				}
			?>
		</div>-->
<?php get_footer(); ?>