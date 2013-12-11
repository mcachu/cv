<?php get_header(); the_post(); ?>

<div id="post-<?php the_ID() ?>" <?php post_class(array('post')) ?>>
	<h1 class="entry-title"><?php the_title() ?></h1>
	
	<div class="entry-info">
		<?php printf(__('Posted on %s in %s', 'vcard'), get_the_date(), get_the_category_list(', ')) ?>
		<?php the_tags(__(' - tagged: ', 'vcard')) ?>
	</div>

	<div class="entry-content">
		<?php the_content() ?>

		<div class="clear"></div>
		<?php wp_link_pages( array() ); ?>
	</div>
	
	<?php comments_template() ?>
</div>

<?php get_footer() ?>