<?php get_header(); ?>

<div id="page-not-found" class="post">
	<h1 class="entry-title"><?php _e('Not Found', 'vcard') ?></h1>

	<div class="entry-content">
		<?php _e("We couldn't find the page you were looking for.", 'vcard') ?>
	</div>

	<?php comments_template() ?>
</div>

<?php get_footer() ?>