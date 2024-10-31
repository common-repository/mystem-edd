<?php
	/**
		* Plugin Name:       MyStem EDD
		* Plugin URI:        https://wordpress.org/plugins/mystem-extra/
		* Description:       Add extra features to the WordPress theme MyStem.
		* Version:           1.1
		* Author:            lobov
		* Author URI:        https://mystemplus.com/
		* License:           GPL-2.0+
		* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
		* Text Domain:       mystem-extra
	*/
	if ( ! defined( 'WPINC' ) ) {die;}
	
	if( !class_exists( 'MyStem_EDD' ) ) {
	
		final class MyStem_EDD {
			
			private static $instance;
			
			public static function instance() {
				if ( ! isset( self::$instance ) && ! ( self::$instance instanceof MyStem_EDD ) ) {
					self::$instance = new MyStem_EDD;	
				
					self::$instance->includes();					
					if ( class_exists( 'Easy_Digital_Downloads' ) ) {
						self::$instance->edd = new MyStem_EDD_Integration();						
					}
			
				}
				return self::$instance;
			}
			
			public function __clone() {
				// Cloning instances of the class is forbidden.
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mystem-edd' ), '1.0' );
			}
			
			public function __wakeup() {
				// Unserializing instances of the class is forbidden.
				_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mystem-edd' ), '1.0' );
			}
			
			private function includes() {	
			
				require_once plugin_dir_path( __FILE__ ) . 'class-edd.php';
			
			}
		
		
		}
	}
	
	
	function mystem_edd() {
		return MyStem_EDD::instance();
	}	
	
	if ( 	'mystem' != get_option( 'template' ) ) {
		if ( ! function_exists( 'mystem_theme_activated' ) ) {
			function mystem_theme_activated() {				
				$message = __( 'This plugin "MyStem EDD" works only with WordPress theme "MyStem". Please, install and activate the theme "MyStem" first (https://wordpress.org/themes/mystem/)', 'mystem-edd'); 
				echo '<div class="notice notice-error"> <p>'. $message .'</p></div>';
			}
			add_action( 'admin_notices', 'mystem_theme_activated' );				
		}
	}
	else {
		mystem_edd();		
	}
	
	
