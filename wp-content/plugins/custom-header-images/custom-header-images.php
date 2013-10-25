<?php
/*
Plugin Name: Custom Header Images
Plugin URI: http://www.blackbam.at/blog/
Description: A very simple and lightweight Plugin for managing custom header images for pages, posts, archive-pages, and all other possible.
Author: David Stöckl
Version: 1.1.1
Author URI: http://www.blackbam.at/blog/
 *
 * Note: This Plugins is GPLv2 licensed. This Plugin is released without any warranty. 
 *
*/



/* 1. Version check */
global $wp_version;

$exit_msg='The Custom Header Images Plugin requires WordPress version 3.0 or higher. <a href="http://codex.wordpress.org/Upgrading_Wordpress">Please update!</a>';

if(version_compare($wp_version,"3.0","<")) {
	exit ($exit_msg);
}

/* 2. Install / Uninstall */
register_activation_hook(__FILE__,"chi_activate");

function chi_activate() {
	$header_images = array(
		'chi_width' =>960,
		'chi_height' => 250
	);
	add_option('chi_data',$header_images);
	
	register_uninstall_hook(__FILE__,"chi_uninstall");
}

function chi_uninstall() {
	// delete all options, tables, ...
	delete_option('chi_data');
	delete_option('chi_custom_output');
}

/*************** Administration ****************/
add_action( 'admin_enqueue_scripts', 'chi_admin_scripts' );

function chi_admin_scripts() {
		wp_enqueue_script('jquery');  
}

// localization
add_action('init','chi_localization');

function chi_localization() {
	load_plugin_textdomain( 'custom-header-images', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// add the backend menu page
add_action('admin_menu','chi_options');

// add the options page
function chi_options() {
	add_options_page('Header Images','Header Images','manage_options',__FILE__,'chi_backend_page');
}

function chi_backend_page() { ?>
		<div class="wrap">
			<div><?php screen_icon('options-general'); ?></div>
			<h2><?php _e('Settings: Header Images','custom-header-images'); ?></h2>
			<?php
			if(isset($_POST['chi_backend_update']) && $_POST['chi_backend_update']!="") {
				
				$header_images = array();
				
				$header_images['chi_header_image_links'] = intval($_POST['chi_header_image_links']);
				$header_images['chi_width'] = intval($_POST['chi_width']);
				$header_images['chi_height'] = intval($_POST['chi_height']);
				
				$header_images['chi_display_nothing'] = intval($_POST['chi_display_nothing']);
				$header_images['chi_display_cat_image'] = intval($_POST['chi_display_cat_image']);
				
				// exclude post types
				$exclude_post_types = array_map('trim',explode(",",$_POST["chi_exclude_post_types"]));
				$possible = get_post_types(array('public'=>'true'),'names');
				$exclude = array();
				
				foreach($exclude_post_types as $pt) {
					if(in_array($pt,$possible)) {
						array_push($exclude,$pt);
					}
				}
				$header_images['chi_exclude_post_types'] = $exclude;
				
				// exclude taxonomies
				$exclude_taxonomies = array_map('trim',explode(",",$_POST["chi_exclude_taxonomies"]));
				$possible = get_taxonomies(array('public'=>'true'),'names');
				$exclude = array();
				
				foreach($exclude_taxonomies as $tax) {
					if(in_array($tax,$possible)) {
						array_push($exclude,$tax);
					}
				}
				$header_images['chi_exclude_taxonomies'] = $exclude;
				
				// Saving the Standard Header Image URLs
				$header_images['chi_url_global_default'] = trim($_POST['chi_url_global_default']);
				
				$header_images['chi_url_front'] = trim($_POST['chi_url_front']);
				$header_images['chi_url_home'] = trim($_POST['chi_url_home']);
				$header_images['chi_url_404'] = trim($_POST['chi_url_404']);
				$header_images['chi_url_search'] = trim($_POST['chi_url_search']);
				
				$header_images['chi_url_single_default'] = trim($_POST['chi_url_single_default']);
				$header_images['chi_url_page_default'] = trim($_POST['chi_url_page_default']);
				
				$header_images['chi_url_archive_default'] = trim($_POST['chi_url_archive_default']);
				$header_images['chi_url_date'] = trim($_POST['chi_url_date']);
				$header_images['chi_url_author_default'] = trim($_POST['chi_url_author_default']);
				$header_images['chi_url_category_default'] = trim($_POST['chi_url_category_default']);
				$header_images['chi_url_tag_default'] = trim($_POST['chi_url_tag_default']);
				$header_images['chi_url_tax_default'] = trim($_POST['chi_url_tax_default']);
				
				// Saving the Standard Header Image URL Links
				if($header_images['chi_header_image_links']==1) {
					$header_images['chi_url_global_default_link'] = trim($_POST['chi_url_global_default_link']);
					
					$header_images['chi_url_front_link'] = trim($_POST['chi_url_front_link']);
					$header_images['chi_url_home_link'] = trim($_POST['chi_url_home_link']);
					$header_images['chi_url_404_link'] = trim($_POST['chi_url_404_link']);
					$header_images['chi_url_search_link'] = trim($_POST['chi_url_search_link']);
					
					$header_images['chi_url_single_default_link'] = trim($_POST['chi_url_single_default_link']);
					$header_images['chi_url_page_default_link'] = trim($_POST['chi_url_page_default_link']);
					
					$header_images['chi_url_archive_default_link'] = trim($_POST['chi_url_archive_default_link']);
					$header_images['chi_url_date_link'] = trim($_POST['chi_url_date_link']);
					$header_images['chi_url_author_default_link'] = trim($_POST['chi_url_author_default_link']);
					$header_images['chi_url_category_default_link'] = trim($_POST['chi_url_category_default_link']);
					$header_images['chi_url_tag_default_link'] = trim($_POST['chi_url_tag_default_link']);
					$header_images['chi_url_tax_default_link'] = trim($_POST['chi_url_tax_default_link']);
				}
				
				update_option('chi_data',$header_images);
				
				// Save custom output
				update_option('chi_custom_output',stripslashes_deep($_POST['chi_custom_output']));
				
				?>
					<div id="setting-error-settings_updated" class="updated settings-error"> 
						<p><strong><?php _e('Settings saved successfully.','custom-header-images'); ?></strong></p>
					</div>
			<?php
			} 
			
			// get the data
			$data = get_option('chi_data');
			?>
			<form name="improved_user_search_in_backend_update" method="post" action="">
				<p><?php printf(__('Please consider %s donating %s. Thank you.','custom-header-images'),'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DX9GDC5T9J9AQ">','</a>'); ?></p>
				<p><strong><?php _e('Note:','custom-header-images'); ?></strong> <?php _e('If you host your images on your site (recommended), than you upload these using the media library:','custom-header-images'); ?></p>
				<ol>
					<li><?php printf(__('Go to the %s media library %s and upload your images (or use an external absolute image URL)','custom-header-images'),
					'<a href="'.get_bloginfo('wpurl').'/wp-admin/upload.php">','</a>'); ?></li>
					<li><?php _e('Copy the file-URL(s) of your image(s) and copy it to the desired position in this page','custom-header-images'); ?></li>
					<li><?php _e('Save the settings','custom-header-images'); ?></li>
				</ol>
				<p><strong><?php _e('Note:','custom-header-images'); ?></strong> 
					<?php _e('Just leave the fields blank to display the global default image or no image.','custom-header-images'); ?></p>
				<p><?php _e('Post, Page, Category and Taxonomy Images are set in the posts menu.','custom-header-images'); ?></p>
				<h2><?php _e('General Settings','custom-header-images'); ?></h2>
				<div>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Use Header Image Links?','custom-header-images'); ?></th>
							<td><input type="checkbox" name="chi_header_image_links" value="1" <?php if($data['chi_header_image_links']==1) {?>checked="checked"<?php }; ?> /></td>
							<td class="description"><?php _e('If this is checked, header image links are enabled.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Default Header Image Width','custom-header-images'); ?></th>
							<td><input type="text" size="6" name="chi_width" value="<?php echo $data['chi_width']; ?>" /></td>
							<td class="description"><?php _e('The image displayed on the article overview page.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Default Header Image Height','custom-header-images'); ?></th>
							<td><input type="text" size="6" name="chi_height" value="<?php echo $data['chi_height']; ?>" /></td>
							<td class="description"><?php _e('The image displayed on a static frontpage.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Display nothing by default?','custom-header-images'); ?></th>
							<td><input type="checkbox" name="chi_display_nothing" value="1" <?php if($data['chi_display_nothing']==1) {?>checked="checked"<?php }; ?> /></td>
							<td class="description"><?php _e('If this option is on, the Plugin displayes nothing, if no concrete image is specified.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Display category image in case of missing post / page / post-type image?','custom-header-images'); ?></th>
							<td><input type="checkbox" name="chi_display_cat_image" value="1" <?php if($data['chi_display_cat_image']==1) {?>checked="checked"<?php }; ?> /></td>
							<td class="description"><?php _e('If this option is on, the Plugin displays the category image (if existing), in case of missing specific image for post/page/post-type.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Exclude the following post types (by slug, comma seperated):','custom-header-images'); ?></th>
							<td><input type="text" size="80" name="chi_exclude_post_types" value="<?php echo implode(",",is_array($data['chi_exclude_post_types']) ? $data['chi_exclude_post_types'] : array()); ?>" /></td>
							<td class="description"><?php _e('Possible values:','custom-header-images'); ?> <?php 
							$post_types = get_post_types(array('public'=>'true'),'names');
							echo implode(", ",$post_types);
							?>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Exclude the following taxonomies (by slug, comma seperated):','custom-header-images'); ?></th>
							<td><input type="text" size="80" name="chi_exclude_taxonomies" value="<?php echo implode(",",is_array($data['chi_exclude_taxonomies']) ? $data['chi_exclude_taxonomies'] : array()); ?>" /></td>
							<td class="description"><?php _e('Possible values:','custom-header-images'); ?> <?php 
							$taxes = get_taxonomies(array('public'=>'true'),'names');
							echo implode(", ",$taxes);
							?>
							</td>
						</tr>
					</table>
					
				</div>
				<p>&nbsp;</p>
				<h2><?php _e('Image Settings','custom-header-images'); ?></h2>
				<div>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php _e('Global Image Default URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_global_default" value="<?php echo $data['chi_url_global_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="100" name="chi_url_global_default_link" value="<?php echo $data['chi_url_global_default_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on all pages, which have no set default.','custom-header-images'); ?></td>
						</tr>
						<tr>
							<th colspan="3"><strong><?php _e('Special Pages','custom-header-images'); ?></strong></th>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Home Image URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_home" value="<?php echo $data['chi_url_home']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_home_link" value="<?php echo $data['chi_url_home_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on the article overview page.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Frontpage Image URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_front" value="<?php echo $data['chi_url_front']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_front_link" value="<?php echo $data['chi_url_front_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on a static frontpage.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('404 Image URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_404" value="<?php echo $data['chi_url_404']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_404_link" value="<?php echo $data['chi_url_404_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on error pages.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Search Image URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_search" value="<?php echo $data['chi_url_search']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_search_link" value="<?php echo $data['chi_url_search_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on search pages.','custom-header-images'); ?></td>
						</tr>
						<tr>
							<th colspan="3"><strong><?php _e('Posts &amp; Pages','custom-header-images'); ?></strong></th>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Single Post Default Image URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_single_default" value="<?php echo $data['chi_url_single_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_single_default_link" value="<?php echo $data['chi_url_single_default_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on single posts by default.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Page Image Default URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_page_default" value="<?php echo $data['chi_url_page_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_page_default_link" value="<?php echo $data['chi_url_page_default_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on pages by default.','custom-header-images'); ?></td>
						</tr>
						<tr>
							<th colspan="3"><strong><?php _e('Archive Pages','custom-header-images'); ?></strong></th>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Archive Default Image URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_archive_default" value="<?php echo $data['chi_url_archive_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_archive_default_link" value="<?php echo $data['chi_url_archive_default_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on archive pages by default.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Date Image URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_date" value="<?php echo $data['chi_url_date']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_date_link" value="<?php echo $data['chi_url_date_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on date archive pages.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Author Image Default URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_author_default" value="<?php echo $data['chi_url_author_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_author_default_link" value="<?php echo $data['chi_url_author_default_link']; ?>" />
								<?php } ?>							</td>
							<td class="description"><?php _e('The image displayed on author pages by default.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Category Image Default URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_category_default" value="<?php echo $data['chi_url_category_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_category_default_link" value="<?php echo $data['chi_url_category_default_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on category pages by default.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Tag Image Default URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_tag_default" value="<?php echo $data['chi_url_tag_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_tag_default_link" value="<?php echo $data['chi_url_tag_default_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on tag pages by default.','custom-header-images'); ?></td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('Taxonomy Image Default URL','custom-header-images'); ?></th>
							<td>
								<input type="text" size="100" name="chi_url_tax_default" value="<?php echo $data['chi_url_tax_default']; ?>" />
								<?php if($data['chi_header_image_links']==1) { ?>
									<br/><?php _e('Linked URL:','custom-header-images'); ?> <input type="text" size="80" name="chi_url_tax_default_link" value="<?php echo $data['chi_url_tax_default_link']; ?>" />
								<?php } ?>
							</td>
							<td class="description"><?php _e('The image displayed on taxonomy pages by default.','custom-header-images'); ?></td>
						</tr>
					</table>
					<p></p>
					
					<h2><?php _e('Custom Output','custom-header-images'); ?></h2>
					<p><?php _e('For reasons of responsiveness and more flexibility, you may want to adjust the output of the Plugin to your needs.Use any HTML, CSS and even JavaScript to customize your output. Leave empty for standard output.','custom-header-images'); ?></p>
					<script type="text/javascript">
						function chi_restore_output() {
							jQuery('#chi_custom_output').html('<div onclick="if(this.getAttribute(\'data-link\')!=\'\') window.location.href=this.getAttribute(\'data-link\')" data-link="[link]" class="chi_display_header" style="height:[height]px; width:[width]px; background-image:url(\'[image_url]\');"></div>');
						}
					</script>
					
					<div>
					<textarea style="font-size:15px;width:80%; height:100px; padding:3px 5px; background-color:#eee; font-family:'Courier New',sans-serif;" id="chi_custom_output" name="chi_custom_output"><?php echo get_option('chi_custom_output'); ?></textarea>
					</div>
					<div class="desc">
						<span style="color:#991;"><?php _e('Important','custom-header-images'); ?></span>: <?php _e('Use the following shortcodes, or you will not see your header images correctly:','custom-header-images'); ?><br/><br/>
						
						<span style="color:#393;">[image_url]</span> ... <?php _e('The header image URL','custom-header-images'); ?><br/>
						<span style="color:#393;">[link]</span> ... <?php _e('The header image Link','custom-header-images'); ?><br/>
						<span style="color:#393;">[width]</span> ... <?php _e('The header image defined width','custom-header-images'); ?><br/>
						<span style="color:#393;">[height]</span> ... <?php _e('The header image defined height','custom-header-images'); ?><br/><br/>
						
						<span style="color:#911;"><?php _e('Caution','custom-header-images'); ?></span>: <?php _e('Be careful, you should know what you are doing.','custom-header-images'); ?>
						<?php printf(__('In case of troubles, you can use %s restore default %s.','custom-header-images'),'<a style="cursor:pointer;" onclick="chi_restore_output()">','</a>'); ?>
					</div>
					<p>&nbsp;</p>
					<hr/>
					<p>&nbsp;</p>
					<p><input type="hidden" name="chi_backend_update" value="doit" />
					<input type="submit" name="Save" value="<?php _e('Save Settings','custom-header-images'); ?>" class="button-primary" /></p>
					<p>&nbsp;</p>
				</div>
			</form>
		</div>
		
<?php } 

/************** Post/Page/Post-Type Options *******/
add_action('admin_init', 'chi_init');
add_action('save_post', 'save_chi_post');

// füge Post/Page/Post-Type Meta-Boxen hinzu
function chi_init(){
	
	$post_types = get_post_types(array('public'=>'true'));
	$chi_data = get_option("chi_data");
	$excluded = $chi_data["chi_exclude_post_types"];

	foreach($post_types as $pt) {
		if(!is_array($excluded) || !in_array($pt,$excluded)) {
			add_meta_box("chi_post_settings", __("Custom Header Image",'custom-header-images'), "chi_post_settings", $pt, "normal", "default");
		}
	}
}

function chi_post_settings(){
    global $post;
    $custom = get_post_custom($post->ID);
	$chi_data = get_option("chi_data");
?>
	<div class="inside">
		<table class="form-table">
			<tr>
				<th><label for="chi_post_setting_1"><?php _e('URL of the Custom Header Image','custom-header-images'); ?></label></th>
				<td>
					<input type="text" size="50" name="chi_post_setting_1" value="<?php echo $custom["chi_post_setting_1"][0]; ?>" />
				</td>
			</tr>
			<?php if($chi_data['chi_header_image_links']==1) { ?>
				<tr>
					<th><label for="chi_post_setting_3"><?php _e('Link of the Custom Header Image','custom-header-images'); ?></label></th>
					<td>
						<input type="text" size="50" name="chi_post_setting_3" value="<?php echo $custom["chi_post_setting_3"][0]; ?>" />
					</td>
				</tr>
			<?php } ?>
			<tr>
				<th><label for="chi_post_setting_2"><?php _e('Display nothing?','custom-header-images'); ?></label></th>
				<td>
					<input type="checkbox" size="50" name="chi_post_setting_2" value="1" <?php if($custom["chi_post_setting_2"][0]==1) {?>checked="checked"<?php } ?> />
				</td>
			</tr>
		</table>
	</div>
<?php
}

function save_chi_post(){
	global $post;
	
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}
	
	//if($post->post_type == "post" || $post->post_type == "page") {
		update_post_meta($post->ID, "chi_post_setting_1", trim($_POST["chi_post_setting_1"]));
		update_post_meta($post->ID, "chi_post_setting_2", intval($_POST["chi_post_setting_2"]));
		update_post_meta($post->ID, "chi_post_setting_3", trim($_POST["chi_post_setting_3"]));
	//}
}

/************** End Post/Page/Post-Type Options *******/

/******** Category options ***********/
///////////// Category custom Thumbnail
//add extra fields to category edit form hook
add_action('init','chi_taxonomy_fields',101);

function chi_taxonomy_fields() {
	$taxes = get_taxonomies(array('public'=>'true'));
	$chi_data = get_option("chi_data");
	$excluded = $chi_data["chi_exclude_taxonomies"];
	
	foreach($taxes as $tax) {
		if(!is_array($excluded) || !in_array($tax,$excluded)) {
			add_action ( $tax.'_add_form_fields', 'extra_taxonomy_fields_add');
			add_action ( $tax.'_edit_form_fields', 'extra_taxonomy_fields_edit');
			add_action ( 'edited_'.$tax, 'save_extra_taxonomy_fields',10,2);
			add_action ( 'create_'.$tax, 'save_extra_taxonomy_fields',10,2);
		}
	}
}


//add extra fields to taxonomy edit form callback function
function extra_taxonomy_fields_edit( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
	$tax = $tag->taxonomy;
    $cat_meta = get_option( "chi_term_setting_1_".$tax."_$t_id");
	$chi_data = get_option("chi_data");
?>
<tr class="form-field">
	<th scope="row" valign="top"><label><?php _e('Taxonomy Image Url (The Image)','custom-header-images'); ?></label></th>
	<td>
		<input type="text" name="chi_term_setting_1_[img]" id="chi_term_setting_1_[img]" size="40" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>">
		<p><span class="description"><?php _e('The Taxonomy Thumbnail URL - please use relative path like /wp-content/..path_to_image/image.jpg','custom-header-images'); ?></span></p>
	</td>
</tr>
<?php if($chi_data['chi_header_image_links']==1) { ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php _e('Taxonomy Image Link (Link of the Image)','custom-header-images'); ?></label></th>
		<td>
			<input type="text" name="chi_term_setting_1_[link]" id="chi_term_setting_1_[link]" size="40" value="<?php echo $cat_meta['link'] ? $cat_meta['link'] : ''; ?>">
			<p><span class="description"><?php _e('Use this, if you want to link the Taxonomy Image to some url e.g. http://myblog.org/taxonomy-overview/','custom-header-images'); ?></span></p>
		</td>
	</tr>
<?php } ?>
<tr class="form-field">
	<th scope="row"><label><?php _e('Display no header image?','custom-header-images'); ?></th>
	<td>
		<input style="width:20px;" type="checkbox" size="50" name="chi_term_setting_1_[dpn]" value="1" <?php if($cat_meta['dpn']==1) {?>checked="checked"<?php } ?> /></p>
		<p><span class="description"><?php _e('If this is checked, no header image will be displayed.','custom-header-images'); ?></span></p>
	</td>
</tr>
<?php
}

// add extra fields to the taxonomy add function
function extra_taxonomy_fields_add($tag) {
    $t_id = $tag->term_id;
	$tax = $tag->taxonomy;
    $cat_meta = get_option( "chi_term_setting_1_".$tax."_$t_id");
	$chi_data = get_option("chi_data");
	?>
	<div class="form-field">
		<label><?php _e('Taxonomy Image Url','custom-header-images'); ?></label>
		<input type="text" name="chi_term_setting_1_[img]" size="40" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
		<p><span class="description"><?php _e('The Taxonomy Thumbnail URL - please use relative path like /wp-content/..path_to_image/image.jpg','custom-header-images'); ?></span></p>
	</div>
<?php if($chi_data['chi_header_image_links']==1) { ?>
	<div class="form-field">
		<label><?php _e('Taxonomy Image Link (Link of the Image)','custom-header-images'); ?></label>
			<input type="text" name="chi_term_setting_1_[link]" id="chi_term_setting_1_[link]" size="40" value="<?php echo $cat_meta['link'] ? $cat_meta['link'] : ''; ?>"><br/>
			<p><span class="description"><?php _e('Use this, if you want to link the Taxonomy Image to some url e.g. http://myblog.org/taxonomy-overview/','custom-header-images'); ?></span></p>
	</div>
<?php } ?>
	<div class="form-field">
		<p><label><?php _e('Display no header image?','custom-header-images'); ?></label>
		<input style="width:20px;" type="checkbox" size="50" name="chi_term_setting_1_[dpn]" value="1" <?php if($cat_meta['dpn']==1) {?>checked="checked"<?php } ?> /></p>
		<p><span class="description"><?php _e('If this is checked, no header image will be displayed.','custom-header-images'); ?></span></p>
	</div>
	
		<?php
}
   // save taxonomy extra fields callback function
function save_extra_taxonomy_fields( $term_id, $tt_id ) {
	
	// get the taxonomy of this term
	global $wpdb;
	$tax = $wpdb->get_var("SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_taxonomy_id=$tt_id");
	
    if ( isset( $_POST['chi_term_setting_1_'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "chi_term_setting_1_".$tax."_$t_id");
        $cat_keys = array_keys($_POST['chi_term_setting_1_']);
		
        foreach ($cat_keys as $key){
        	if (isset($_POST['chi_term_setting_1_'][$key])){
                $cat_meta[$key] = $_POST['chi_term_setting_1_'][$key];
            }
        }
		
		if($_POST['chi_term_setting_1_']['dpn']!=1) {
			$cat_meta['dpn'] = 0;
		}
		
        //save the option array
        update_option( "chi_term_setting_1_".$tax."_$t_id", $cat_meta );
    }
}



/************** Display functions **********/
add_action('wp_head','chi_css');

function chi_css() {?>
	<style type="text/css">
		.chi_display_header {
			background-repeat:no-repeat;
			background-position:center center;
		}
	</style>
<?php }

function chi_display_header($width=-1,$height=-1) {
	$chi_data = get_option('chi_data');
	
	$header_image_url = "";
	$header_image_link = "";
	$display_nothing = false;
	$final = false;
	
	if($width==-1) {
		$width = $chi_data['chi_width'];
	}
	
	if($height==-1) {
		$height = $chi_data['chi_height'];
	}
	
	if(is_front_page()) {
		$header_image_url = $chi_data['chi_url_front'];
		$header_image_link = $chi_data['chi_url_front_link'];
	} else if(is_home()) {
		$header_image_url = $chi_data['chi_url_home'];
		$header_image_link = $chi_data['chi_url_home_link'];
	} else if(is_404()) {
		$header_image_url = $chi_data['chi_url_404'];
		$header_image_link = $chi_data['chi_url_404_link'];
	} else if(is_search()) {
		$header_image_url = $chi_data['chi_url_search'];
		$header_image_link = $chi_data['chi_url_search_link'];
	} else if(is_archive()) {
		
		if(is_category()) {
			$cat_image_settings = get_option('chi_term_setting_1_category_'.get_query_var('cat'));
			if($cat_image_settings["dpn"]==1) {
				$display_nothing = true;
			} else {
				$header_image_url = $cat_image_settings["img"];
				$header_image_link = $cat_image_settings["link"];
			}
			if($header_image_url=="") {
				$header_image_url = $chi_data["chi_url_category_default"];
				$header_image_link = $chi_data['chi_url_category_default_link'];
			}
			
		} else if(is_tag()) {
			$cat_image_settings = get_option('chi_term_setting_1_post_tag_'.get_query_var('tag_id'));
			if($cat_image_settings["dpn"]==1) {
				$display_nothing = true;
			} else {
				$header_image_url = $cat_image_settings["img"];
				$header_image_link = $cat_image_settings["link"];
			}
			if($header_image_url=="") {
				$header_image_url = $chi_data["chi_url_tag_default"];
				$header_image_link = $chi_data['chi_url_tag_default_link'];
			}
		} else if(is_date()) {
			$header_image_url= $chi_data["chi_url_date"];
			$header_image_link = $chi_data['chi_url_date_link'];
		} else if(is_author()) {
			$header_image_url = $chi_data["chi_url_author_default"];
			$header_image_link = $chi_data['chi_url_author_default_link'];
		} else if(is_tag()) {
			$header_image_url = $chi_data["chi_url_tag_default"];
			$header_image_link = $chi_data['chi_url_tag_default_link'];
		} else if(is_tax()) {
			$taxonomy = get_query_var('taxonomy');
			$term = get_query_var($taxonomy);
			$term_info = get_term_by('slug',$term,$taxonomy);
			
			$cat_image_settings = get_option('chi_term_setting_1_'.get_query_var('taxonomy').'_'.$term_info->term_id);
			
			if($cat_image_settings["dpn"]==1) {
				$display_nothing = true;
			} else {
				$header_image_url = $cat_image_settings["img"];
				$header_image_link = $cat_image_settings["link"];
			}
			if($header_image_url=="") {
				$header_image_url = $chi_data["chi_url_tax_default"];
				$header_image_link = $chi_data['chi_url_tax_default_link'];
			}
		}
		if($header_image_url=="") {
			$header_image_url = $chi_data['chi_url_archive_default'];
			$header_image_link = $chi_data['chi_url_archive_default_link'];
		}
	} else if(is_single() || is_page()) {
		global $post;
		$single_image_url=get_post_meta($post->ID,"chi_post_setting_1",true);
		$single_image_link = get_post_meta($post->ID,"chi_post_setting_3",true);
		
		if(get_post_meta($post->ID,"chi_post_setting_2",true)==1) {
			$display_nothing = true;
		}
		
		if($single_image_url!="") {
			
			$header_image_url = $single_image_url;
			$header_image_link =  $single_image_link;
		} else {
			$category_image_url="";
			if($chi_data['chi_display_cat_image']==1) {
				$categories = get_the_category($post->ID);
				if(!empty($categories)) {
					$first_cat_id = $categories[0]->term_id;
					$category_image_url_op = get_option('chi_term_setting_1_category_'.$first_cat_id);
					$category_image_url = $category_image_url_op['img'];
					$category_image_link_op = get_option('chi_term_setting_1_category_'.$first_cat_id);
					$category_image_link = $category_image_link_op['link'];
				}
			}
			
			if($category_image_url!="") {
				$header_image_url = $category_image_url;
				$header_image_link = $category_image_link;
			} else {
				if(is_single()) {
					$header_image_url = $chi_data['chi_url_single_default'];
					$header_image_link = $chi_data['chi_url_single_default_link'];
				} else if(is_page()) {
					$header_image_url = $chi_data['chi_url_page_default'];
					$header_image_link = $chi_data['chi_url_page_default_link'];
				}
			}
		}
	}

	if($header_image_url=="" && $chi_data['chi_display_nothing']!=1) {
		$header_image_url=$chi_data['chi_url_global_default'];
		$header_image_link=$chi_data['chi_url_global_default_link'];
	}
	
	if($display_nothing===true) {
		$header_image_url="";
	}
	
	if($header_image_url!="") {
		$custom_output = get_option('chi_custom_output');
		
		if(trim($custom_output)=="") {
			?>
			<div class="chi_display_header" data-link="<?php echo $header_image_link; ?>" style="<?php if($linkme!="") { echo "cursor:pointer;"; } ?>height:<?php echo $height;?>px; width:<?php echo $width;?>px; background-image:url('<?php echo $header_image_url; ?>');"></div>
			<?php
		} else {
			echo str_replace(
				array('[image_url]','[link]','[width]','[height]'),
				array($header_image_url,$header_image_link,$width,$height),
				$custom_output
			);
		}
	}
}
?>