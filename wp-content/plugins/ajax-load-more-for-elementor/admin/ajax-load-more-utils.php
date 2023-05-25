<?php
namespace PD_ALM;
use PD_ALM\AJAX_LOAD_MORE;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Do all addon related works
 */
final class PD_ALM_UTILS {
	
	public function __construct(){
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	public function init(){
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, PD_ALM_MIN_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, PD_ALM_MIN_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}

		// Enqueue Styles
		add_action( 'admin_enqueue_scripts',  [ $this, 'admin_scripts_styles' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );

		// Enqueue Scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_scripts' ] );

		// Register widget
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

	}

	/**
	 * Admin Notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ){
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html( '"%1$s" requires "%2$s" to be installed and activated.', 'post-slider-for-elementor' ),
			'<strong>' . esc_html( 'Ajax Load More for Elementor', 'post-slider-for-elementor' ) . '</strong>',
			'<strong>' . esc_html( 'Elementor', 'post-slider-for-elementor' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ){
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html( '"%1$s" requires "%2$s" version %3$s or greater.', 'post-slider-for-elementor' ),
			'<strong>' . esc_html( 'Ajax Load More for Elementor', 'post-slider-for-elementor' ) . '</strong>',
			'<strong>' . esc_html( 'Elementor', 'post-slider-for-elementor' ) . '</strong>',
			 PD_ALM_MIN_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ){
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html( '"%1$s" requires "%2$s" version %3$s or greater.', 'post-slider-for-elementor' ),
			'<strong>' . esc_html( 'Ajax Load More for Elementor', 'post-slider-for-elementor' ) . '</strong>',
			'<strong>' . esc_html( 'PHP', 'post-slider-for-elementor' ) . '</strong>',
			 PD_ALM_MIN_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Enqueue Styles
	 * 
	 * Load all required stylesheets
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_styles(){

		//Register FontAwesome for fallback
		wp_register_style(
            'font-awesome-5-all',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/all.min.css',
            false,
            '1.0.0'
        );

        wp_register_style(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/v4-shims.min.css',
            false,
            '1.0.0'
        );

        wp_register_script(
            'font-awesome-4-shim',
            ELEMENTOR_ASSETS_URL . 'lib/font-awesome/js/v4-shims.min.js',
            false,
            '1.0.0'
        );

		wp_enqueue_style( 'pd-alm-style', PD_ALM_URL . '/assets/css/style.css', array(), '1.0.0', 'all' );
	}

	/**
	 * Enqueue Admin Styles and Scripts
	 * 
	 * Load Admin stylesheets and scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_scripts_styles(){

		wp_enqueue_style( 'wb-nt-admin-style', PD_ALM_URL . 'admin/assets/css/admin.css', array(), '1.0.0', 'all' );
		
		wp_enqueue_script( 'wb-nt-admin-script', PD_ALM_URL . 'admin/assets/js/admin.js', array('jquery'), '1.0.0', 'all' );

		wp_localize_script( 'wb-nt-admin-script', 'pd_alm_ajax_object',
            array(
            	'ajax_url' => admin_url( 'admin-ajax.php' ),
            ) 
        );
	}

	/**
	 * Enqueue Scripts
	 * 
	 * Load all required scripts
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function enqueue_scripts(){

		wp_enqueue_script( 'pd-alm-imagesloaded', PD_ALM_URL . 'assets/vendors/imagesloaded/imagesloaded.pkgd.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'pd-alm-packery-library', PD_ALM_URL . 'assets/vendors/packery/packery.pkgd.min.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'pd-alm-main', PD_ALM_URL . 'assets/js/main.js', array( 'pd-alm-packery-library' ), '1.0.0', true );

		wp_localize_script( 'pd-alm-main', 'pd_alm_ajax_object',
            array(
            	'ajax_url' => admin_url( 'admin-ajax.php' ),
            ) 
        );
	}

	/**
	 * Register Widget
	 * 
	 * Register Elementor Before After Image Comparison Slider From Here
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_widgets( $widgets_manager ) {
		$this->includes();
		$this->register_slider_widgets( $widgets_manager );
	}

	/**
	 * Include Files
	 *
	 * Load widgets php files.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function includes() {

		require_once( PD_ALM_PATH . '/widgets/ajax-load-more.php' );

	}

	/**
	 * Register Post Slider Widget
	 *
	 * Register the Post Slider Widget from here
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function register_slider_widgets( $widgets_manager ) {
		$widgets_manager->register_widget_type( new AJAX_LOAD_MORE\PD_ALM_WIDGET() );
	}
}

new PD_ALM_UTILS();