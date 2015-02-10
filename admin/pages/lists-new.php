<?php

/**
 * Lists add admin page.
 *
 * Allows you to add a new list.
 *
 * @link       http://wpaxl.com
 * @since      1.0.0
 *
 * @package    Axl_Newsletters
 * @subpackage Axl_Newsletters/admin/pages
 */

$this->admin_header(); ?>

	<h2 id="axl-newsletters-title"><?php _ex( 'Add New List', 'Page title.', 'axl-newsletters' ); ?></h2>

	<form method="post" action="<?php echo $this->admin_url( 'lists&action=new' ); ?>" novalidate="novalidate">

		<?php settings_fields( 'axl-newsletters-lists-new' ); ?>

		<label for="name">List Name</label>

	</form>

<?php
$this->admin_footer();