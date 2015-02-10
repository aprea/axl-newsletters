<?php

/**
 * Dashboard admin page.
 *
 * Renders the main parent Axl Newsletters admin page.
 * Includes a summary of your campaigns and subscriptions.
 *
 * @link       http://wpaxl.com
 * @since      1.0.0
 *
 * @package    Axl_Newsletters
 * @subpackage Axl_Newsletters/admin/pages
 */

$this->admin_header();

	printf( '<h2 id="axl-newsletters-title">%s</h2>', _x( 'Dashboard', 'Page title.', 'axl-newsletters' ) );

$this->admin_footer();