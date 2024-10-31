<?php
	
	/** ===============
		* Easy Digital Downloads Options
	*/
	
	function mystem_extra_edd( $wp_customize ) {
		
		$wp_customize->add_section( 'mystem_extra_edd', array(
		'title'       => __( 'Easy Digital Downloads', 'mystem-edd' ),
		'description' => __( 'Easy Digital Downloads Options', 'mystem-edd' ),
		'priority'   => 16,
		) );
		
		// Cart menu option
		$wp_customize->add_setting( 'mystem_edd_cart_menu', array(
		'default' => '1',
		'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'mystem_edd_cart_menu', array(
		'type' => 'select',
		'label' => __( 'Display in the menu cart:', 'mystem-edd' ),
		'section' => 'mystem_extra_edd',
		'choices' => array(
		'1'	=> 'Total sum & quantity items',
		'2'	=> 'Only total sum',
		'3'	=> 'Only quantity items',
		),
		) );
		
		// Download Category global Template
		$wp_customize->add_setting( 'mystem_edd_category_download', array(
		'default' => 'default',
		'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'mystem_edd_category_download', array(
		'type' => 'select',
		'label' => __( 'Choose global Category Template:', 'mystem-edd' ),
		'section' => 'mystem_extra_edd',
		'choices' => array(
		'default'	                    => 'Default',
		'grid'	                      => 'Grid',
		'grid-third'	                => 'Grid 3 column',
		'grid-without-sidebar'	      => 'Grid without sidebar',
		'grid-without-sidebar-third'	=> 'Grid without sidebar 3 column',
		'grid-without-sidebar-fourth'	=> 'Grid without sidebar 4 column',
		'classic'	                    => 'Classic',
		'classic-without-sidebar'	    => 'Classic without sidebar',
		),
		) );
		
		// Single Download global Template
		$wp_customize->add_setting( 'mystem_edd_single_download', array(
		'default' => 'default',
		'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'mystem_edd_single_download', array(
		'type' => 'select',
		'label' => __( 'Choose global Download Template:', 'mystem-edd' ),
		'section' => 'mystem_extra_edd',
		'choices' => array(
		'default'	=> 'Default',
		'template-1'	=> 'Template 1',				
		),
		) );
		
	}
	add_action( 'customize_register', 'mystem_extra_edd' );
	
	
	/** ===============
		* No purchase button below download content
	*/
	remove_action( 'edd_after_download_content', 'edd_append_purchase_link' );
	
	// EDD Reviews remove output from product page
	if ( function_exists( 'edd_reviews' ) ) {
		remove_filter( 'the_content', array( edd_reviews(), 'load_frontend' ) );
	}
		function mystem_edd_load_frontend(  ) {
			global $post;
			
			if ( $post && $post->post_type == 'download' && is_singular( 'download' ) && is_main_query() && ! post_password_required() ) {
				ob_start();
				edd_get_template_part( 'reviews' );
				if ( get_option( 'thread_comments' ) ) {
					edd_get_template_part( 'reviews-reply' );
				}
				$review = ob_get_contents();
				ob_end_clean();
			}
			
			echo $review;
		}
		
		add_action( 'mystem_edd_review', 'mystem_edd_load_frontend' );
		
		function mystem_extra_edd_color_scheme_css() {
			$header_background        = get_theme_mod( 'mystem_header_background', '#363636' );
			$second_color             = get_theme_mod( 'mystem_second_color', '#cccccc' );
			$header_color             = get_theme_mod( 'mystem_header_color', '#ffffff' );
			$border                   = get_theme_mod( 'mystem_border', '4' );
			$blocks_color             = get_theme_mod( 'mystem_blocks_color', '#ffffff' );
			$text_color               = get_theme_mod( 'mystem_text_color', '#363636' );
			$color                    = get_theme_mod( 'mystem_color', '#02C285' );
			$background_color         = get_theme_mod( 'mystem_background_color', '#d9dfe5' );
			$css = ' 
			#mystem-edd-modal-window {
			background: '. esc_attr( $blocks_color ) .';
			border-radius: ' . esc_attr( $border ) . 'px;
			border: 1px solid '. esc_attr( $header_background ) .'; 
			}			
			.mystem-edd-modal-title {
			background: '. esc_attr( $header_background ) .';
			color: '. esc_attr( $header_color ) .';
			border-radius: ' . esc_attr( $border ) . 'px ' . esc_attr( $border ) . 'px 0 0 ;
			}	
			.widget_mystem_edd_author_items_widget .widget-img img{
			border-radius: ' . esc_attr( $border ) . 'px;
			}
			.widget-area .widget_mystem_edd_author_items_widget .downloads-title a, .widget-area .widget_mystem_edd_downloads_widget .downloads-title a{
			color: '. esc_attr( $text_color ) .';
			}
			.mystem-edd-image-button {
			color: ' . esc_attr( $color ) . ';
			}
			a.edd-button-preview, button.edd-image-button {
			color: ' . esc_attr( $blocks_color ) . ';
			background: ' . esc_attr( $color ) . ';				
			}
			.edd-image-sliders .edd-image-button:first-child {
			border-radius: ' . esc_attr( $border ) . 'px 0 0 ' . esc_attr( $border ) . 'px;
			}
			.edd-image-sliders .edd-image-button:last-child {
			border-radius: 0 ' . esc_attr( $border ) . 'px ' . esc_attr( $border ) . 'px 0;
			}
			.edd-product .headline, edd-product article {
			background: ' . esc_attr( $blocks_color ) . ';
			}
			
			';
			$css = trim( preg_replace( '~\s+~s', ' ', $css ) );
			
			return $css;
			
		}
		
	// Meta for Download
	if ( ! function_exists( 'mystem_edd_on' ) ) :
	/**
		* Prints HTML with meta information for the current post-date/time and author.
	*/
	function mystem_download_on() {
		$category     =  get_the_term_list( get_the_ID(), 'download_category', '', ', ' ); 
	?>
	<span class="byline mystem-cat">
		<i class="far fa-folder"></i>
		<?php echo $category; ?>
	</span>		
	<div class="price">
		<?php edd_price( get_the_ID() ); ?>
	</div>
	<?php
	}
	endif;
		
		
	
	
	// Meta for Download
	if ( ! function_exists( 'mystem_edd_rating' ) ) :
	function mystem_edd_rating() {
		if( class_exists( 'EDD_Reviews' ) ) {			
			$starRating=null;
			$starRating = edd_reviews()->average_rating(false);
			$starRating=(round($starRating));	
			echo '<span class="product-rating">';
			for ($i = 1; $i <= 5; $i++) {									
				if ($i <= $starRating){
					echo '<i class="fas fa-star"></i>';	
				}
				else {
					echo '<i class="far fa-star"></i>';
				}
			}
								
			echo '</span>';
		}
	}
	endif;
	
	// Meta for Download
	if ( ! function_exists( 'mystem_edd_rating' ) ) :
	function mystem_edd_rating() {
		if( class_exists( 'EDD_Reviews' ) ) {			
			$starRating=null;
			$starRating = edd_reviews()->average_rating(false);
			wp_star_rating( ['rating'=>$starRating, 'type'=>'rating', 'number'=>0 ] );
			$starRating=(round($starRating));	
			echo '<span class="product-rating">';
			for ($i = 1; $i <= 5; $i++) {									
				if ($i <= $starRating){
					echo '<i class="fas fa-star"></i>';	
				}
				else {
				    echo '<i class="far fa-star"></i>';
				}
			}	
			echo '</span>';
		}
	}
	endif;
	
	// admin Style for EDD Download
	function mystem_admin_edd_download_style( $hook ){
		if( $hook == 'post-new.php' ||  $hook == 'post.php' ) {
			wp_enqueue_style( 'mystem-download', plugin_dir_url( __FILE__ ) .'assets/css/admin.css' );
		}
	}
	add_action( 'admin_enqueue_scripts', 'mystem_admin_edd_download_style' );
	
	// Metabox fields
	function mystem_edd_download_details(){
		$details = array(
		'description' => array(
		'name' => __('Subheading ', 'mystem-edd'),
		'description' => __('Small description of item', 'mystem-edd'),
		),
		'preview_url' => array(
		'name' => __('Preview Url', 'mystem-edd'),
		'description' => __('Preview Url to show in single download page', 'mystem-edd'),
		),
		'docs_url' => array(
		'name' => __('Docs Url ', 'mystem-edd'),
		'description' => __('Documentations URL', 'mystem-edd'),
		),
		'item_files' => array(
		'name' => __('Files Included ', 'mystem-edd'),
		'description' => __('Files Included', 'mystem-edd'),
		),
		'item_version' => array(
		'name' => __('Item version', 'mystem-edd'),
		'description' => __('Enter Item Version', 'mystem-edd'),
		),
		'item_chenglog' => array(
		'name' => __('Item chenglog', 'mystem-edd'),
		'description' => __('Enter Item chenglog', 'mystem-edd'),
		),
		
		);		
		
		return $details;
		
	}
	
	// Add metabox into EDD Download
	function mystem_extra_edd_add_metbox(){
		$screens = array(  'download' );
		add_meta_box( 'mystemedd_sectionid', __('Download Details', 'mystem-edd'), 'mystem_extra_edd_metabox', $screens );
	}
	add_action('add_meta_boxes', 'mystem_extra_edd_add_metbox');
	
	// HTML metabox code
	function mystem_extra_edd_metabox( $post, $meta ){
		$post_id = $post->ID;
		$data = get_post_meta( $post_id, '_mystem_edd_download', false );	
		wp_nonce_field( plugin_basename(__FILE__), 'mystem_edd_noncename' );
		$details = mystem_edd_download_details();
		echo '<div class="mystem-metabox">';
		foreach ($details as $key => $value) {
			if ( $key == 'item_chenglog' ) {				
				echo '<div class="mystem-element">';
				echo '<div class="mystem-label"><label for="mystem_edd_' . $key . '">' . $value['name'] . '</label></div>';
				$mystem_value = isset( $data[0][$key] ) ? $data[0][$key] : '';
				echo '<div class="mystem-value">';
				wp_editor( $mystem_value, 'mystem_edd_' . $key, array(
					'wpautop'       => 1,
					'media_buttons' => 0,
					'textarea_name' => 'mystem_edd[' . $key . ']',				
				) );
				echo '<p>' .$value['description']. '</p></div>';
				echo '</div>';
				continue;
				
			}		
			echo '<div class="mystem-element">';
			echo '<div class="mystem-label"><label for="mystem_edd_' . $key . '">' . $value['name'] . '</label></div>';
			$mystem_value = isset( $data[0][$key] ) ? $data[0][$key] : '';
			echo '<div class="mystem-value"><input type="text" id= "mystem_edd_' . $key . '" name="mystem_edd[' . $key . ']" value="'.$mystem_value.'" />';echo '<p>' .$value['description']. '</p></div>';
			echo '</div>';
		}
		echo '</div>';
		
		
	}
	
	add_action( 'save_post', 'mystem_extra_edd_save_download' );
	function mystem_extra_edd_save_download( $post_id ) {
		
		if ( ! isset( $_POST['mystem_edd'] ) )
		return;
		
		if ( ! wp_verify_nonce( $_POST['mystem_edd_noncename'], plugin_basename(__FILE__) ) )
		return;
		
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return;
		
		if( ! current_user_can( 'edit_post', $post_id ) )
		return;	
	
		$data = array();
		foreach ( $_POST['mystem_edd'] as $key => $value) {
			if ( $key == 'item_chenglog' ) {
				$data[$key] =  wp_kses_post( $value ) ;
			}
			elseif ( $key == 'preview_url' || $key == 'docs_url' ) {
				$data[$key] = esc_url( $value );
			}
			else {
				$data[$key] = sanitize_text_field( $value );
			}			
		}						
		update_post_meta( $post_id, '_mystem_edd_download', $data );
	}
	
	require_once plugin_dir_path(__FILE__) . '/widgets/item-details.php';
	require_once plugin_dir_path(__FILE__) . '/widgets/more-author-downloads.php';
	require_once plugin_dir_path(__FILE__) . '/widgets/display-downloads.php';
	
	
	function mystem_extra_edd_widgets_include()
	{			
		register_widget('MyStem_Download_Details_Widget');
		register_widget('MyStem_More_Author_Items_Widget');
		register_widget('MyStem_Edd_Downloads_Widget');
	}
	add_action('widgets_init', 'mystem_extra_edd_widgets_include');
	
	
	// Add Download Gallery.
	require_once plugin_dir_path(__FILE__) . '/gallery/class-multi-images.php';
	
	add_action( 'add_meta_boxes', 'mystem_multiple_images_meta_box_add' );
	
	function mystem_multiple_images_meta_box_add()
	{
		add_meta_box( 'mystem-download-images', esc_html__( 'Download Gallery', 'mystem-edd' ), 'MyStem_Download_Images::output', 'download', 'side', 'low' );
	}
	function mystem_admin_script_enqueue( $hook ) {
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			wp_enqueue_script('mystem-admin-scripts', plugin_dir_url(__FILE__) .'/gallery/js/script.js','','', true);
			wp_enqueue_style( 'mystem-admin-css', plugin_dir_url(__FILE__) .'/gallery/css/style.css');
			
		}
	}
	add_action( 'admin_enqueue_scripts', 'mystem_admin_script_enqueue' );
	add_action( 'save_post',  'MyStem_Download_Images::save' );
	
	
	// Featured Downloads
	add_action('post_submitbox_misc_actions', 'mystem_download_create_feature_item');
	add_action('save_post', 'mystem_download_save_feature_item');
	function mystem_download_create_feature_item()
	{
    $post_id = get_the_ID();
    if (get_post_type($post_id) != 'download') {
			return;
		}
    $value = get_post_meta($post_id, '_mystem_download_feature_item', true);
    wp_nonce_field('mystem_feature_item_nonce_'.$post_id, 'mystem_feature_item_nonce');
	?>
	<div class="misc-pub-section misc-pub-section-last">
		<label><input type="checkbox" value="1" <?php checked($value, true, true); ?> name="_mystem_download_feature_item" /><?php _e('Feature Item', 'mystem-edd'); ?></label>
	</div>
	<?php
	}
	function mystem_download_save_feature_item($post_id)
	{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
    if (
		!isset($_POST['mystem_feature_item_nonce']) ||
		!wp_verify_nonce($_POST['mystem_feature_item_nonce'], 'mystem_feature_item_nonce_'.$post_id)
    ) {
			return;
		}
    if (!current_user_can('edit_post', $post_id)) {
			return;
		}
    if (isset($_POST['_mystem_download_feature_item'])) {
			update_post_meta($post_id, '_mystem_download_feature_item', sanitize_text_field( $_POST['_mystem_download_feature_item'] ));
			} else {
			delete_post_meta($post_id, '_mystem_download_feature_item');
		}
	}
	
	
	add_filter( 'walker_nav_menu_start_el', 'mystem_walker_nav_menu_start_el_icon', 10, 4 );
	function mystem_walker_nav_menu_start_el_icon( $item_output, $item, $depth, $args ){
		if( is_array( $item->classes ) ){
			$classes_str = implode(" ", $item->classes);
			preg_match("/mystem-edd-cart/",$classes_str,$matches);
			$cart = ( empty($matches[0]) ? '' : $matches[0] );
			if( !empty( $cart ) ){				
				$cart_type = get_theme_mod( 'mystem_edd_cart_menu', 1 );
				switch ($cart_type) {
					case 1:
					$cart_menu = wp_kses_data( edd_cart_subtotal() ) . ' - ' .  wp_kses_data( sprintf( _n( '%d item', '%d items', edd_get_cart_quantity(), 'mystem-edd' ), edd_get_cart_quantity() ) );
					break;
					case 2:
					$cart_menu = wp_kses_data( edd_cart_subtotal() );
					break;
					case 3:
					$cart_menu = wp_kses_data( sprintf( _n( '%d item', '%d items', edd_get_cart_quantity(), 'mystem-edd' ), edd_get_cart_quantity() ) );
					break;
				}
				if ( $args->theme_location === 'header' || $args->theme_location === 'primary' ) { 
					$cart_menu = '<sup>' . $cart_menu . '</sup>';
				}				
				$item_output = '<a class="cart-contents" href="' . esc_url( edd_get_checkout_uri() ) . '">';
				$item_output .= $item->title . $cart_menu;				
				$item_output .= '</a>';			
			}
			
		}
		return $item_output;
	}
	
	