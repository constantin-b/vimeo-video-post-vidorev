<?php
/*
* Plugin Name: Vimeotheque PRO 2 - Theme VidoRev compatibility
* Plugin URI: https://vimeotheque.com
* Description: Add-on plugin for Vimeotheque PRO - Vimeo videos WordPress importer which introduces compatibility with theme VidoRev
* Author: CodeFlavors
* Version: 1.1
* Author URI: https://vimeotheque.com
*/

class CVM_Vidorev_Compatibility{
	/**
	 * Holds compatible theme name
	 */
	const THEME = 'VidoRev';
	/**
	 * Holds class instance
	 * @var CVM_Vidorev_Compatibility|null
	 */
	private static $instance = null;

	/**
	 * CVM_Vidorev_Compatibility constructor.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'on_init' ) );
	}

	/**
	 * @return CVM_Vidorev_Compatibility|null
	 */
	public static function get_instance(){
		if( null === self::$instance ){
			self::$instance = new CVM_Vidorev_Compatibility();
		}
		return self::$instance;
	}

	/**
	 * Hook "init" callback, verifies that plugin is loaded and
	 * that loaded theme is the right theme
	 */
	public function on_init(){
		if( !did_action( 'vimeotheque_pro_loaded' ) ){
			return;
		}
		$theme = $this->get_theme();
		if( !$theme ||  self::THEME != $theme->get('Name') ){
			return;
		}

		require_once plugin_dir_path( __FILE__ ) . '/includes/class.cvm-vidorev-compatibility.php';
		new CVM_Vidorev_Actions_Compatibility( self::THEME );
	}

	/**
	 * Get currently installed parent theme
	 * @return bool|false|WP_Theme
	 */
	private function get_theme(){
		// get template details
		$theme = wp_get_theme();
		if( is_a( $theme, 'WP_Theme' ) ){
			// check if it's child theme
			if( is_a( $theme->parent(), 'WP_Theme' ) ){
				// set theme to parent
				$theme = $theme->parent();
			}
		}else{
			$theme = false;
		}
		return $theme;
	}
}
CVM_Vidorev_Compatibility::get_instance();
