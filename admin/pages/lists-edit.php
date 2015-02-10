<?php

/**
 * Lists edit admin page.
 *
 * Allows you to edit a list.
 *
 * @link       http://wpaxl.com
 * @since      1.0.0
 *
 * @package    Axl_Newsletters
 * @subpackage Axl_Newsletters/admin/pages
 */

$this->admin_header(); ?>

	<h2 id="axl-newsletters-title">
		<?php _ex( 'Edit List', 'Page title.', 'axl-newsletters' ); ?>
		<a href="<?php echo $this->admin_url( 'lists&action=new' ); ?>" class="add-new-h2"><?php _ex( 'Add New', 'Action button, i.e. add new item', 'axl-newsletters' ); ?></a>
	</h2><?php

$this->admin_footer();