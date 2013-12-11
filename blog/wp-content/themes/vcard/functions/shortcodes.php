<?php

function vcard_shortcode_resume_entry($atts, $content){
	/**
	 * @var $title
	 * @var $description
	 * @var $date
	 */
	extract( shortcode_atts( array(
		'title' => null,
		'description' => null,
		'date' => null,
	), $atts ) );
	
	ob_start();
	?>
	<div class="resume-entry">
		<div class="date"><?php print $date ?></div>
		<h4><?php print $title ?></h4>
		<p><?php print $description ?></p>
		<div class="resume-entry-decoration"></div>
	</div>
	<?php
	return ob_get_clean();	
}

add_shortcode('resume_entry', 'vcard_shortcode_resume_entry');


function vcard_shortcode_resume_skill($atts){
	/**
	 * @var $title
	 * @var $level
	 */
	extract(shortcode_atts(array(
		'title' => null,
		'level' =>50,
	), $atts));
	
	ob_start();
	?>
	<div class="resume-skill">
		<div class="bar"><div class="bar-fill" style="width:<?php print $level ?>%"><div class="bar-edge bar-edge-right"></div><div class="bar-edge bar-edge-left"></div></div></div>
		<h4><?php print $title ?></h4>
		<div class="resume-skill-decoration"></div>
	</div>
	<?php

	return ob_get_clean();
}

add_shortcode('resume_skill', 'vcard_shortcode_resume_skill');

/**
 * Display a portfolio gallery.
 * 
 * A lot of this code comes from WordPress' gallery_shortcode
 */
function vcard_shortcode_gallery($attr){
	global $post;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}
	
	ob_start();
	
	?><div class="portfolio-gallery"><?php
	foreach($attachments as $attachment){
		$orig = wp_get_attachment_image_src($attachment->ID, 'original');
		?>
		<a class="entry" href="<?php print $orig[0] ?>" title="<?php print esc_attr($attachment->post_excerpt) ?>">
			<?php print wp_get_attachment_image($attachment->ID, 'portfolio-gallery') ?>
			<div class="title"><h4><?php print $attachment->post_title ?></h4></div>
			<?php if(!empty($attachment->post_excerpt)) : ?><div class="caption"><p><?php print $attachment->post_excerpt ?></p></div><?php endif; ?>
		</a>
		<?php
	}
	?><div class="clear"></div></div><?php
	
	return ob_get_clean();
}

add_shortcode('vcard_gallery', 'vcard_shortcode_gallery');