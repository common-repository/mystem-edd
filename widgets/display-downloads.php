<?php
	
	/**
		* EDD Author Items Widget 
		*
	*/
	
	
	class MyStem_Edd_Downloads_Widget extends WP_Widget {
		/** Constructor */
		public function __construct() {
			parent::__construct(
			'mystem_edd_downloads_widget',
			__( 'MyStem Display Downloads', 'mystem-edd' ),
			array(
			'description' =>__( 'Display latest, popular and featured downloads', 'mystem-edd' ),
			)
			);
			
			
		}
		
		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {
			
			$args['id'] = ( isset( $args['id'] ) ) ? $args['id'] : 'mystem_edd_downloads_widget';	
			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Downloads', 'mystem-edd' );
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $args['id'] );
			$downloads  = $instance['downloads'] ? $instance['downloads'] : 'latest';
			
			$number_posts = $instance['number_posts'] ? $instance['number_posts'] : '5';			
			$authorID = get_the_author_meta( 'ID' );			
			
			
			global $post;
			$query = array(
				'numberposts'      => $number_posts,
				'post_status'      => 'publish',			
				'post_type'        => 'download',						
				'suppress_filters' => true, 
			);				
			
			
			
			switch ( $downloads ) { 
				case 'featured':
				$query['post_type'] = 'download';
				$query['meta_key']  = '_mystem_download_feature_item';
				$query['orderby']   = 'rand';
				$query['order']     = 'ASC';
				break;
				
				case 'popular':			
					$query['post_type'] = 'download';
					$query['meta_key']  = '_edd_download_sales';
					$query['orderby']   = 'meta_value_num';
					$query['order']     = 'DESC';
				break;				
				
				case 'latest':			
				  $query['post_type'] = 'download';
					$query['orderby']   = 'date';
					$query['order']     = 'DESC';
				break;	
				
				case 'purchases':										
					$query['post_type'] = 'edd_payment';
				break;
				
				
			}		
			$posts = get_posts( $query );
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
		?>
		<ul>
			<?php 
				foreach ($posts as $post) { 
					setup_postdata($post);	
					if( $downloads == 'purchases' ) {
						$meta = get_post_meta($post->ID, '_edd_payment_meta' );
						$cart = $meta[0]['cart_details'];
						$download_id = $cart[0]['id'];
						$thumbnail = get_the_post_thumbnail( $download_id, array(75,75) );
						$url = get_permalink( $download_id );
						$title = get_the_title( $download_id );
						$category = get_the_term_list( $download_id, 'download_category', '', ', ' );
						$price = edd_currency_filter( edd_format_amount( edd_get_download_price( $download_id ) ) );
					}
					else {
						$thumbnail = get_the_post_thumbnail( $post->ID, array(75,75) );
						$url = get_permalink( $post->ID );
						$title = get_the_title( $post->ID );
						$category = get_the_term_list( $post->ID, 'download_category', '', ', ' );
						$price = edd_currency_filter( edd_format_amount( edd_get_download_price( $post->ID ) ) );						
					}
					
					
				?>
				<li>
					<span class="mystem-edd-download">
						<span class="widget-img">
						<a href="<?php echo esc_url( $url ); ?>">
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
							</a>
						</span>
						<span class="mystem-edd-download-content">
							<span class="downloads-title"><a href="<?php echo esc_url( $url );?>"><?php echo $title; ?></a></span>
							
							<span class="edd-downloads-meta">			
								<span class="downloads-category"><?php echo $category; ?></span>
								<span class="downloads-price"><?php echo $price; ?></span>
								
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
			'title'        => __( 'Downloads', 'mystem-edd' ),			
			'number_posts'  => '5',		
			'downloads' => 'latest',
			);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'mystem-edd' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		<!-- Number of Posts -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>"><?php esc_html_e( 'Number of Downloads:', 'mystem-edd' ) ?></label>
			
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_posts' ) ); ?>" type="text" value="<?php echo esc_attr($instance['number_posts']); ?>" />
		</p>
		
		<!-- Show Downloads   -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'downloads' ) ); ?>"><?php esc_html_e( 'Display Downloads', 'mystem-edd' ) ?></label>
			<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'downloads' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'downloads' ) ); ?>">
				<option <?php selected( $instance['downloads'], 'latest' ); ?> value="latest">Latest add</option>
				<option <?php selected( $instance['downloads'], 'popular' ); ?> value="popular">Popular</option>
				<option <?php selected( $instance['downloads'], 'featured' ); ?> value="featured">Featured</option>
				<option <?php selected( $instance['downloads'], 'purchases' ); ?> value="purchases">Recent Purchases</option>
			</select>
		</p>
		
		
		<?php }
		/** @see WP_Widget::update */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );			
			$instance['number_posts']  = isset( $new_instance['number_posts'] )  ? $new_instance['number_posts']  : '';	
			$instance['downloads']  = isset( $new_instance['downloads'] )  ? $new_instance['downloads']  : '';	
			return $instance;						
		}
		
		}																