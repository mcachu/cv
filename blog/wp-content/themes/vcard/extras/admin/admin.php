<?php

/**
 * Active the First Run extra. This will just display a bar in the admin after a user first installs this theme
 * 
 * @action after_switch_theme
 */
function so_adminbar_first_run_activate(){
	define('SO_FIRST_RUN_ACTIVE', true);
}
add_action('after_switch_theme', 'so_adminbar_first_run_activate');

/**
 * Initialize the default admin bars.
 */
function so_adminbar_init(){
	if(!is_admin()) return;
	
	$bar = null;
	$bar = apply_filters('so_adminbar', $bar);
	
	if(!empty($bar)){
		$dismissed = get_user_meta(get_current_user_id(), 'so_admin_bars_dismissed', true);
		if(!empty($dismissed) && !empty($dismissed[$bar->id])) $bar = null;
	}
	
	if(!empty($bar)){
		if(empty($bar->icon)) $bar->icon = 'http://www.gravatar.com/avatar/'.md5('greg@siteorigin.com').'?s=44';
	}
	
	$GLOBALS['so_adminbar_active'] = $bar;
}
add_action('current_screen', 'so_adminbar_init');

/**
 * Set up the default admin bars.
 * 
 * @param $bar
 * @return object
 */
function so_adminbar_defaults($bar){
	$screen = get_current_screen();
	
	if($screen->id == 'themes' && defined('SO_FIRST_RUN_ACTIVE'))
		$bar = (object) array('id' => 'firstrun', 'message' => array('extras/admin/messages/message', 'firstrun'));
	
	return $bar;
}
add_filter('so_adminbar', 'so_adminbar_defaults');

/**
 * Enqueue admin bar scripts if there's an admin bar active.
 * 
 * @param $suffix
 * @return mixed
 */
function so_adminbar_enqueue($suffix){
	// Only enqueue these if there's an active admin bar
	if(empty($GLOBALS['so_adminbar_active'])) return;
	
	wp_enqueue_script('siteorigin-admin-bar', get_template_directory_uri().'/extras/admin/assets/bar.js', array('jquery'));
	wp_enqueue_style('siteorigin-admin-bar', get_template_directory_uri().'/extras/admin/assets/bar.css');
}
add_action('admin_enqueue_scripts', 'so_adminbar_enqueue');

/**
 * Display the admin bar
 * 
 * @action in_admin_header
 */
function so_adminbar_render(){
	if(empty($GLOBALS['so_adminbar_active'])) return;
	
	?>
	<div id="siteorigin-admin-bar" data-type="<?php print esc_attr($GLOBALS['so_adminbar_active']->id) ?>">
		<div class="inner">
			<img src="<?php print esc_attr($GLOBALS['so_adminbar_active']->icon) ?>" class="icon" width="44" height="44" />
			<a href="#dismiss" class="dismiss"><?php _e('dismiss', 'siteorigin') ?></a>
			<strong><?php call_user_func_array('get_template_part', $GLOBALS['so_adminbar_active']->message) ?></strong>
		</div>
	</div>
	<?php
}
add_action('in_admin_header', 'so_adminbar_render');

/**
 * An ajax callback to dismiss the admin bar.
 */
function so_adminbar_dismiss_bar(){
	$dismiss = $previous = get_user_meta(get_current_user_id(), 'so_admin_bars_dismissed', true);
	if(empty($dismiss)) $dismiss = array();
	
	$bar = stripslashes($_POST['bar']);
	$dismiss[$bar] = true;
	
	update_user_meta(get_current_user_id(), 'so_admin_bars_dismissed', $dismiss, $previous);
	
	exit();
}
add_action('wp_ajax_so_admin_dismiss_bar', 'so_adminbar_dismiss_bar');