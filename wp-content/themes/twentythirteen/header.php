<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="initial-scale=1">
    
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="shortcut icon" type="image/x-icon" href="/wp-content/uploads/2013/11/favicon.ico" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-38824412-3']);
	_gaq.push(['_trackPageview']);
      
	(function() {
	  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
      
      </script>
	<!-- AddThis Smart Layers BEGIN -->
	<!-- Go to http://www.addthis.com/get/smart-layers to customize -->
	<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-526fcbfa63f48451"></script>
	<script type="text/javascript">
	  addthis.layers({
	    'theme' : 'transparent',
	    'share' : {
	      'position' : 'left',
	      'numPreferredServices' : 5
	    }, 
	    'follow' : {
	      'services' : [
		{'service': 'facebook', 'id': 'sqwiggleinc'},
		{'service': 'twitter', 'id': 'sqwiggle'},
		{'service': 'google_follow', 'id': 'b/110581722177764063021/+Sqwiggle/posts'}
	      ]
	    },  
	    'whatsnext' : {}  
	  });
	</script>
	<!-- AddThis Smart Layers END -->
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "175f0c38-ca6a-43fa-bc10-8c6fd617f32c", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
</head>
<?php $custom_fields = get_post_custom($post_id); ?>
<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header" style="background-position: center; background-size:cover; background-image: url('<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>')" role="banner">
			<div class="image-credit">
				<span>Image Credit:</span>
				<a href="<?php echo $custom_fields["Image Credit URL"][0] ?>" target="_blank"><?php echo $custom_fields["Image Credit"][0] ?></a>
			</div>
			<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<h1 class="site-title"></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</a>

			<div id="navbar" class="navbar">
				<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<!--<h3 class="menu-toggle"><?php _e( 'Menu', 'twentythirteen' ); ?></h3>
					<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentythirteen' ); ?>"><?php _e( 'Skip to content', 'twentythirteen' ); ?></a>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>-->
					<a href="http://www.sqwiggle.com" alt="Sqwiggle" target="_blank" style="margin-top: 40px; padding-bottom: 20px; float: left;"><img src="/wp-content/uploads/2013/10/sqwiggle_logo.png" alt="Sqwiggle Logo" /></a>
					<form action="http://feedburner.google.com/fb/a/mailverify" style="padding-bottom: 20px; method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=sqwiggle', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" class="subscribe_form">
						<input type="text" name="email" class="subscribe_input" onfocus="if (this.value==this.defaultValue) this.value = ''" onblur="if (this.value=='') this.value = this.defaultValue" value="Subscribe via Email">
						<input type="hidden" value="sqwiggle" name="uri">
						<input type="hidden" name="loc" value="en_US">
						<input type="submit" class="subscribe_submit" value="Subscribe">
					</form>
					<!--<?php get_search_form(); ?>-->
				</nav><!-- #site-navigation -->
			</div><!-- #navbar -->
		</header><!-- #masthead -->

		<div id="main" class="site-main">
