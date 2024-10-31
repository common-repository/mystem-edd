<?php if ( ! defined( 'ABSPATH' ) ) exit;
	/**
		* MyStem EDD Templates
		*
		* @package     MyStem EDD
		* @subpackage  
		* @copyright   Copyright (c) 2018, Dmytro Lobov
		* @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
		* @since       1.0
	*/
	
	if( !class_exists( 'MyStem_EDD_Integration' ) ) {
		
		define( 'MyStem_EDD_Integration_Url', plugin_dir_url( __FILE__ ) );
		
		class MyStem_EDD_Integration {
			function __construct() {	
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );					
				add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
				add_action( 'download_category_add_form_fields', array( $this, 'add_meta_field' ), 10, 2 );
				add_action( 'download_tag_add_form_fields', array( $this, 'add_meta_field' ), 10, 2 );
				add_action( 'download_category_edit_form_fields', array( $this, 'edit_meta_field' ), 10, 2 );
				add_action( 'download_tag_edit_form_fields', array( $this, 'edit_meta_field' ), 10, 2 );
				add_action( 'edited_download_category', array( $this, 'save_meta_field' ), 10, 2 );  
				add_action( 'create_download_category', array( $this, 'save_meta_field' ), 10, 2 );
				add_action( 'edited_download_tag', array( $this, 'save_meta_field' ), 10, 2 );  
				add_action( 'create_download_tag', array( $this, 'save_meta_field' ), 10, 2 );				
				add_filter( 'taxonomy_template', array( $this, 'get_taxonomy_template' ) );				
				add_action( 'pre_get_posts', array( $this, 'number_posts' ) );	
				add_action( 'widgets_init', array( $this, 'widgets_init' ) );
				add_filter( 'single_template', array( $this, 'get_custom_post_type_template' ) );
				add_filter( 'body_class', array( $this, 'category_sidebar' ), 20, 2 );
				add_filter( 'theme_page_templates', array( $this, 'edd_page_template' ), 10, 3 );
				add_filter( 'template_include', array( $this, 'edd_load_template' ), 99 );			
			}
			
			
			function front_scripts() {
				// wp_enqueue_script('mystem-extra-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array('jquery'), null, true);
				
				if( !is_category() && !is_tag() ){
					wp_enqueue_style( 'mystem-extra-edd', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
					wp_add_inline_style( 'mystem-extra-edd', mystem_extra_edd_color_scheme_css() );
				}
				if ( is_single() )  { 
					global $post;
					if ( 'download' == get_post_type( $post->ID ) ) {
						wp_enqueue_script('mystem-extra-image-slides', plugin_dir_url( __FILE__ ) . 'assets/js/image-slides.js', array('jquery'), null, true);
					}
				}
			}
			
			function admin_scripts( $hook ) {
				// Load taxonomy and term pages
				if( $hook == 'edit-tags.php' ||  $hook == 'term.php' ) {		
					// font awesome stylesheet
					wp_enqueue_style( 'mystem-font-awesome', get_template_directory_uri() . '/font-awesome/css/fontawesome-all.min.css', array(), '5.0.11', 'all' );
					
					// include color picker
					wp_enqueue_style('wp-color-picker');
					wp_enqueue_script('wp-color-picker');
					
					// include icon picker
					wp_enqueue_script('mystem-fonticonpicker', get_template_directory_uri() . '/inc/assets/fonticonpicker/js/fonticonpicker.min.js', array('jquery'));
					
					wp_enqueue_style('mystem-fonticonpicker', get_template_directory_uri() . '/inc/assets/fonticonpicker/css/fonticonpicker.min.css');
					
					wp_enqueue_style('mystem-fonticonpicker-darkgrey', get_template_directory_uri() . '/inc/assets/fonticonpicker/css/fonticonpicker.darkgrey.min.css');
					
					// include script for taxonomy
					wp_enqueue_script( 'mystem-taxonomy', plugin_dir_url( __FILE__ ) . 'assets/js/taxonomy.js' );
				}
				else{
					return;
				}
			}
			
			function add_meta_field() {
				// this will add the custom meta field to the add new term page
			?>			
			<div class="form-field">
				<label for="mystem_cat_meta[icon_field]"><?php _e( 'Icon', 'mystem-edd' ); ?></label>				
				<select class="iconpicker" name="mystem_cat_meta[icon_field]">
					<?php					
						$icons = mystem_fontawesome_icons();
						foreach ( $icons as $icon ){
							echo '<option>' . $icon . '</option>';
						}
					?>
				</select>
				<p class="description"><?php _e( 'Select the icon','mystem-edd' ); ?></p>
			</div>
			
			<div class="form-field">
				<label for="mystem_cat_meta[icon_color]"><?php _e( 'Icon color', 'mystem-edd' ); ?></label>
				<input type="text" name="mystem_cat_meta[icon_color]" value="#383838" class="color-picker-field">
				<p class="description"><?php _e( 'Select Icon color','mystem-edd' ); ?></p>
			</div>
			
			<div class="form-field">
				<label for="mystem_cat_meta[cat_template]"><?php _e('Category Template', 'mystem-edd'); ?></label>
				<select name="mystem_cat_meta[cat_template]">					
					<option value='default'><?php _e('Default','mystem-edd'); ?></option>
					<option value='grid'><?php _e('Grid','mystem-edd'); ?></option>
					<option value='grid-third'><?php _e('Grid 3 column','mystem-edd'); ?></option>
					<option value='grid-without-sidebar'><?php _e('Grid without sidebar','mystem-edd'); ?></option>
					<option value='grid-without-sidebar-third'><?php _e('Grid without sidebar 3 column','mystem-edd'); ?></option>
					<option value='grid-without-sidebar-fourth'><?php _e('Grid without sidebar 4 column','mystem-edd'); ?></option>
					<option value='classic'><?php _e('Classic','mystem-edd'); ?></option>
					<option value='classic-without-sidebar'><?php _e('Classic without sidebar','mystem-edd'); ?></option>
				</select>
				<p class="description"><?php _e( 'Select a specific template for this category','mystem-edd' ); ?></p>		
			</div>
			
			<div class="form-field">
				<label for="mystem_cat_meta[hide_header]"><?php _e('Hide Header', 'mystem-edd'); ?></label>
				<input type="checkbox" name="mystem_cat_meta[hide_header]" value="1">
				<p class="description"><?php _e( 'Hide title and description','mystem-edd' ); ?></p>
			</div>
			
			<div class="form-field">
				<label for="mystem_cat_meta[number_posts]"><?php _e('Number posts', 'mystem-edd'); ?></label>
				<input type="number" name="mystem_cat_meta[number_posts]" step="1" class="small-text" value="<?php echo get_option('posts_per_page'); ?>" >
				<p class="description"><?php _e( 'Number posts of category','mystem-edd' ); ?></p>
			</div>	
			
			<div class="form-field">
				<label for="mystem_cat_meta[single_template]"><?php _e('Single DOwnload Template', 'mystem-edd'); ?></label>
				<select name="mystem_cat_meta[single_template]">
					<option value='default'><?php _e('Default','mystem-edd'); ?></option>
					<option value='template-1'><?php _e('Template 1','mystem-edd'); ?></option>					
				</select>
				<p class="description"><?php _e( 'Select a specific template for single download in category','mystem-edd' ); ?></p>		
			</div>
			
			
			<?php
			}
			
			function edit_meta_field($term) {
				
				// put the term ID into a variable
				$t_id = $term->term_id;
				
				// retrieve the existing value(s) for this meta field. This returns an array
			$mystem_cat_meta = get_option( "mystem_taxonomy_$t_id" ); ?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="mystem_cat_meta[icon_field]"><?php _e( 'Icon', 'mystem-edd' ); ?></label></th>
				<td>
					<select class="iconpicker" name="mystem_cat_meta[icon_field]">
						<?php					
							$sel_icon = esc_attr( $mystem_cat_meta['icon_field'] ) ? esc_attr( $mystem_cat_meta['icon_field'] ) : '';
							$icons = mystem_fontawesome_icons();
							foreach ( $icons as $icon ){
								echo '<option' . selected( $sel_icon, $icon ) . '>' . $icon . '</option>';						
							}
						?>
					</select>
					<p class="description"><?php _e( 'Select the icon','mystem-edd' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="mystem_cat_meta[icon_color]"><?php _e( 'Icon color', 'mystem-edd' ); ?></label></th>
				<td>
					<input type="text" name="mystem_cat_meta[icon_color]" value="<?php echo esc_attr(!empty( $mystem_cat_meta['icon_color'] ) ) ? esc_attr( $mystem_cat_meta['icon_color'] ) : '#383838'; ?>" class="color-picker-field">
					<p class="description"><?php _e( 'Select Icon color','mystem-edd' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="mystem_cat_meta[cat_template]"><?php _e('Category Template', 'mystem-edd'); ?></label></th>
				<td>
					<select name="mystem_cat_meta[cat_template]">
						<?php					
							$sel_cat = esc_attr( $mystem_cat_meta['cat_template'] ) ? esc_attr( $mystem_cat_meta['cat_template'] ) : 'default';					
						?>						
						<option value='default' <?php selected( $sel_cat, 'default' ); ?>><?php _e('Default','mystem-edd'); ?></option>
						<option value='grid' <?php selected( $sel_cat, 'grid' ); ?>><?php _e('Grid','mystem-edd'); ?></option>
						<option value='grid-third' <?php selected( $sel_cat, 'grid-third' ); ?>><?php _e('Grid 3 column','mystem-edd'); ?></option>
						<option value='grid-without-sidebar' <?php selected( $sel_cat, 'grid-without-sidebar' ); ?>><?php _e('Grid without sidebar','mystem-edd'); ?></option>
						<option value='grid-without-sidebar-third' <?php selected( $sel_cat, 'grid-without-sidebar-third' ); ?>><?php _e('Grid without sidebar 3 column','mystem-edd'); ?></option>
						<option value='grid-without-sidebar-fourth' <?php selected( $sel_cat, 'grid-without-sidebar-fourth' ); ?>><?php _e('Grid without sidebar 4 column','mystem-edd'); ?></option>
						<option value='classic' <?php selected( $sel_cat, 'classic' ); ?>><?php _e('Classic','mystem-edd'); ?></option>
						<option value='classic-without-sidebar' <?php selected( $sel_cat, 'classic-without-sidebar' ); ?>><?php _e('Classic without sidebar','mystem-edd'); ?></option>
						
					</select>
					<p class="description"><?php _e( 'Select a specific template for this category','mystem-edd' ); ?></p>
				</td>
			</tr>
			
			<tr class="form-field">
				<th scope="row" valign="top"><label for="mystem_cat_meta[cat_sidebar]"><?php _e('Category Template', 'mystem-edd'); ?></label></th>
				<td>
					<select name="mystem_cat_meta[cat_sidebar]">
						<?php					
							$sel_sidebar = esc_attr( $mystem_cat_meta['cat_sidebar'] ) ? esc_attr( $mystem_cat_meta['cat_sidebar'] ) : 'default';
						?>						
						<option value='default' <?php selected( $sel_sidebar, 'default' ); ?>><?php _e('Default','mystem-edd'); ?></option>
						<option value='sidebar-content' <?php selected( $sel_sidebar, 'sidebar-content' ); ?>><?php _e('Left','mystem-edd'); ?></option>
						<option value='content-sidebar' <?php selected( $sel_sidebar, 'content-sidebar' ); ?>><?php _e('Right','mystem-edd'); ?></option>
					</select>
					<p class="description"><?php _e( 'Select the location of the sidebar in the category','mystem-edd' ); ?></p>
				</td>
			</tr>
			
			<tr class="form-field">
				<th scope="row" valign="top"><label for="mystem_cat_meta[hide_header]"><?php _e('Hide Header', 'mystem-edd'); ?></label></th>
				<td>
					<?php $hide_header = !empty( $mystem_cat_meta['hide_header'] ) ? 1 : 0; ?>
					<input type="checkbox" name="mystem_cat_meta[hide_header]" value="1"<?php checked( $hide_header ); ?>>
					<p class="description"><?php _e( 'Hide title and description','mystem-edd' ); ?></p>
				</td>	
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="mystem_cat_meta[number_posts]"><?php _e('Number posts', 'mystem-edd'); ?></label></th>
				<td>
					<input type="number" name="mystem_cat_meta[number_posts]" step="1" class="small-text" value="<?php echo esc_attr(!empty( $mystem_cat_meta['number_posts'] ) ) ? esc_attr( $mystem_cat_meta['number_posts'] ) : get_option('posts_per_page'); ?>" >
					<p class="description"><?php _e( 'Number posts of category','mystem-edd' ); ?></p>
				</td>	
			</tr>		
			
			<tr class="form-field">
				<th scope="row" valign="top"><label for="mystem_cat_meta[single_template]"><?php _e('Single Download Template', 'mystem-edd'); ?></label></th>
				<td>
					<select name="mystem_cat_meta[single_template]">
						<?php					
							$sel_cat = esc_attr( $mystem_cat_meta['single_template'] ) ? esc_attr( $mystem_cat_meta['single_template'] ) : 'default';					
						?>
						<option value='default' <?php selected( $sel_cat, 'default' ); ?>><?php _e('Default','mystem-edd'); ?></option>
						<option value='template-1' <?php selected( $sel_cat, 'template-1' ); ?>><?php _e('Template 1','mystem-edd'); ?></option>						
					</select>
					<p class="description"><?php _e( 'Select a specific template for single download in category','mystem-edd' ); ?></p>
				</td>
			</tr>
			<?php
			}
			
			function save_meta_field( $term_id ) {
				if ( isset( $_POST['mystem_cat_meta'] ) ) {
					$t_id = $term_id;
					$mystem_cat_meta = get_option( "mystem_taxonomy_$t_id" );
					$cat_keys = array_keys( $_POST['mystem_cat_meta'] );
					foreach ( $cat_keys as $key ) {
						if ( isset ( $_POST['mystem_cat_meta'][$key] ) ) {
							$mystem_cat_meta[$key] = sanitize_text_field( $_POST['mystem_cat_meta'][$key] );
						}						
					}
					if ( !isset ( $_POST['mystem_cat_meta']['hide_header'] ) ) { 
						$mystem_cat_meta['hide_header'] = 0;
					}
					// Save the option array.
					update_option( "mystem_taxonomy_$t_id", $mystem_cat_meta );
				}
			} 				
			
			function get_taxonomy_template( $category_template ) {
				$cat_ID = absint( get_queried_object()->term_id );
				$cat_meta = get_option( 'mystem_taxonomy_'.$cat_ID );
				if (isset( $cat_meta['cat_template'] )  && $cat_meta['cat_template'] != 'default' ){
					$temp = plugin_dir_path( __FILE__ ) . 'template/'.$cat_meta['cat_template'].'.php';
				}
				else {
					$template = get_theme_mod( 'mystem_edd_category_download', 'default' );						
					if ( $template != 'default' ){
						$temp = plugin_dir_path( __FILE__ ) . 'template/' . $template . '.php';	
					}
				}
				
				if (!empty($temp)) {
					return $temp;
				}					
				
				return $category_template;
			}
			
			function category_sidebar( $classes ) {
			    if ( is_page_template('page-member.php') ) {
			        $classes[] = 'sidebar-content';
			    }
				if ( is_tax( array( 'download_category', 'download_tag' ) ) ) {
					$cat_ID = absint( get_queried_object()->term_id );
					$cat_meta = get_option( 'mystem_taxonomy_'.$cat_ID );
					if ( isset( $cat_meta['cat_sidebar'] ) && $cat_meta['cat_sidebar'] != 'default' ) {
						$sel = $cat_meta['cat_sidebar'];
						$layout = (get_theme_mod( 'mystem_layout' ) == 'sc') ? 'sidebar-content' : 'content-sidebar';
						if ($sel != $layout) {						
							$key = array_search( $layout, $classes );
							if (false !== $key) {
								unset( $classes[ $key ]);
							}
							$classes[] = $sel;
						}
					}		
				}
				if ( is_single() ) {
					global $post;
					if ($post->post_type == 'download') {
						$categories = get_the_terms( $post->ID, 'download_category' );				
						$cat_ID = absint( $categories[0]->term_id );
						$cat_meta = get_option( 'mystem_taxonomy_'.$cat_ID );
						if ( isset( $cat_meta['single_template'] ) && $cat_meta['single_template'] != 'default' ) { 
							$classes[] = 'mystem-download-'.$cat_meta['single_template'];
						}
						else {
							$template = get_theme_mod( 'mystem_edd_single_download', 'default' );
							$classes[] = 'mystem-download-'.$template;
						}					
					}
				}
				return $classes;
			}
			
			
			function number_posts($query) {
				if ( $query->is_tax ) {
					$cat_ID = absint( get_queried_object()->term_id );
					$cat_meta = get_option( 'mystem_taxonomy_'.$cat_ID );
					$number_posts = !empty( $cat_meta['number_posts'] ) ? $cat_meta['number_posts'] : get_option('posts_per_page');
					$query->set('posts_per_page',$number_posts);
				}
			}
			
			function get_custom_post_type_template($single_template) {
				global $post;
				if ($post->post_type == 'download') {
					$categories = get_the_terms( $post->ID, 'download_category' );				
					$cat_ID = absint( $categories[0]->term_id );					
					$cat_meta = get_option( 'mystem_taxonomy_'.$cat_ID );				
					if ( isset( $cat_meta['single_template'] ) && $cat_meta['single_template'] != 'default' ) {
						$temp = plugin_dir_path( __FILE__ ) . 'single-template/'.$cat_meta['single_template'].'.php';							
					}
					else {
						$template = get_theme_mod( 'mystem_edd_single_download', 'default' );						
						$temp = plugin_dir_path( __FILE__ ) . 'single-template/' . $template . '.php';						
					}
					if ( !empty( $temp ) ) {
						return $temp;
					}	
				}
				return $single_template;								
			}
			
			function widgets_init() {
				register_sidebar( array(
				'name'          => __( 'EDD Category ', 'mystem-edd' ),
				'id'            => 'edd-category',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
				) );
				
				register_sidebar( array(
				'name'          => __( 'EDD Single Download', 'mystem-edd' ),
				'id'            => 'edd-single-download',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
				) );
				
				register_sidebar( array(
				'name'          => __( 'EDD Member Page', 'mystem-edd' ),
				'id'            => 'edd-member-page',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
				) );
				
			}
			
			function edd_page_template( $page_templates, $thisis, $post) {
				$page_templates['page-member.php'] = 'EDD Members';		
				return $page_templates;				
			}
			
			
			function edd_load_template ( $template ) {				
				global $post;    
				$page_template_slug     = get_page_template_slug( $post->ID );
				
				if( $page_template_slug == 'page-member.php' ){
					return plugin_dir_path( __FILE__ ) .'page-templates/page-member.php';
				}	
				// if( is_search() ){
					// global $wp_query;
					// $post_type = ( get_query_var('post_type') ) ? get_query_var('post_type') : '';
					// if( $wp_query->is_search && $post_type == 'download' )   
					// {
						// $template_search = get_theme_mod( 'mystem_edd_category_download', 'default' );						
						// if ( $template_search != 'default' ){
							// $temp = plugin_dir_path( __FILE__ ) . 'template/' . $template_search . '.php';	
						// }
						// if ( !empty( $temp ) ) {
							// return $temp;
						// }					 
					// } 			
				// }
				return $template;
				
			}
			
		}
		require_once plugin_dir_path( __FILE__ ) . 'functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'shortcodes.php';
		}							