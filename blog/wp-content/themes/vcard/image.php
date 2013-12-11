<?php get_header(); the_post(); global $post; ?>

<div id="post-<?php the_ID() ?>" <?php post_class(array('post')) ?>>
	<h1 class="entry-title"><?php the_title() ?></h1>
	
	<div class="entry-info">
		<?php printf(__('Posted on %s in %s', 'vcard'), get_the_date(), '<a href="'.get_permalink($post->post_parent).'">'.get_the_title($post->post_parent).'</a>') ?>
		<?php the_tags(__(' - tagged: ', 'vcard')) ?>
	</div>

	<div class="entry-content">
		<?php the_content() ?>
		
		<?php print wp_get_attachment_image( $post->ID, array( 700, 1024 ) ); ?>

		<div class="clear"></div>
		<nav id="nav-single">
			<span class="nav-previous"><?php previous_image_link( false, __( 'Previous' , 'vcard' ) ); ?></span>
			<span class="nav-next"><?php next_image_link( false, __( 'Next' , 'vcard' ) ); ?></span>
		</nav><!-- #nav-single -->
	</div>
	
	<?php comments_template() ?>
</div>

<?php get_footer() ?>