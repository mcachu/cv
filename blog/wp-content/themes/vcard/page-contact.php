<?php
/*
Template Name: Contact
*/

get_header();
the_post();
?>

<div id="post-<?php the_ID() ?>" <?php post_class(array('post')) ?>>
	<h1 class="entry-title"><?php the_title() ?></h1>

	<div class="entry-content">
		<?php the_content() ?>
		<div class="clear"></div>
	</div>

	<?php comments_template() ?>
</div>

<?php get_footer() ?>