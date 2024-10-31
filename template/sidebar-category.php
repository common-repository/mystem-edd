<?php
	/**
		* MyStem Sidebar for EDD Category
		*
		* @package     MyStem EDD
		* @subpackage  
		* @copyright   Copyright (c) 2018, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
?>
<div id="secondary" class="widget-area" role="complementary">
	
		<?php if ( ! dynamic_sidebar( 'edd-category' ) ) : ?>
		
		<aside id="search" class="widget widget_search">
			<?php get_search_form(); ?>
		</aside>
		
		
		<?php endif; // end sidebar widget area ?>
	
</div>
