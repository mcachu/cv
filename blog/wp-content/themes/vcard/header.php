<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />

	<title><?php wp_title('|', true, 'right'); ?></title>

	<link rel="stylesheet" type="text/css" media="screen" href="<?php print get_stylesheet_uri() ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<?php wp_head(); ?>
</head>

<body <?php body_class() ?>>

<div class="container">
	<div id="page">
		<?php
		wp_nav_menu(array(
			'theme_location' => 'main-menu',
			'container_id' => 'main-menu',
			'fallback_cb' => false,
		));
		?>
		<div id="page-decoration"></div>
		
		<div id="site-info">
			<a href="<?php echo site_url() ?>" title="<?php print esc_attr(bloginfo('name').' - '.bloginfo('description')) ?>">
				<img src="<?php print get_header_image() ?>" width="130" height="160" />
			</a>
			<div class="text">
				<a href="<?php echo site_url() ?>" title="<?php print esc_attr(bloginfo('name').' - '.bloginfo('description')) ?>">
					<h1><?php bloginfo('name') ?></h1>
					<h2><?php bloginfo('description') ?></h2>
				</a>
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="content"> 