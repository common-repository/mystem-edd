<?php if ( ! defined( 'ABSPATH' ) ) exit;
	/**
		* MyStem EDD Shortcodes
		*
		* @package     MyStem EDD
		* @subpackage  
		* @copyright   Copyright (c) 2018, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	add_shortcode('mystem_downloads', 'mystem_extra_downloads_query');
	
	function mystem_extra_downloads_query( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'products'         => 'featured',
			'type'             => 'slider',		
			'columns'          => 2,
			'number'           => 9,
			'exclude'          => '',
			'title'            => '',
			'background'       => '#fff',
			'color'            => '#1396e1',
			'imgheight'        => 'auto',	
			'category'         => '',
		), $atts, 'mystem_downloads' );
		global $post;
		
		$query = array(
			'numberposts'      => $atts['number'],
			'post_status'      => 'publish',			
			'post_type'        => 'download',
			'exclude'          => $atts['exclude'],						
		);	
		
		switch ( $atts['products'] ) {
			case 'featured':					
				$query['meta_key'] = '_mystem_download_feature_item';
				$query['orderby']  = 'rand';
				$query['order']    = 'ASC';
			break;
			
			case 'popular':					
				$query['meta_key'] = '_edd_download_sales';
				$query['orderby']  = 'meta_value_num';
				$query['order']    = 'DESC';
			break;				
			
			case 'latest':										
				$query['orderby']  = 'date';
				$query['order']    = 'DESC';
			break;
			
			case 'cat':				
				$query['orderby']  = 'date';
				$query['order']    = 'DESC';
				$query['tax_query'] = array(
					array(
						'taxonomy' => 'download_category',
						'field' => 'tag_ID',
						'terms' => $atts['category'], 
						'include_children' => false
						)
					);
			break;
			
		}
		
		
		$downloads = get_posts( $query );
		
		$type = ( $atts['type'] == 'grid' ) ? 'cat-grid' : $atts['type'];
		if($type == 'cat-grid' ) {
			if( $atts['columns'] == 3 ) {
				$type .= ' third';					
			}
			elseif ( $atts['columns'] == 4 ) {
				$type .= ' fourth';
			}
		}
		
	?>
	<div class="edd-product <?php echo $atts['products']; ?> mystem-extra-downloads">	
		<?php if( !empty( $atts['title'] ) ) : ?>
		<div class="headline">
			<h4><?php echo $atts['title']; ?></h4>			
			<?php if( $atts['type'] == 'slider' ) : ?>
			<div class="slide-control-wrap">
				<div class="slide-control-left">						
					<i class="fas fa-angle-left"></i>												
				</div>					
				<div class="slide-control-right">
					<i class="fas fa-angle-right"></i>
				</div>
			</div>	
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="<?php echo $type; ?>">
			
			<?php
				foreach ($downloads as $post) { 
					setup_postdata($post);
				?>
				
				<article>		
					<?php
						// display featured image
					if ( has_post_thumbnail() ) :?>
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'featured-img', array(
						'class' => 'featured-img',
					) );?></a>
					<?php
						endif;
					?>
					<header class="entry-header">
						<h4 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>					
						<div class="entry-meta">
							<?php mystem_download_on(); ?>
						</div>					
					</header>					
					
					<div class="entry-summary">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 24, '', get_the_author_meta( 'display_name' ), array() ) . ' ' . get_the_author_meta( 'display_name' ); ?>
						
						<?php mystem_edd_rating(); ?>
						
					</div>
					
				</article>
				
				<?php
				}
				wp_reset_postdata();
			?>				
		</div>			
	</div>
	<?php			
		$style = '
		.edd-product.'.$atts['products'].' .headline, .edd-product.'.$atts['products'].' article {
			background-color: ' . esc_attr( $atts['background'] ) .';
		}
		.edd-product.'.$atts['products'].' article .featured-img{
			height: ' . esc_attr( $atts['imgheight'] ) .';
		}
		.edd-product.'.$atts['products'].' .headline:before {
			background-color: ' . esc_attr( $atts['color'] ) .';
		}		
		.edd-product.'.$atts['products'].' .slide-control-left,.edd-product.'.$atts['products'].' .slide-control-right {
			background-color: ' . esc_attr( $atts['color'] ) .';
			color: ' . esc_attr( $atts['background'] ) .';
		}
		.edd-product.'.$atts['products'].' .slide-control-left i, .edd-product.'.$atts['products'].' .slide-control-right i {
			color: ' . esc_attr( $atts['background'] ) .';
		}
		';
		$style = trim( preg_replace( '~\s+~s', ' ', $style ) );
		echo '<style>' . $style .'</style>';
		
		
		if($type == 'slider' ) {
			wp_enqueue_style( 'mystem-extra-owl-carousel', plugin_dir_url( __FILE__ ) . 'assets/css/owl.carousel.min.css', array());
			wp_enqueue_script( 'mystem-extra-owl-carousel', plugin_dir_url( __FILE__ ) . 'assets/js/owl.carousel.min.js', array( 'jquery' ) );			
			wp_enqueue_script( 'mystem-extra-owl', plugin_dir_url( __FILE__ ) . 'assets/js/owl.js', array( 'jquery' ) );		
		}
	}				
	
	add_shortcode('mystem_account', 'mystem_extra_account');
	
	function mystem_extra_account( $atts, $content = null ) {
		
		$atts = shortcode_atts( array(
			'meta' => 'nicename',	
			'size' => '96',
		), $atts, 'mystem_account' );
		
		if( is_user_logged_in() ) {
			$cur_user_id = get_current_user_id();
			if ( $atts['meta'] == 'avatar' ) {
				$avatar = get_avatar( $cur_user_id, $atts['size'], '', '', array() );
				return $avatar;
			}
			else {
				$user = get_userdata( $cur_user_id );
				$field = 'user_' . $atts['meta'];
				$user_info = $user->$field;			
				
				return $user_info;
			}
						
		}		
	}
	
	add_shortcode('mystem_edd_count', 'mystem_edd_count');
	
	function mystem_edd_count( $atts, $content = null ) {
		
		$atts = shortcode_atts( array(
			'type' => 'products',				
		), $atts, 'mystem_edd_count' );
		
		switch ( $atts['type'] ) {
			case 'products':					
				$counter = wp_count_posts('download')->publish;				
			break;
			
			case 'users':					
				$counter = edd_count_total_customers();
			break;				
			
			case 'sales':										
				$counter = edd_get_total_sales();				
			break;
			
		}
		return $counter;
	}
	