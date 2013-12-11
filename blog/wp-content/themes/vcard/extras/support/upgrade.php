<div class="wrap" style="max-width:680px">
	<h2><?php _e('Premium Theme Support', 'siteorigin') ?></h2>
	<p>
		<?php
		$theme = basename(get_template_directory());
		printf(
			__('We offer email support to everyone who upgrades to <a href="%s">%s Premium</a>.', 'siteorigin'),
			admin_url('themes.php?page=premium_upgrade'),
			ucfirst($theme)
		);
		?>
	</p>
	<p>
		<?php
		printf(
			__("We still love you, even if you're using %s Free. We just want to offer the best service possible to the fine people who have purchased our Premium themes.", 'siteorigin'),
			ucfirst($theme)
		);
		?></p>
</div> 