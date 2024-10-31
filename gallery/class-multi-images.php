<?php 

class MyStem_Download_Images {


	public static function output( $post ) {
		?>
		<div id="mystem_post_images_container">
			<ul class="mystem_post_images">
				<?php
				if ( metadata_exists( 'post', $post->ID, '_mystem_post_image_gallery' ) ) {
					$mystem_post_image_gallery = get_post_meta( $post->ID, '_mystem_post_image_gallery', true );
					$attachments = array_filter( explode( ',', $mystem_post_image_gallery ) );
				} 				

				$update_meta = false;

				if ( ! empty( $attachments ) ) { 
					foreach ( $attachments as $attachment_id ) {
						$attachment = wp_get_attachment_image( $attachment_id, 'thumbnail' );
						if ( empty( $attachment ) ) {
							$update_meta = true;

							continue;
						}

						echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
						' . $attachment . '
						<ul class="actions">
						<li><a href="#" class="delete tips" data-tip="' . esc_attr__( 'Delete image', 'mystem-edd' ) . '">' . esc_html__( 'Delete', 'mystem-edd' ) . '</a></li>
						</ul>
						</li>';

						$updated_gallery_ids[] = $attachment_id;
					}

					if ( $update_meta ) {
						update_post_meta( $post->ID, '_mystem_post_image_gallery', implode( ',', $updated_gallery_ids ) );
					}
				}
				?>
			</ul>

			<input type="hidden" id="mystem_post_image_gallery" name="mystem_post_image_gallery" value="<?php if(isset($mystem_post_image_gallery)){echo esc_attr( $mystem_post_image_gallery );} ?>" />

		</div>
		<p class="mystem_add_post_images hide-if-no-js">
			<a href="#" data-choose="<?php esc_attr_e( 'Add Images to Download Gallery', 'mystem-edd' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'mystem-edd' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'mystem-edd' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'mystem-edd' ); ?>"><?php _e( 'Add download gallery images', 'mystem-edd' ); ?></a>
		</p>
		<?php
	}

	/**
	 * Save meta box data.
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public static function save( $post_id) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;


    // if our current user can't edit this post, bail
		$attachment_ids = isset( $_POST['mystem_post_image_gallery'] ) ? array_filter( explode( ',', self::clean_id( $_POST['mystem_post_image_gallery'] ) ) ) : array();
		if(count($attachment_ids)>0){
		update_post_meta( $post_id, '_mystem_post_image_gallery', implode( ',', $attachment_ids ) );
	}
	}

	public static function clean_id( $var ) {
		return is_array( $var ) ? array_map( 'wc_clean', $var ) : sanitize_text_field( $var );
	}
}
