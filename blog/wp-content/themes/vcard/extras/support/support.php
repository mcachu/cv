<?php

/**
 * @action admin_menu
 */
function so_support_admin_menu(){
	add_theme_page(__('Premium Theme Support', 'siteorigin'), __('Theme Support', 'siteorigin'), 'switch_theme', 'theme_support', 'so_support_render');
}
add_action('admin_menu', 'so_support_admin_menu');

/**
 * 
 */
function so_support_render(){
	if(!defined('SO_IS_PREMIUM')) locate_template('extras/support/upgrade.php', true);
	else locate_template('extras/support/page.php', true);
}