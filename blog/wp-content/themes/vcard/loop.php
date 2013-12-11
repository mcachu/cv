<?php if(have_posts()) : ?>
	<div id="blog-loop">
		<?php while(have_posts()) : the_post(); global $post; ?>
		<div class="post">
			<?php if(has_post_thumbnail()) : ?>
				<div class="thumbnail">
					<a href="<?php the_permalink() ?>" class="entry-link"><?php the_post_thumbnail() ?></a>
				</div>
			<?php endif; ?>
	
			<div class="post-summary">
				<a href="<?php the_permalink() ?>" class="entry-link"><h3><?php the_title() ?></h3></a>
				<div class="date">
					<?php if(trim(get_the_title()) == '') : ?><a href="<?php the_permalink() ?>"><?php endif; ?>
					<?php the_date() ?>
					<?php if(trim(get_the_title()) == '') : ?></a>	<?php endif; ?>
				</div>
				<div class="excerpt"><?php the_excerpt() ?></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php endwhile; ?>
		
		<?php global $wp_query; if ($wp_query->max_num_pages) : ?>
			<div class="post-nav">
				<?php posts_nav_link(' ', '<em></em>'.__('Newer Entries', 'vcard'), '<em></em>'.__('Older Entries', 'vcard')) ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
	</div>
<?php else : ?>
	<div class="message"><?php _e('No Posts Found', 'vcard') ?></div>
<?php endif; ?>