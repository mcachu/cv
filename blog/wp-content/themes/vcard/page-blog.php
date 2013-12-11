<?php
/*
Template Name: Blog
*/

get_header();

global $wp_query;
query_posts(array(
	'post_type' => 'post',
	'paged' => $wp_query->get('paged')
));
get_template_part('loop');
wp_reset_query();

get_footer();