<?php

/**
 * Settings admin page.
 *
 * Renders the settings admin page.
 * Allows you to change various different settings.
 *
 * @link       http://wpaxl.com
 * @since      1.0.0
 *
 * @package    Axl_Newsletters
 * @subpackage Axl_Newsletters/admin/pages
 */

$this->admin_header();

	printf( '<h2 id="axl-newsletters-title">%s</h2>', _x( 'Settings', 'Page title.', 'axl-newsletters' ) );

$this->admin_footer();