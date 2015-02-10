<?php

/**
 * Defines installation functionality
 *
 * Defines several methods which are responsible for
 * installing Axl Newsletters. Tasks include:
 *  - Installing and upgrading the database schema
 *  - etc
 *
 * @link        http://wpaxl.com
 * @since       1.0.0
 *
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/includes
 */

/**
 * Defines installation functionality.
 *
 * Defines several methods which are responsible for
 * installing Axl Newsletters. Tasks include:
 *  - Installing and upgrading the database schema
 *  - etc
 *
 * @since       1.0.0
 * @package     Axl_Newsletters
 * @subpackage  Axl_Newsletters/includes
 * @author      Chris Aprea <chris@wpaxl.com>
 */
final class Axl_Newsletters_Install {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since  1.0.0
	 * @var    init   $schema_version  The database schema version number.
	 */
	static protected $schema_version = 1;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since   1.0.0
	 * @return  string   The SQL needed to create the required tables.
	 */
	private function get_schema() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql =
	   "CREATE TABLE {$wpdb->prefix}axn_campaigns (
		  campaign_id             bigint(20)    unsigned NOT NULL auto_increment,
		  author                  bigint(20)    unsigned NOT NULL default '0',
		  content                 longtext               NOT NULL,
		  title                   text                   NOT NULL,
		  status                  varchar(20)            NOT NULL default 'publish',
		  name                    varchar(200)           NOT NULL default '',
		  created                 datetime               NOT NULL default '0000-00-00 00:00:00',
		  modified                datetime               NOT NULL default '0000-00-00 00:00:00',
		  type                    varchar(20)            NOT NULL default 'campaign',
		  PRIMARY KEY  (campaign_id),
		  KEY name (name),
		  KEY type_status_date (type,status,created,campaign_id),
		  KEY author (author)
		) $charset_collate;

		CREATE TABLE {$wpdb->prefix}axn_campaign_meta (
		  meta_id                 bigint(20)    unsigned NOT NULL auto_increment,
		  campaign_id             bigint(20)    unsigned NOT NULL default '0',
		  meta_key                varchar(255)                    default NULL,
		  meta_value              longtext,
		  PRIMARY KEY  (meta_id),
		  KEY camp_id (campaign_id),
		  KEY meta_key (meta_key)
		) $charset_collate;

		CREATE TABLE {$wpdb->prefix}axn_subscribers (
		  subscriber_id           bigint(20)    unsigned NOT NULL auto_increment,
		  fname                   varchar(100)           NOT NULL default '',
		  lname                   varchar(100)           NOT NULL default '',
		  email                   varchar(100)           NOT NULL default '',
		  created                 datetime               NOT NULL default '0000-00-00 00:00:00',
		  modified                datetime               NOT NULL default '0000-00-00 00:00:00',
		  status                  varchar(20)            NOT NULL default 'active',
		  PRIMARY KEY  (subscriber_id),
		  UNIQUE KEY email (email)
		) $charset_collate;

		CREATE TABLE {$wpdb->prefix}axn_subscriber_meta (
		  subscriber_meta_id     bigint(20)     unsigned NOT NULL auto_increment,
		  subscriber_id          bigint(20)     unsigned NOT NULL default '0',
		  meta_key               varchar(255)   default  NULL,
		  meta_value             longtext,
		  PRIMARY KEY  (subscriber_meta_id),
		  KEY subscriber_id (subscriber_id),
		  KEY meta_key (meta_key)
		) $charset_collate;

		CREATE TABLE {$wpdb->prefix}axn_subscriptions (
		  subscription_id         bigint(20)    unsigned NOT NULL auto_increment,
		  list_id                 bigint(20)    unsigned NOT NULL default '0',
		  signup_ip_address       varchar(45)            NOT NULL,
		  signup_source           varchar(100)           NOT NULL,
		  status                  varchar(20)            NOT NULL default 'active',
		  double_opt_in           boolean                NOT NULL default '0',
		  created                 datetime               NOT NULL default '0000-00-00 00:00:00',
		  modified                datetime               NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY  (subscription_id),
		  KEY list_id (list_id)
		) $charset_collate;

		CREATE TABLE {$wpdb->prefix}axn_lists (
		  list_id                 bigint(20)    unsigned NOT NULL auto_increment,
		  name                   text                    NOT NULL,
		  from_email              varchar(100)           NOT NULL default '',
		  from_name               varchar(100)           NOT NULL default '',
		  join_reminder           text                   NOT NULL,
		  created                 datetime               NOT NULL default '0000-00-00 00:00:00',
		  modified                datetime               NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY  (list_id)
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Creates and updates the database tables used by this plugin.
	 *
	 * Occurs both at the time of installation to create the initial tables
	 * and whenever the schema is updated.
	 *
	 * @since   1.0.0
	 * @return  string   Empty string if success, error message if failure.
	 */
	private function update_tables() {
		global $wpdb;

		$wpdb->show_errors = true;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		ob_start();

		dbDelta( $this->get_schema() );

		$result = ob_get_clean();

		// Set the db schema version to make future updates possible.
		update_option( 'axl_newsletters_schema_version', self::$schema_version );

		do_action( 'axl_newsletters_tables_updated', $result );

		return $result;
	}

	/**
	 * Forces the tables to be updated when a specific query string is present.
	 *
	 * @since  1.0.0
	 */
	public function force_update_db() {

		if ( ! isset( $_GET['axl-newsletters-force-update-db'] ) ) {
			return;
		}

		//TODO: error handling, record / display error if present
		$result = $this->update_tables();

		wp_safe_redirect( admin_url( 'admin.php?page=axl-newsletters-dashboard' ) );

		exit;
	}

	/**
	 * Creates the Axl Newsletters tables if required.
	 *
	 * @since  1.0.0
	 */
	public function maybe_install() {
		global $wpdb;

		$schema_version = get_option( 'axl_newsletters_schema_version' );

		if ( ! empty( $schema_version ) && $schema_version == self::$schema_version ) {
			return;
		}

		//TODO: error handling, record / display error if present
		$result = $this->update_tables();
	}
}