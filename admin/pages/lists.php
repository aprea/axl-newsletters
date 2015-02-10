<?php

/**
 * Lists admin page.
 *
 * Renders the lists admin page. Includes a list of all lists.
 * Allows you to create new lists and manage existing ones.
 *
 * @link       http://wpaxl.com
 * @since      1.0.0
 *
 * @package    Axl_Newsletters
 * @subpackage Axl_Newsletters/admin/pages
 */

$this->admin_header(); ?>

	<h2 id="axl-newsletters-title">
		<?php _ex( 'Lists', 'Page title.', 'axl-newsletters' ); ?>
		<a href="<?php echo admin_url( 'admin.php?page=axl-newsletters-lists&action=new' ); ?>" class="add-new-h2"><?php _ex( 'Add New', 'Action button, i.e. add new item.', 'axl-newsletters' ); ?></a>
	</h2><?php

	$lists_table = new Axl_Newsletters_Lists_Table();

	if ( $lists_table->has_items() ) {
		$lists_table->display();
	} else {
		echo '<p>Create a list!</p>';
	}

$this->admin_footer();