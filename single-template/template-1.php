<?php
	/**
		* The Template for displaying single posts without sidebar.
		*
		* @package MyStem EDD
		* @subpackage
		* @since 1.0
	*/
	
	get_header(); 
	
	$data = get_post_meta( $post->ID, '_mystem_edd_download', false );
	$description = !empty( $data[0]['description'] ) ? $data[0]['description'] : '';
	
	$categories = get_the_terms( $post->ID, 'download_category' );				
	$cat_ID = absint( $categories[0]->term_id );					
	$mystem_cat_meta = get_option( 'mystem_taxonomy_'.$cat_ID );
	
	$icon_color = !empty( $mystem_cat_meta['icon_color'] ) ? ' style="color:' . esc_attr( $mystem_cat_meta['icon_color'] ) . '"' : '';
	$icon = !empty( $mystem_cat_meta['icon_field'] ) ? '<i class="' . esc_attr( $mystem_cat_meta['icon_field'] ) . '"' . $icon_color . '></i> ' : '';
?>

<header class="entry-header">
	<h1 class="entry-title"><?php echo $icon . get_the_title(); ?></h1>
	
	<div class="download-description">
	<?php if ( !empty( $description ) ):?>
		<p><?php echo $description;?></p>
	<?php endif;?>
	</div>
	
	
</header>





<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		
		<?php while ( have_posts() ) : the_post(); ?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<?php
				// display featured image
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'featured-img', array(
					'class' => 'featured-img mystem-post-slide',
					) );
				}
				$post_gallery_img = get_post_meta($post->ID, '_mystem_post_image_gallery', true);
				$arr=explode(",",$post_gallery_img);
				if(!empty($post_gallery_img))
				{
					foreach ($arr as $value)
					{
						$img_url = wp_get_attachment_image_src ( $value, 'full', false );
						$img_url2 = wp_get_attachment_image_src ( $value, false );
						echo '<img src="'.$img_url[0].'" class="featured-img mystem-post-slide" style="width:100%; display: none;"> ';
					}
				}
				
			?>			
			<div class="edd-image-sliders">
				<?php if (!empty($post_gallery_img)):?>
				<button class="edd-image-button" onclick="plusDivs(-1)"><i class="fas fa-chevron-left"></i></button>		
				<?php endif;?>
				
				<?php 
					$data = get_post_meta( $post->ID, '_mystem_edd_download', false );
					$previewurl = !empty( $data[0]['preview_url'] ) ? $data[0]['preview_url'] : '';
				?>                      
				<?php if( !empty( $previewurl ) ) : ?> 				
				<a href="<?php echo esc_url( $previewurl ); ?>" class="edd-button-preview" target="_blank">Live Preview <i class="fas fa-eye"></i></a>				
				<?php endif ?>
				
				<?php if (!empty($post_gallery_img)):?>
				<button class="edd-image-button" onclick="plusDivs(1)"><i class="fas fa-chevron-right"></i></button>
				<?php endif;?>
			</div>
			
			
			
			<div class="entry-content">
				<?php
					the_content();					
				?>
			</div>
			
		</article>
		
		
		
		<?php if ( function_exists( 'edd_reviews' ) ) : ?>
		<div class="single-post-footer clear">
			<?php do_action('mystem_edd_review'); ?>
		</div>
		<?php endif; ?>	
		
		<?php endwhile; // end of the loop. ?>
		
	</main>
</div>
<?php require_once plugin_dir_path( __FILE__ ) . 'sidebar-single.php'; ?>
<?php get_footer(); ?>
