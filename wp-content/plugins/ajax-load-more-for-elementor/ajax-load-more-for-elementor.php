<?php
/**
 Plugin Name: Ajax Load More for Elementor
 Description: Ajax Load More for Elementor lets you display your Posts with an Ajax powered Load More Button
 Author: Plugin Devs
 Author URI: https://plugin-devs.com/
 Version: 1.0.0
 License: GPLv2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html
 Text Domain: pd-alm
*/

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) { exit; }

 /**
  * Main class for News Ticker
  */
class PD_ALM_SLIDER
 {
 	
 	private static $instance;

	public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PD_ALM_SLIDER();
            self::$instance->init();
        }
        return self::$instance;
    }

    //Empty Construct
 	function __construct(){}
 	
 	//initialize Plugin
 	public function init(){
 		$this->defined_constants();
 		$this->include_files();
		add_action( 'elementor/init', array( $this, 'pd_alm_create_category') ); // Add a custom category for panel widgets
 	}

 	//Defined all constants for the plugin
 	public function defined_constants(){
 		define( 'PD_ALM_PATH', plugin_dir_path( __FILE__ ) );
		define( 'PD_ALM_URL', plugin_dir_url( __FILE__ ) ) ;
		define( 'PD_ALM_VERSION', '1.0.0' ) ; //Plugin Version
		define( 'PD_ALM_MIN_ELEMENTOR_VERSION', '2.0.0' ) ; //MINIMUM ELEMENTOR Plugin Version
		define( 'PD_ALM_MIN_PHP_VERSION', '5.4' ) ; //MINIMUM PHP Plugin Version
		define( 'PD_ALM_PRO_LINK', 'https://plugin-devs.com/product/elementor-ajax-load-more/' ) ; //Pro Link
 	}

 	//Include all files
 	public function include_files(){

 		require_once( PD_ALM_PATH . 'functions.php' );
 		require_once( PD_ALM_PATH . 'admin/ajax-load-more-utils.php' );
 		if( is_admin() ){
 			require_once( PD_ALM_PATH . 'admin/admin-pages.php' );	
 			require_once( PD_ALM_PATH . 'class-plugin-deactivate-feedback.php' );	
 			require_once( PD_ALM_PATH . 'class-plugin-review.php' );	
 			require_once( PD_ALM_PATH . 'support-page/class-support-page.php' );	
 		}
 		require_once( PD_ALM_PATH . 'class-ajax.php' );
 	}

 	//Elementor new category register method
 	public function pd_alm_create_category() {
	   \Elementor\Plugin::$instance->elements_manager->add_category( 
		   	'plugin-devs-element',
		   	[
		   		'title' => esc_html( 'Plugin Devs Element', 'news-ticker-for-elementor' ),
		   		'icon' => 'fa fa-plug', //default icon
		   	],
		   	2 // position
	   );
	}

 }

function pd_alm_register_function(){
	if( is_admin() ){
		$pd_alm_feedback = new PD_ALM_Usage_Feedback(
			__FILE__,
			'webbuilders03@gmail.com',
			false,
			true
		);
	}
}
add_action('plugins_loaded', 'pd_alm_register_function');
$pd_alm = PD_ALM_SLIDER::getInstance();


add_action('wp_footer', 'pd_alm_display_custom_css');
function pd_alm_display_custom_css(){
	$custom_css = get_option( 'pd_alm_custom_css' );
	$css ='';
	if ( ! empty( $custom_css ) ) {
		$css .= '<style type="text/css">';
		$css .= '/* Custom CSS */' . "\n";
		$css .= $custom_css . "\n";
		$css .= '</style>';
	}
	echo $css;
}

/**
 * Submenu filter function. Tested with Wordpress 4.1.1
 * Sort and order submenu positions to match your custom order.
 *
 */
function pd_alm_order_submenu( $menu_ord ) {

  global $submenu;

  // Enable the next line to see a specific menu and it's order positions
  //echo '<pre>'; print_r( $submenu['pd-ajax-load-more'] ); echo '</pre>'; exit();

  $arr = array();

  $arr[] = $submenu['pd-ajax-load-more'][1];
  $arr[] = $submenu['pd-ajax-load-more'][2];
  $arr[] = $submenu['pd-ajax-load-more'][5];
  $arr[] = $submenu['pd-ajax-load-more'][4];

  $submenu['pd-ajax-load-more'] = $arr;

  return $menu_ord;

}

// add the filter to wordpress
add_filter( 'custom_menu_order', 'pd_alm_order_submenu' );

/**
 * Setup Plugin Activation Time
 *
 * @since 1.0.1
 *
 */
register_activation_hook(__FILE__,  'pdalm_setup_plugin_activation_time' );
add_action('upgrader_process_complete', 'pdalm_setup_plugin_activation_time');
add_action('init', 'pdalm_setup_plugin_activation_time');
function pdalm_setup_plugin_activation_time(){
	$installation_time = get_option('pdalm_installed_time');
	if( !$installation_time ){
		update_option('pdalm_installed_time', current_time('timestamp'));
	}
}