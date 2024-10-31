<?php
	/**
		* Product Details Widget
		*
	*/
	class MyStem_Download_Details_Widget extends WP_Widget {
		/** Constructor */
		public function __construct() {
			parent::__construct(
			'mystem_download_details_widget',
			__( 'MyStem Download Details', 'mystem-edd' ),
			array(
			'description' =>__( 'Display the details of a specific Download', 'mystem-edd' ),
			)
			);
			if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
				add_action('wp_enqueue_scripts', array( $this, 'download_details_widget_scripts' ));
			}
		}
		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {
			$args['id'] = ( isset( $args['id'] ) ) ? $args['id'] : 'mystem_download_details_widget';
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );
			$download_id = get_the_ID();
			$version = !empty( $data[0]['item_version'] ) ? $data[0]['item_version'] : get_post_meta( $download_id, '_edd_sl_version', true );
			$tag_list = get_the_term_list( $download_id, 'download_tag', '', ', ' );
			$sales = get_post_meta( $download_id, '_edd_download_sales', true );
			$data = get_post_meta( $download_id, '_mystem_edd_download', false );
			$docsurl = !empty( $data[0]['docs_url'] ) ? $data[0]['docs_url'] : '';
			$files = !empty( $data[0]['item_files'] ) ? $data[0]['item_files'] : '';
			$chenglog = !empty( $data[0]['item_chenglog'] ) ? $data[0]['item_chenglog'] : get_post_meta( $download_id, '_edd_sl_changelog', true );
			$starRating=null;
			if( class_exists( 'EDD_Reviews' ) ) {
				$total_rating = wp_count_comments( $download_id )->total_comments;
				$starRating = edd_reviews()->average_rating(false);
				$starRatinground = (round($starRating));
			}
			$version_inc = $instance['version_inc'] ? $instance['version_inc'] : '';
			$changelog = $instance['changelog'] ? $instance['changelog'] : '';
			$sales_inc = $instance['sales_inc'] ? $instance['sales_inc'] : '';
			$docsurl_inc = $instance['docsurl_inc'] ? $instance['docsurl_inc'] : '';
			$files_inc = $instance['files_inc'] ? $instance['files_inc'] : '';
			$rating_inc = $instance['rating_inc'] ? $instance['rating_inc'] : '';
			echo $args['before_widget'];
			if( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			echo '<ul>';
		?>
		<li><?php _e('Upload Date', 'mystem-edd'); ?> <span><?php echo get_the_date();?></span> </li>
		<?php if (!empty($version) && !empty($version_inc)) { ?>
			<li>
				<?php _e('Version', 'mystem-edd'); ?>:
				<span><?php echo $version; ?> <?php if ( !empty( $changelog ) ) { ?><a href="#mystem-edd-modal" class='changelog' title="<?php _e('View Changelog', 'mystem-edd'); ?>"><i class="far fa-file-alt"></i></a><?php } ?></span>
			</li>
		<?php } ?>
		<?php if(isset($files)&& (strlen($files)>0) &&  !empty($files_inc)) { ?>
			<li>
				<?php _e('Files Included', 'mystem-edd'); ?>:
				<span><?php echo $files;?></span>
			</li>
		<?php } ?>
		<?php if(isset($docsurl)&& (strlen($docsurl)>0) &&  !empty($docsurl_inc)) { ?>
			<li>
				<?php _e('Documentation', 'mystem-edd'); ?>:
				<span><a href="<?php echo esc_url($docsurl); ?>" target="_blank"><?php _e('read', 'mystem-edd'); ?></a></span>
			</li>
		<?php } ?>
		<?php if(isset($sales_inc)&&strlen($sales_inc)>0){ ?>
			<li>
				<?php _e('Sales', 'mystem-edd'); ?>:
				<span><?php echo $sales; ?></span>
			</li>
		<?php } ?>
		<?php if(class_exists( 'EDD_Reviews' ) &&  !empty($rating_inc) ) { ?>
			<li><?php _e('Rating', 'mystem-edd'); ?>:
				<span class="edd-rating" title="<?php echo $starRatinground;?> average based on <?php echo $total_rating;?> ratings.">
					<a href="#edd-reviews">
						<?php
							for ($i = 1; $i <= 5; $i++) {
								if ($i <= $starRatinground){
									echo '<i class="fas fa-star"></i>';
								}
								else {
                					echo '<i class="far fa-star"></i>';
                				}
							}
						?>
					</a>
				</span>
			<?php } ?>
			<?php
				echo '</ul>';
				if ( !empty( $changelog ) ) {
				?>
				<div id="mystem-edd-modal-overlay" class="mystem-edd-modal-overlay">
					<div id="mystem-edd-modal-overclose" class="mystem-edd-modal-overclose"></div>
					<div id="mystem-edd-modal-window" class="mystem-edd-modal-window">
						<div class="mystem-edd-modal-title">
							<h3><?php the_title(); ?></h3>
							<div id="mystem-edd-modal-close">
								<i class="fas fa-times" id="close-times"></i>
							</div>
						</div>
						<?php echo $chenglog; ?>
					</div>
				</div>
				<?php // Used by themes. Closes the widget
				}
				echo $args['after_widget'];
			}
			
			/** @see WP_Widget::form */
			public function form( $instance ) {
				// Set up some default widget settings.
				$defaults = array(
				'title'        => __( 'Download Details', 'mystem-edd' ),
				'version_inc'  => 'on',
				'sales_inc'    => 'on',
				'changelog'     => 'on',
				'files_inc'    => 'on',
				'docsurl_inc'  => 'on',
				'rating_inc'   => 'on',
				);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>
			<!-- Title -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'mystem-edd' ) ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
			</p>
			<!-- Version of Item -->
			<p>
				<input <?php checked( $instance['version_inc'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'version_inc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'version_inc' ) ); ?>" type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'version_inc' ) ); ?>"><?php esc_html_e( 'Show Version', 'mystem-edd' ); ?></label>
			</p>
			<!-- Show changelog -->
			<p>
				<input <?php checked( $instance['changelog'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'changelog' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'changelog' ) ); ?>" type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'changelog' ) ); ?>"><?php esc_html_e( 'Show Changelog', 'mystem-edd' ); ?></label>
			</p>
			<!-- Show includes files -->
			<p>
				<input <?php checked( $instance['files_inc'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'files_inc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'files_inc' ) ); ?>" type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'files_inc' ) ); ?>"><?php esc_html_e( 'Show includes files', 'mystem-edd' ); ?></label>
			</p>
			<!-- Show URL to documentation -->
			<p>
				<input <?php checked( $instance['docsurl_inc'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'docsurl_inc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'docsurl_inc' ) ); ?>" type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'docsurl_inc' ) ); ?>"><?php esc_html_e( 'Show Url on Docs', 'mystem-edd' ); ?></label>
			</p>
			<!-- Show count sales -->
			<p>
				<input <?php checked( $instance['sales_inc'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'sales_inc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sales_inc' ) ); ?>" type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'sales_inc' ) ); ?>"><?php esc_html_e( 'Show Sales', 'mystem-edd' ); ?></label>
			</p>
			<!-- Show Rating -->
			<p>
				<input <?php checked( $instance['rating_inc'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'rating_inc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rating_inc' ) ); ?>" type="checkbox" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'rating_inc' ) ); ?>"><?php esc_html_e( 'Show Rating', 'mystem-edd' ); ?></label>
			</p>
			<?php }
			/** @see WP_Widget::update */
			public function update( $new_instance, $old_instance ) {
				$instance = $old_instance;
				$instance['title'] = strip_tags( $new_instance['title'] );
				$instance['version_inc']  = isset( $new_instance['version_inc'] )  ? $new_instance['version_inc']  : '';
				$instance['sales_inc'] = isset( $new_instance['sales_inc'] ) ? $new_instance['sales_inc'] : '';
				$instance['changelog'] = isset( $new_instance['changelog'] ) ? $new_instance['changelog'] : '';
				$instance['files_inc'] = isset( $new_instance['files_inc'] ) ? $new_instance['files_inc'] : '';
				$instance['docsurl_inc'] = isset( $new_instance['docsurl_inc'] ) ? $new_instance['docsurl_inc'] : '';
				$instance['rating_inc'] = isset( $new_instance['rating_inc'] ) ? $new_instance['rating_inc'] : '';
				return $instance;
			}
			/** include scripts and styles */
			function download_details_widget_scripts() {
				if( ! apply_filters( 'show_item_details_widget_script', true, $this->id_base ) )
				return;
				$theme_url = get_stylesheet_directory_uri();
				wp_enqueue_style('modal-window', MyStem_EDD_Integration_Url.'assets/css/modal-windows.css');
				wp_enqueue_script('effects', MyStem_EDD_Integration_Url.'assets/js/effects.js',array('jquery'),'1.0', true);
				wp_enqueue_script('modal-window', MyStem_EDD_Integration_Url.'assets/js/modal-window.js',array('jquery'),'1.0', true);
			}
		}