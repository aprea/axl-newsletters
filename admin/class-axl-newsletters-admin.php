<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link        http://wpaxl.com
 * @since       1.0.0
 *
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/admin
 * @author      Chris Aprea <chris@wpaxl.com>
 */
final class Axl_Newsletters_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @var    string   $axl_newsletters  The ID of this plugin.
	 */
	private $axl_newsletters;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @var    string  $version  The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 * @var    string  $axl_newsletters  The name of this plugin.
	 */
	public function __construct( $axl_newsletters ) {
		$this->axl_newsletters = $axl_newsletters;
	}

	/**
	 * Retrieve the url to a specific Axl Newsletters admin page.
	 * If $page is omitted the Axl Newsletters dashboard url is returned.
	 *
	 * @since   1.0.0
	 * @param   string  $page  Optional. Desired Axl Newsletters admin page.
	 * @return  string         Admin url of the requested page.
	 */
	private function admin_url( $page = '' ) {

		if ( empty( $page ) ) {
			$url = admin_url( 'admin.php?page=axl-newsletters-dashboard' );
		} else {
			$url = admin_url( 'admin.php?page=axl-newsletters-' . $page );
		}

		return $url;
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since  1.0.0
	 */
	public function enqueue_styles() {

		// Don't enqueue styles on non Axl Newsletters specific pages.
		if ( ! isset( $_GET['page'] ) || false === strpos( $_GET['page'], 'axl-newsletters' ) ) {
			return;
		}

		wp_enqueue_style( $this->axl_newsletters, plugin_dir_url( __FILE__ ) . 'css/axl-newsletters-admin' . AXL_NEWSLETTERS_CSSJS_SUFFIX .  '.css', array(), AXL_NEWSLETTERS_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since  1.0.0
	 */
	public function enqueue_scripts() {

		// Don't enqueue strips on non Axl Newsletters specific pages.
		if ( ! isset( $_GET['page'] ) || false === strpos( $_GET['page'], 'axl-newsletters' ) ) {
			return;
		}

		wp_enqueue_script( $this->axl_newsletters, plugin_dir_url( __FILE__ ) . 'js/axl-newsletters-admin' . AXL_NEWSLETTERS_CSSJS_SUFFIX . '.js', array( 'jquery' ), AXL_NEWSLETTERS_VERSION, false );
	}

	/**
	 * Registers the top level admin menu item and all child menu items.
	 *
	 * @since  1.0.0
	 */
	public function add_menu_pages() {

		// Allow changing the capability users need to view the settings pages.
		$manage_options_cap = apply_filters( 'axl_newsletters_manage_options_capability', 'manage_options' );

		// Define main menu item.
		$main_menu = array(
			'page_title' => __( 'Axl Newsletters: Dashboard', 'axl-newsletters' ),
			'menu_title' => __( 'Axl Newsletters', 'axl-newsletters' ),
			'capability' => $manage_options_cap,
			'position'   => null,
		);

		// Allow *some* manipulation of the main menu args.
		$main_menu = apply_filters( 'axl_newsletters_main_menu', $main_menu );

		// The following args are too dangerous to expose to developers.
		$main_menu['menu_slug'] = 'axl-newsletters-dashboard';
		$main_menu['callback']  = array( $this, 'load_page' );
		$main_menu['icon']      = 'dashicons-email-alt';

		// Register the main menu.
		add_menu_page(
			$main_menu['page_title'],
			$main_menu['menu_title'],
			$main_menu['capability'],
			$main_menu['menu_slug'],
			$main_menu['callback'],
			$main_menu['icon'],
			$main_menu['position']
		);

		// Define sub-menu items.
		$sub_menus = array(
			'campaigns' => array(
				'page_title'  => __( 'Axl Newsletters: Campaigns', 'axl-newsletters' ),
				'menu_title'  => __( 'Campaigns', 'axl-newsletters' ),
				'capability'  => $manage_options_cap,
			),
			'lists' => array(
				'page_title'  => __( 'Axl Newsletters: Lists', 'axl-newsletters' ),
				'menu_title'  => __( 'Lists', 'axl-newsletters' ),
				'capability'  => $manage_options_cap,
			),
			'settings' => array(
				'page_title'  => __( 'Axl Newsletters: Settings', 'axl-newsletters' ),
				'menu_title'  => __( 'Settings', 'axl-newsletters' ),
				'capability'  => $manage_options_cap,
			),
		);

		// Allow *some* manipulation of the sub-menu args.
		$sub_menus = apply_filters( 'axl_newsletters_sub_menus', $sub_menus );

		// Add the sub-menu slugs afterwards. Too dangerous to expose in the filter.
		foreach ( $sub_menus as $slug => &$args ) {
			$args['menu_slug'] = 'axl-newsletters-' . $slug;
		}

		// Loop through and register each sub-menus.
		if ( count( $sub_menus ) ) {
			foreach ( $sub_menus as $slug => $sub_menu ) {
				// The following args are too dangerous to expose to developers via filters.
				$sub_menu['parent_slug'] = 'axl-newsletters-dashboard';
				$sub_menu['callback']    = array( $this, 'load_page' );

				// Register the sub-menu.
				add_submenu_page(
					$sub_menu['parent_slug'],
					$sub_menu['page_title'],
					$sub_menu['menu_title'],
					$sub_menu['capability'],
					$sub_menu['menu_slug'],
					$sub_menu['callback']
				);
			}
		}

		global $submenu;

		// Change the label on the first item for added clarity.
		if ( isset( $submenu['axl-newsletters-dashboard'] ) && current_user_can( $manage_options_cap ) ) {
			$submenu['axl-newsletters-dashboard'][0][0] = __( 'Dashboard', 'axl-newsletters' );
		}
	}

	/**
	 * Loads the specified Axl Newsletters admin page.
	 *
	 * @since  1.0.0
	 */
	public function load_page() {

		if ( ! isset( $_GET['page'] ) ) {
			return;
		}

		switch ( $_GET['page'] ) {
			case 'axl-newsletters-campaigns' :
				require_once( AXL_NEWSLETTERS_PATH . 'admin/pages/campaigns.php' );
			break;

			case 'axl-newsletters-lists' :
				if ( isset( $_GET['action'] ) ) {
					switch ( $_GET['action'] ) {
						case 'new' :
							require_once( AXL_NEWSLETTERS_PATH . 'admin/pages/lists-new.php' );
							return;
						break;

						case 'edit' :
							require_once( AXL_NEWSLETTERS_PATH . 'admin/pages/lists-edit.php' );
							return;
						break;
					}
				}

				require_once( AXL_NEWSLETTERS_PATH . 'admin/tables/class-axl-newsletters-lists-table.php' );
				require_once( AXL_NEWSLETTERS_PATH . 'admin/pages/lists.php' );

			break;

			case 'axl-newsletters-settings' :
				require_once( AXL_NEWSLETTERS_PATH . 'admin/pages/settings.php' );
			break;

			case 'axl-newsletters-dashboard':
			default:
				require_once( AXL_NEWSLETTERS_PATH . 'admin/pages/dashboard.php' );
			break;
		}
	}

	/**
	 * Generates the header for admin pages.
	 *
	 * @since  1.0.0
	 */
	public function admin_header() {

		$page = esc_attr( $_GET['page'] ); ?>

		<div class="wrap axl-newsletters-admin-page page-<?php echo $page; ?>"><?php
	}

	/**
	 * Generates the footer for admin pages.
	 *
	 * @since  1.0.0
	 */
	public function admin_footer() { ?>

		</div><!-- .axl-newsletters-admin-page --><?php
	}
}