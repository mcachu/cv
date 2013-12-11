<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
	<?php if(strtolower(get_comment_type()) != 'pingback') : ?>
		<div class="comment-avatar">
			<?php print get_avatar(get_comment_author_email(), 78); ?>
			<div class="comment-decoration"></div>
		</div>
	<?php endif; ?>
	<div class="comment-main">
		<div class="comment-text entry-content"><?php comment_text() ?></div>
		<div class="comment-info">
			<span class="author"><?php comment_author_link() ?></span>
			<span class="date"><?php comment_date() ?></span>
		</div>
	</div>
	<div class="clear"></div>
	