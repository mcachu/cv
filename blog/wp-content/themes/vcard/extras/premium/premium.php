<?php

/**
 * Display the premium admin menu
 * @return mixed
 */
function so_premium_admin_menu(){
	if(defined('SO_IS_PREMIUM')) return;
	add_theme_page(__('Premium Upgrade', 'siteorigin'), __('Premium Upgrade', 'siteorigin'), 'switch_themes', 'premium_upgrade', 'so_premium_page_render');
}
add_action('admin_menu', 'so_premium_admin_menu');

/**
 * Render the premium page
 */
function so_premium_page_render(){
	$theme = basename(get_template_directory());
	
	if(isset($_GET['action'])) $action = $_GET['action'];
	else $action = 'view';
	
	
	switch($action){
		case 'view':
			$premium = apply_filters('so_premium_content', array());
			
			if(empty($premium)){
				?>
				<div class="wrap" id="theme-upgrade">
					<h2><?php _e('Premium Upgrade', 'siteorigin') ?></h2>
					<p>
						<?php
						printf(
							__("There's a premium version of %s available, <a href='%s'>find out more</a>.", 'siteorigin'),
							ucfirst($theme),
							'http://siteorigin.com/premium/'.$theme.'/'
						);
						?>
					</p>
				</div>
				<?php
				return;	
			}
			
			?>
			<div class="wrap" id="theme-upgrade">

				<form id="theme-upgrade-info" method="post" action="<?php print add_query_arg('action', 'enter-order') ?>">
					<p>
						<?php
						printf(
							__("After you pay for %s Premium, we'll email you a download link and an order number to your <strong>PayPal email address</strong>. ", 'siteorigin').
							__('You can install manually, or enter your order number here to enable an automatic upgrade.', 'siteorigin'),
							ucfirst($theme)
						);
						?>
					</p>

					<label><strong><?php _e('Order Number', 'siteorigin') ?></strong></label>
					<input type="text" class="regular-text" name="order_number" />
					<input type="submit" value="<?php esc_attr_e('Enable Upgrade', 'siteorigin') ?>" />
					<?php wp_nonce_field('save_order_number', '_upgrade_nonce') ?>
				</form>
	
				<a href="#" id="theme-upgrade-already-paid"><?php _e('Already Paid?', 'siteorigin') ?></a>
				<?php if(isset($premium['premium_title'])) : ?><h2><?php print $premium['premium_title'] ?></h2><?php endif; ?>
				<?php if(isset($premium['premium_summary'])) : ?><p><?php print $premium['premium_summary'] ?></p><?php endif; ?>

				<?php if(isset($premium['buy_button']) && isset($premium['buy_url'])) : ?>
					<p class="download">
						<a href="<?php print esc_attr($premium['buy_url']) ?>" class="buy-button"><img src="<?php print esc_attr($premium['buy_button']) ?>" /></a>
						<?php if(isset($premium['buy_message_1'])) : ?><span><?php print $premium['buy_message_1'] ?></span><?php endif; ?>
					</p>
				<?php endif; ?>
						
				<?php if(!empty($premium['featured'])) : ?>
					<p id="promo-image">
						<img src="<?php print esc_attr($premium['featured'][0]) ?>" width="<?php print intval($premium['featured'][1]) ?>" height="<?php print intval($premium['featured'][2]) ?>" class="magnify" />
					</p>
				<?php endif; ?>
				<div class="content">
					<?php if(!empty($premium['features'])) : foreach($premium['features'] as $feature) : ?>
						<h3><?php print $feature['heading'] ?></h3>
						<p><?php print $feature['content'] ?></p>
					<?php endforeach; endif; ?>
				</div>

				<?php if(isset($premium['buy_button']) && isset($premium['buy_url'])) : ?>
					<p class="download">
						<a href="<?php print esc_attr($premium['buy_url']) ?>" class="buy-button"><img src="<?php print esc_attr($premium['buy_button']) ?>" /></a>
						<?php if(isset($premium['buy_message_2'])) : ?><span><?php print $premium['buy_message_2'] ?></span><?php endif; ?>
					</p>
				<?php endif; ?>
				
				</div>
				<div id="magnifier"><div class="image"></div>
			</div>
			<?php
			break;
		
		case 'enter-order' :
			$option_name = 'so_order_number_'.$theme;
			if(isset($_POST['_upgrade_nonce']) && wp_verify_nonce($_POST['_upgrade_nonce'], 'save_order_number') && isset($_POST['order_number'])){
				update_option($option_name, trim($_POST['order_number']));
			}

			// Validate the order number
			$result = wp_remote_get(
				add_query_arg(array(
					'order_number' => get_option($option_name),
					'action' => 'validate_order_number',
				), SO_THEME_ENDPOINT.'/premium/'.$theme.'/')
			);
			$valid = null;
			if(!is_wp_error($result)){
				$validation_result = unserialize($result['body']);
				$valid = isset($validation_result['valid']) ? $validation_result['valid'] : null;
				if($valid){
					// Trigger a refresh of the theme update information
					set_site_transient('update_themes', null);
				}
			}
			
			
			?>
				<div class="wrap" id="theme-upgrade">
					<h2>Your Order Number is [<?php print get_option($option_name) ?>]</h2>
					
					<?php if(is_null($valid)) : ?>
						<p>
							<?php _e("There was a problem contacting our validation servers.", 'siteorigin') ?>
							<?php _e("Please try again later, or upgrade manually using the ZIP file we sent you.", 'siteorigin') ?>
						</p>
					<?php elseif($valid) : ?>
						<p>
							<?php _e("We've validated your order number.", 'siteorigin') ?>
							<?php
								printf(
									__('You can update now, or later on your <a href="%s">Themes page</a>.', 'siteorigin'),
									admin_url('themes.php')
								);
							?>
							<?php _e('This update will unlock all the premium features.', 'siteorigin') ?>
						</p>
						<p class="submit">
							<?php
							$update_url = wp_nonce_url(admin_url('update.php?action=upgrade-theme&amp;theme=' . urlencode($theme)), 'upgrade-theme_' . $theme);
							$update_onclick = 'onclick="if ( confirm(\'' . esc_js( __("Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'siteorigin') ) . '\') ) {return true;}return false;"';
							?>
							<a href="<?php print esc_attr($update_url) ?>" <?php print $update_onclick ?> class="button-primary">
								<?php _e('Update Theme', 'siteorigin') ?>
							</a>
						</p>
					<?php else : ?>
						<p>
							<?php _e('We  <strong>invalid</strong>.', 'siteorigin') ?>
							<?php _e("Please try again, or upgrade manually using the ZIP file we sent you.", 'siteorigin') ?>
							<?php _e('Note that you need a valid order number to receive automatic updates in the future.', 'siteorigin') ?>
						</p>
					<?php endif; ?>
				</div>
			<?php
			break;	
	}
}

/**
 * Enqueue admin scripts
 * @param $prefix
 * @return mixed
 */
function so_premium_admin_enqueue($prefix){
	if($prefix != 'appearance_page_premium_upgrade') return;

	wp_enqueue_script('siteorigin-magnifier', get_template_directory_uri().'/extras/premium/magnifier.js', array('jquery'), SO_THEME_VERSION);
	wp_enqueue_script('siteorigin-premium-upgrade', get_template_directory_uri().'/extras/premium/premium.js', array('jquery'), SO_THEME_VERSION);
	wp_enqueue_style('siteorigin-premium-upgrade', get_template_directory_uri().'/extras/premium/premium.css', array(), SO_THEME_VERSION);
}
add_action('admin_enqueue_scripts', 'so_premium_admin_enqueue');