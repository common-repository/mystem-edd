<?php
	/*
		* Template Name: EDD Members
	*/
get_header();
$primary = is_user_logged_in() ? 'primary' : 'primary-full';
?>

<div id="<?php echo $primary; ?>" class="content-area">
	<main id="main" class="site-main" role="main">
		
		<?php while ( have_posts() ) : the_post(); ?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>
			
			<div class="entry-content">
				<?php 
					if ( is_user_logged_in()){ 
						the_content(); 
					}
					else {
						echo '<center><h3>' . __('Please login to access your account information ', 'mystem-edd') . '</h3></center>';
						echo do_shortcode( '[edd_login]' );
					}
				?>				
			</div>
		</article>
		
		<?php endwhile; // end of the loop. ?>
		
	</main>
</div>

<?php 
	if ( is_user_logged_in()){  
		require_once plugin_dir_path( __FILE__ ) . 'sidebar-member.php';
	}
	?>

<?php get_footer(); ?>
