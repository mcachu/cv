<?php

define('SO_THEME_VERSION', '1.0.5');

include(get_template_directory().'/extras/admin/admin.php');
include(get_template_directory().'/functions/shortcodes.php');


if(!function_exists('vcard_setup')):
/**
 * Setup everything for the VCard
 * 
 * @action after_setup_theme
 */
function vcard_setup(){
	if ( ! isset( $content_width ) ) $content_width = 540;

	// This is required
	add_theme_support('automatic-feed-links');
	
	// We use featured images
	add_theme_support('post-thumbnails');
	
	// Custom background
	add_theme_support('custom-background', array(
		'default-color' => '#fbf4ee',
		'default-image' => get_template_directory_uri().'/images/bg.png',
	));
	
	// Custom header is used for the main image
	add_theme_support('custom-header', array(
		'width' => 130,
		'height' => 160,
		'default-image' => get_template_directory_uri().'/images/default-profile.jpg',
		'header-text' => false,
	));

	// The single side menu
	register_nav_menu('main-menu', __('Main Menu', 'vcard'));

	// Add thumbnail sizes
	set_post_thumbnail_size(130,130,true);
	add_image_size('portfolio-gallery', 220, 220, true);
	
	// We have an editor style
	add_editor_style();
}
endif;
add_action('after_setup_theme', 'vcard_setup');


if(!function_exists('vcard_widgets')):
/**
 * Register vCard's widget area.
 */
function vcard_widgets(){
	register_sidebar(array(
		'name'          => __('Site Footer', 'vcard'),
		'id'            => 'site-footer',
		'description'   => __('Displayed in the footer of your site.', 'vcard'),
	));
}
endif;
add_action('widgets_init', 'vcard_widgets');


if(!function_exists('vcard_title')) :
/**
 * Give vCard a nice title.
 *
 * @param string $title The starting title
 * @param $sep
 * @param $seplocation
 * @return string
 *
 * @filter wp_title
 */
function vcard_title($title, $sep, $seplocation){
	global $page, $paged;

	// Add the blog name.
	$title = $title.get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= ' | ' . sprintf( __( 'Page %s', 'vcard' ), max( $paged, $page ) );

	return $title;
}
endif;
add_filter('wp_title', 'vcard_title', 10, 3);


if(!function_exists('vcard_enqueue_scripts')):
/**
 * Enqueue vCard's scripts and styles
 */
function vcard_enqueue_scripts(){
	// Google web fonts for Droid Sans
	wp_enqueue_style('google-webfonts', 'http://fonts.googleapis.com/css?family=Droid+Sans');
	
	if(is_page() && basename(get_page_template()) == 'page-portfolio.php'){
		// Enqueue colorbox when we're viewing a portfolio page.
		wp_enqueue_script('jquery.colorbox', get_template_directory_uri().'/colorbox/jquery.colorbox.min.js', array('jquery'), '1.3.19.3');
		wp_enqueue_script('vcard-main', get_template_directory_uri().'/js/vcard.min.js', array('jquery'), SO_THEME_VERSION);
		wp_enqueue_style('jquery.colorbox', get_template_directory_uri().'/colorbox/colorbox.css', array(), '1.3.19.3');
	}
	
	if ( is_singular() ) wp_enqueue_script( "comment-reply" );
}
endif;
add_action('wp_enqueue_scripts', 'vcard_enqueue_scripts');


if(!function_exists('vcard_menu_item_icons')):
/**
 * Displays a menu item icon.
 * 
 * @param $objects
 * @param $args
 * @return mixed
 */
function vcard_menu_item_icons($objects, $args){
	if($args->theme_location != 'main-menu') return $objects;
	
	$front = get_option('page_on_front');
	
	foreach($objects as & $object){
		if($object->type == 'post_type' && $object->type_label == 'Page'){
			$template = get_post_meta($object->object_id, '_wp_page_template', true);
			$object->classes[] = str_replace('.', '-', $template);
			
			if($template == 'page-blog.php' && is_single()) {
				$object->classes[] = 'current-menu-item';
			}
		}
	}
	
	return $objects;
}
endif;
add_filter('wp_nav_menu_objects', 'vcard_menu_item_icons', 10, 2);


if(!function_exists('vcard_menu_item_filter')):
/**
 * Filter the menu items
 * @param $items
 * @param $args
 */
function vcard_menu_item_filter($items, $args){
	// We need em tags in all the items, for icons.
	$items = preg_replace('/<li([^>]*)>/', '<li$1><em></em>', $items);
	return $items;
	
}
endif;
add_filter('wp_nav_menu_main-menu_items', 'vcard_menu_item_filter', 10, 2);


if(!function_exists('vcard_contact_form_7_default')):
/**
 * Change the contact form defaults so we can edit them.
 * 
 * @param $template
 * @param $prop
 * @return string
 */
function vcard_contact_form_7_default($template, $prop){
	if($prop != 'form') return $template;
	
	$template = '<div class="contact-form">'."\n";
	$template .= '<div class="field"><label>'.__('Your Name', 'vcard').'</label>[text* your-name]</div>'."\n";
	$template .= '<div class="field"><label>'.__('Your Email', 'vcard').'</label>[email* your-email]</div>'."\n";
	$template .= '<div class="field"><label>'.__('Subject', 'vcard').'</label>[text your-subject]</div>'."\n";
	$template .= '<div class="field message"><label>'.__('Message', 'vcard').'</label>[textarea your-message]</div>'."\n";
	$template .= '<div class="field submit">[submit "' . __( 'Send', 'vcard' ) . '"]</div>'."\n";
	$template .= '</div>';
	return $template;
}
endif;
add_filter('wpcf7_default_template', 'vcard_contact_form_7_default', 10, 2);


if(!function_exists('vcard_comment_single')):
/**
 * Display a single comment.
 * 
 * @param $comment
 * @param $args
 */
function vcard_comment_single($comment, $args){
	$GLOBALS['comment'] = $comment;
	get_template_part('comment');
}
endif;


if(!function_exists('vcard_filter_comment_form_fields')):
/**
 * Filter the default values of the comment form
 * @param $fields
 * @return array
 * 
 * @filter comment_form_defaults
 */
function vcard_filter_comment_form_fields($fields){
	$fields['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';
	$fields['comment_notes_after'] = '';
	
	return $fields;
}
endif;
add_filter('comment_form_defaults', 'vcard_filter_comment_form_fields');


if(!function_exists('vcard_previous_link_attr')):
/**
 * Modify the previous link attributes
 * @param $attr
 * @return string
 */
function vcard_previous_link_attr($attr){
	$attr = 'class="nav-prev"';
	return $attr;
}
endif;
add_filter('previous_posts_link_attributes', 'vcard_previous_link_attr');


if(!function_exists('vcard_next_link_attr')):
/**
 * Modify the next link attributes
 * @param $attr
 * @return string
 */
function vcard_next_link_attr($attr){
	$attr = 'class="nav-next"';
	return $attr;
}
endif;
add_filter('next_posts_link_attributes', 'vcard_next_link_attr');


if(!function_exists('vcard_footer_widget_params')):
/**
 * Set the widths of the footer widgets
 *
 * @param $params
 * @return mixed
 */
function vcard_footer_widget_params($params){
	// Check that this is the footer
	if($params[0]['id'] != 'sidebar') return $params;

	$sidebars_widgets = wp_get_sidebars_widgets();
	$count = count($sidebars_widgets[$params[0]['id']]);
	$params[0]['before_widget'] = preg_replace('/\>$/', ' style="width:'.round(100/$count,4).'%" >', $params[0]['before_widget']);

	return $params;
}
endif;
add_action('dynamic_sidebar_params', 'vcard_footer_widget_params');


if(!function_exists('vcard_admin_menu')):
/**
 * Add the vCard theme documentation menu entry.
 */
function vcard_admin_menu(){
	add_theme_page(__('Theme Documentation', 'vcard'), __('Theme Docs', 'vcard'), 'edit_theme_options', 'theme-documentation', 'vcard_theme_documentation');
}
endif;
add_action('admin_menu', 'vcard_admin_menu');


if(!function_exists('vcard_theme_documentation')):
/**
 * Display the documentation page.
 */
function vcard_theme_documentation(){
	?>
	<div class="wrap">
		<h2><?php _e('Theme Documentation', 'vcard') ?></h2>
		<p>
			<?php _e("vCard's documentation is available on SiteOrigin", 'vcard') ?>
		</p>
		<p>
			<a href="http://siteorigin.com/doc/vcard/" target="_blank" class="button-primary"><?php _e('Read Documentation', 'vcard') ?></a>
		</p>
	</div>
	<?php
}
endif;


if(!function_exists('vcard_theme_documentation_adminbar')):
/**
 * Display the admin bar on the theme documentation page
 */
function vcard_theme_documentation_adminbar($bar){
	$screen = get_current_screen();
	
	if($screen->id == 'appearance_page_theme-documentation')
		$bar = (object) array('id' => 'vcard-theme-docs', 'message' => array('tpl/message', 'docs'));
	
	return $bar;
}
endif;
add_filter('so_adminbar', 'vcard_theme_documentation_adminbar');