<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>
<?php $custom_fields = get_post_custom($post_id); ?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
				<?php twentythirteen_post_nav(); ?>
				
				<!--
				<div style="margin: 0 auto; width: 850px; background-size:950px 633px; height: 100px; background-position: center; margin-bottom: 100px;" class="sqwiggle_cta">
					<a href="http://www.sqwiggle.com?utm_campaign=blog" style="float: left"><img src="/wp-content/uploads/2013/11/sqwiggle_logo.png" alt="Sqwiggle" />Work has changed. Join the Sqwiggle revolution.</a>
				</div>
				-->
				<div class="post_cta">
					<?php echo $custom_fields["CTA"][0] ?>
				</div>
				<div class="subscribe_cta">
					<!--HubSpot Call-to-Action Code -->
					<span class="hs-cta-wrapper" id="hs-cta-wrapper-d6dbc103-aee5-4f41-a371-249473203556">
					    <span class="hs-cta-node hs-cta-d6dbc103-aee5-4f41-a371-249473203556" id="hs-cta-d6dbc103-aee5-4f41-a371-249473203556">
						<!--[if lte IE 8]><div id="hs-cta-ie-element"></div><![endif]-->
						<a href="http://cta-redirect.hubspot.com/cta/redirect/329768/d6dbc103-aee5-4f41-a371-249473203556"><img class="hs-cta-img" id="hs-cta-img-d6dbc103-aee5-4f41-a371-249473203556" style="border-width:0px;" src="https://no-cache.hubspot.com/cta/default/329768/d6dbc103-aee5-4f41-a371-249473203556.png" /></a>
					    </span>
					    <script charset="utf-8" src="https://js.hscta.net/cta/current.js"></script>
						<script type="text/javascript">
						    hbspt.cta.load(329768, 'd6dbc103-aee5-4f41-a371-249473203556');
						</script>
					</span>
					<!-- end HubSpot Call-to-Action Code -->
				</div>
				<div id="disqus_thread" style="width: 850px; margin: 0 auto;"></div>
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