<?php

/**
 * Campaign admin page.
 *
 * Renders the campaign admin page, includes a list all campaigns.
 * Allows you to create new campaigns and manage existing ones.
 *
 * @link       http://wpaxl.com
 * @since      1.0.0
 *
 * @package    Axl_Newsletters
 * @subpackage Axl_Newsletters/admin/pages
 */

$this->admin_header();

	printf( '<h2 id="axl-newsletters-title">%s</h2>', _x( 'Campaigns', 'Page title.', 'axl-newsletters' ) );

$this->admin_footer();