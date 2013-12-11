<div class="wrap">
	<div id="icon-themes" class="icon32"><br></div>
	<h2><?php _e('Theme Settings','siteorigin') ?></h2>

	<form action="options.php" method="post">
		<?php settings_fields('theme_settings'); ?>
		<?php do_settings_sections('theme_settings') ?>

		<p class="submit"><input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'siteorigin'); ?>" /></p>
	</form>
</div> 