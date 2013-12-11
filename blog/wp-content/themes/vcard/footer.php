		</div>
			
		<ul id="footer-widgets">
			<?php dynamic_sidebar('site-footer') ?>
			<div class="clear"></div>
		</ul>
		
		<div id="page-top"></div>
		<div id="page-bottom"></div>
		<div id="page-shadow"></div>
	</div>
	<div id="footer">
		<div class="copyright"><?php printf(__('Copyright %s', 'vcard'), get_bloginfo('name')) ?></div>
		<div class="designed"><?php printf(__('Theme By %s', 'vcard'), '<a href="http://siteorigin.com">SiteOrigin</a>') ?></div>
		<div class="clear"></div>
	</div>
</div>

<?php wp_footer() ?>
</body>
</html>