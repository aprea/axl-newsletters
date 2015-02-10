<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link        http://wpaxl.com
 * @since       1.0.0
 *
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/public
 * @author      Chris Aprea <chris@wpaxl.com>
 */
final class Axl_Newsletters_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @var    string  $axl_newsletters  The ID of this plugin.
	 */
	private $axl_newsletters;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 * @var    string  $axl_newsletters  The name of the plugin.
	 */
	public function __construct( $axl_newsletters ) {
		$this->axl_newsletters = $axl_newsletters;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since  1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Axl_Newsletters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Axl_Newsletters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->axl_newsletters, plugin_dir_url( __FILE__ ) . 'css/axl-newsletters-public.css', array(), AXL_NEWSLETTERS_VERSION, 'all' );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since  1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Axl_Newsletters_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Axl_Newsletters_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->axl_newsletters, plugin_dir_url( __FILE__ ) . 'js/axl-newsletters-public.js', array( 'jquery' ), AXL_NEWSLETTERS_VERSION, false );
	}
}