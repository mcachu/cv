<?php

/**
 * Intialize the theme settings page
 * 
 * @param $theme_name
 */
function so_settings_init($theme_name = null){
	if(empty($theme_name)) {
		$theme_name = basename(get_template_directory());
	}
	
	$GLOBALS['so_settings_theme_name'] = $theme_name;
	$GLOBALS['so_settings_name'] = $theme_name.'_theme_settings';
	$GLOBALS['so_settings_defaults'] = apply_filters('so_theme_default_settings', array());
	$GLOBALS['so_settings'] = wp_parse_args(get_option($theme_name.'_theme_settings', array()), $GLOBALS['so_settings_defaults']);
	
	// Register all the actions for the settings page
	add_action('admin_menu', 'so_settings_admin_menu');
	add_action('admin_init', 'so_settings_admin_init', 8);
	add_action('so_adminbar', 'so_settings_adminbar');
	
	add_action('admin_enqueue_scripts', 'so_settings_enqueue_scripts');
	
	// Set up the help tabs
	add_action('load-appearance_page_theme_settings_page', 'so_settings_help_tab');
}

/**
 * Add the settings help tab
 */
function so_settings_help_tab(){
	$screen = get_current_screen();
	$theme = basename(get_template_directory());
	
	ob_start();
	?>
	<p>
		<?php
		printf(
			__("Please read %s's <a href='%s'>Documentation</a>.", 'siteorigin'),
			ucfirst($theme),
			'http://siteorigin.com/doc/'.$theme.'/'
		);
		?>
	</p>
	<?php
	$content = ob_get_clean();
	$screen->add_help_tab(array(
		'id' => 'theme_settings_documentation',
		'title' => __('Theme Documentation', 'siteorigin'),
		'content' => $content
	));
}

/**
 * Initialize admin settings in the admin
 * 
 * @action admin_init
 */
function so_settings_admin_init(){
	register_setting('theme_settings', $GLOBALS['so_settings_name'], 'so_settings_validate');
}

/**
 * Set up the theme settings page.
 * 
 * @action admin_menu
 */
function so_settings_admin_menu(){
	add_theme_page(__('Theme Settings','siteorigin'), __('Theme Settings', 'siteorigin'), 'edit_theme_options', 'theme_settings_page', 'so_settings_render');
}

/**
 * Render the theme settings page
 */
function so_settings_render(){
	locate_template('extras/settings/page.php', true, false);
}

function so_settings_enqueue_scripts($prefix){
	if($prefix != 'appearance_page_theme_settings_page') return;
	wp_enqueue_script( 'siteorigin-settings', get_template_directory_uri().'/extras/settings/settings.js', array('jquery'), SO_THEME_VERSION );
	wp_enqueue_style( 'siteorigin-settings', get_template_directory_uri().'/extras/settings/settings.css', array(), SO_THEME_VERSION );
	
	wp_enqueue_style( 'farbtastic' );
	wp_enqueue_script( 'farbtastic' );
}

function so_settings_adminbar($bar){
	$screen = get_current_screen();
	if($screen->id == 'appearance_page_theme_settings_page'){
		$bar = (object) array('id' => $GLOBALS['so_settings_name'], 'message' => array('extras/settings/message'));
	}
	
	return $bar;
}

/**
 * Add a settings section.
 * 
 * @param $id
 * @param $name
 */
function so_settings_add_section($id, $name){
	add_settings_section($id, $name, '__return_false', 'theme_settings');
}

/**
 * Add a setting
 *
 * @param string $section
 * @param string $id
 * @param string $type
 * @param string $name
 * @param array $args
 */
function so_settings_add_field($section, $id, $type, $name, $args = array()){
	if(isset($wp_settings_fields['theme_settings'][$section][$id])){
		if(isset($wp_settings_fields['theme_settings'][$section][$id]['args']['type']) && $wp_settings_fields['theme_settings'][$section][$id]['args']['type'] == 'teaser')
			unset($wp_settings_fields['theme_settings'][$section][$id]);
		else return;
	}
	
	$args = wp_parse_args($args, array(
		'section' => $section,
		'field' => $id,
		'type' => $type,
	));
	
	add_settings_field($id, $name, 'so_settings_field', 'theme_settings', $section, $args);
}

/**
 * Adds a field that might only be available in another version of the theme.
 * 
 * @param $section
 * @param $id
 * @param $type
 * @param $name
 * @param array $args
 */
function so_settings_add_teaser($section, $id, $name, $args = array()){
	global $wp_settings_fields;
	if(isset($wp_settings_fields['theme_settings'][$section][$id])) return;
	
	$args = wp_parse_args($args, array(
		'section' => $section,
		'field' => $id,
		'type' => 'teaser',
	));

	add_settings_field($id, $name, 'so_settings_field', 'theme_settings', $section, $args);
}

/**
 * Get the value of a setting, or the default value.
 * 
 * @param string $name The setting name
 * @return mixed
 */
function so_setting($name){
	if(!isset($GLOBALS['so_settings'][$name])) return null;
	else return $GLOBALS['so_settings'][$name];
}

/**
 * Render a settings field
 * @param $args
 */
function so_settings_field($args){
	$field_name = $GLOBALS['so_settings_name'].'['.$args['section'].'_'.$args['field'].']';
	$field_id = $args['section'].'_'.$args['field'];
	$current = isset($GLOBALS['so_settings'][$field_id]) ? $GLOBALS['so_settings'][$field_id] : null;
	
	switch($args['type']){
		case 'checkbox' :
			?>
				<input id="<?php print esc_attr($field_id) ?>" name="<?php print esc_attr($field_name) ?>" type="checkbox" <?php checked($current) ?> />
				<label for="<?php print esc_attr($field_id) ?>"><?php print esc_attr(!empty($args['label']) ? $args['label'] : __('Enabled', 'siteorigin')) ?></label>
			<?php
			break;
		case 'text' :
		case 'number' :
			?>
			<input
				id="<?php print esc_attr($field_id) ?>"
				name="<?php print esc_attr($field_name) ?>"
				class="<?php print esc_attr($args['type'] == 'number' ? 'small-text' : 'regular-text') ?>"
				size="25"
				type="<?php print esc_attr($args['type']) ?>"
				value="<?php print esc_attr($current) ?>" /><?php
			break;
		
		case 'select' :
			?>
			<select id="<?php print esc_attr($field_id) ?>" name="<?php print esc_attr($field_name) ?>">
				<?php foreach($args['options'] as $option_id => $label) : ?>
					<option value="<?php print esc_attr($option_id) ?>" <?php selected($option_id, $current) ?>><?php print esc_attr($label) ?></option>
				<?php endforeach ?>
			</select>
			<?php
			break;
		
		case 'textarea' :
			?><textarea id="<?php print esc_attr($field_id) ?>" name="<?php print esc_attr($field_name) ?>" class="large-text" rows="3"><?php print esc_textarea($current) ?></textarea><?php
			break;
		
		case 'color' :
			?>
			<div class="colorpicker-wrapper">
				<div class="color-indicator" style="background-color: <?php print esc_attr($current) ?>"></div>
				<input type="text" id="<?php print esc_attr($field_id) ?>" value="<?php print esc_attr($current) ?>" name="<?php print esc_attr($field_name) ?>" />
				<div class="farbtastic-container"></div>
			</div>
			<?php
			break;
			
		case 'teaser' :
			?>
			<div class="premium-teaser">
				<?php printf(__('<a href="%s">Premium version</a> only', 'siteorigin'), admin_url('themes.php?page=premium_upgrade')) ?>
			</div>
			<?php
			break;
		
		default : 
			_e('Unknown Field Type', 'siteorigin');
			break;
	}

	if(!empty($args['description'])) print '<p class="description">'.$args['description'].'</p>';
}

/**
 * Validate the settings values
 * 
 * @param $values
 * @return array
 */
function so_settings_validate($values){
	global $wp_settings_fields;
	
	foreach($wp_settings_fields['theme_settings'] as $section_id => $fields){
		foreach($fields as $field_id => $field){
			$name = $section_id.'_'.$field_id;
			
			if($field['args']['type'] == 'checkbox'){
				$values[$name] = !empty($values[$name]);
			}
			elseif($field['args']['type'] == 'number'){
				$values[$name] = isset($values[$name]) ? intval($values[$name]) : $GLOBALS['so_settings_defaults'][$name];
			}
			
			// See if this needs any special validation
			if(!empty($field['args']['validator']) && method_exists('SO_Settings_Validator', $field['args']['validator'])){
				$values[$name] = call_user_func(array('SO_Settings_Validator', $field['args']['validator']), $values[$name]);
			}
		}
	}

	return $values;
}

/**
 * Settings validators
 */
class SO_Settings_Validator {
	/**
	 * Extracts the twitter username from the string.
	 * 
	 * @static
	 * @param $twitter
	 * @return bool|mixed|string
	 */
	static function twitter($twitter){
		$twitter = trim($twitter);
		if(empty($twitter)) return false;
		if($twitter[0] == '@') return preg_replace('/^@+/', '', $twitter);

		$url = parse_url($twitter);

		// Check if this is a twitter URL
		if(isset($url['host']) && !in_array($url['host'], array('twitter.com', 'www.twitter.com'))) return false;

		// Check if this is a fragment URL
		if(isset($url['fragment']) && $url['fragment'][0] == '!')
			return substr($url['fragment'],2);

		// And our very last attempt... take it that the username is on the end of the path
		if(isset($url['path'])){
			$parts = explode('/', $url['path']);
			$username = array_pop($parts);
			return $username;
		}

		return false;
	}
}