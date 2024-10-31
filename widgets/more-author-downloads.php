<?php
	
	/**
		* EDD Author Items Widget 
		*
	*/
	
	
	class MyStem_More_Author_Items_Widget extends WP_Widget {
		/** Constructor */
		public function __construct() {
			parent::__construct(
			'mystem_edd_author_items_widget',
			__( 'MyStem More Author\'s Items', 'mystem-edd' ),
			array(
			'description' =>__( 'Display more author\'s EDD items on download page', 'mystem-edd' ),
			)
			);
			
			
		}
		
		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {
			
			$args['id'] = ( isset( $args['id'] ) ) ? $args['id'] : 'mystem_edd_author_items_widget';	
			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Author\'s Items', 'mystem-edd' );
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );
			
			$number_posts = $instance['number_posts'] ? $instance['number_posts'] : '5'; 
			
			$authorID = get_the_author_meta( 'ID' );
			$post_id = get_the_ID();
			global $post;
			$args_posts = array(
			'numberposts'      => 5,
			'post_status'      => 'publish',			
			'post_type'        => 'download',
			'author' => $authorID,
			'orderby' => 'rand',
			'order'    => 'ASC',			
			'suppress_filters' => true, 
			);						
			$posts = get_posts( $args_posts );
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
		?>
		<ul>
			<?php 
				foreach ($posts as $post) { 
					setup_postdata($post);											
					$thumbnail = get_the_post_thumbnail( $post->ID, array(75,75) );
					
				?>
				<li>
					<span class="mystem-edd-download">
						<span class="widget-img">
							<?php if ( $thumbnail ) {
								echo $thumbnail;
								} else {
								$icon = '<span class="fa-stack fa-2x">
								<i class="fas fa-square fa-stack-2x"></i>
								<i class="fas fa-cloud-download-alt fa-stack-1x fa-inverse"></i>
								</span>';
								echo $icon;
							}						
							;?>
						</span>
						<span class="mystem-edd-download-content">
							<span class="downloads-title"><a href="<?php the_permalink( $post->ID ); ?>"><?php the_title(); ?></a></span>
							
							<span class="edd-downloads-meta">			
								<span class="downloads-category"><?php echo get_the_term_list( $post->ID, 'download_category', '', ', ' ); ?></span>
								<span class="downloads-price"><?php echo edd_currency_filter( edd_format_amount( edd_get_download_price( $post->ID ) ) ); ?></span>
								
							</span>
							
						</span>
					</span>
				</li>
				<?php 	} 
				wp_reset_postdata();
			?>
			
		</ul>
		
		<?php // Used by themes. Closes the widget
			echo $args['after_widget'];
		}
		
		/** @see WP_Widget::form */
		public function form( $instance ) {
			// Set up some default widget settings.
			$defaults = array(
			'title'        => __( 'More Author\'s Items', 'mystem-edd' ),			
			'number_posts'  => '5',			
			);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'mystem-edd' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<!-- Number of Posts -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>"><?php esc_html_e( 'Number od Purchases:', 'mystem-edd' ) ?></label>
			
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_posts' ) ); ?>" type="text" value="<?php echo esc_attr($instance['number_posts']); ?>" />
		</p>
		
		
		<?php }
		/** @see WP_Widget::update */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );			
			$instance['number_posts']  = isset( $new_instance['number_posts'] )  ? $new_instance['number_posts']  : '';				
			return $instance;						
		}
		
		}																