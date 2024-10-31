<?php
	/**
		* The Grid template for Category.
		*
		* @package WordPress
		* @subpackage MyStem
		* @since MyStem 1.2
	*/
	
	get_header(); 
	$term = get_queried_object();
	$mystem_cat_meta = !empty( $term->term_id ) ? get_option( "mystem_taxonomy_".$term->term_id ) : '';
	$icon_color = !empty( $mystem_cat_meta['icon_color'] ) ? ' style="color:' . esc_attr( $mystem_cat_meta['icon_color'] ) . '"' : '';
	$icon = !empty( $mystem_cat_meta['icon_field'] ) ? '<i class="' . esc_attr( $mystem_cat_meta['icon_field'] ) . '"' . $icon_color . '></i> ' : '';
?>

<section id="primary-full" class="content-area edd-product">
	<main id="main" class="site-main" role="main">
		
		<?php if ( have_posts() ) : ?>
		
		<?php if ( empty( $mystem_cat_meta['hide_header'] ) ) : ?>
		<header class="page-header">
			<h1 class="page-title">
				<?php echo $icon . single_cat_title( '', false ); ?>
			</h1>
			<?php echo category_description(); ?>
		</header>
		<?php endif; ?>
		
		<div class="cat-grid fourth">
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
			
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>		
				<?php
					// display featured image
				if ( has_post_thumbnail() && ! empty( get_theme_mod( 'mystem_featured_image', '1' ) ) ) :?>
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_post_thumbnail( 'featured-img', array(
					'class' => 'featured-img',
				) );?></a>
				<?php
					endif;
				?>
				<header class="entry-header">
					<h4 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
					<?php if ( 'download' == get_post_type() ) : ?>
					<div class="entry-meta ">
						<?php mystem_download_on(); ?>
					</div>
					<?php endif; ?>
				</header>					
				
				<div class="entry-summary">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 24, '', get_the_author_meta( 'display_name' ), array() ) . ' ' . get_the_author_meta( 'display_name' ); ?>
					
					<?php mystem_edd_rating(); ?>
					
				</div>
			
			</article>
			
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>	
		
		<?php else : ?>
		
		<?php get_template_part( 'content/content', 'none' ); ?>
		
		<?php endif; ?>
		
	</main>
</section>
<?php get_footer(); ?>
