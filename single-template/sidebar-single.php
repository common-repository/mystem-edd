<?php
	/**
		* MyStem Sidebar for EDD Single Download
		*
		* @package WordPress
		* @subpackage MyStem
		* @since MyStem 1.0
	*/
?>
<div id="secondary" class="widget-area" role="complementary">
	
		<?php if ( ! dynamic_sidebar( 'edd-single-download' ) ) : ?>
		
		<aside id="search" class="widget widget_search">
			<?php get_search_form(); ?>
		</aside>		
		
		
		<?php endif; // end sidebar widget area ?>
	
</div>
