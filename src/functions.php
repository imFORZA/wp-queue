<?php
/**
 * WP_Queue Functions.
 *
 * @package WP_Queue
 */

use WP_Queue\Queue;
use WP_Queue\QueueManager;

// require_once( trailingslashit( dirname( __FILE__ ) ) . 'inc/autoloader.php');
if ( ! function_exists( 'wp_queue' ) ) {
	/**
	 * Return Queue instance.
	 *
	 * @param string $connection Connection to initialize.
	 *
	 * @return Queue
	 */
	function wp_queue( $connection = '' ) {
		if ( empty( $connection ) ) {
			$connection = apply_filters( 'wp_queue_default_connection', 'database' );
		}

		return QueueManager::resolve( $connection );
	}
}

if ( ! function_exists( 'wp_queue_options' ) ) {
	/**
	 * WP Queue Options.
	 *
	 * @access public
	 * @return void
	 */
	function wp_queue_options() {

		update_option( 'wp_queue_version', '1.2.1', 'no' );
		update_option( 'wp_queue_db_version', '1.2.1', 'no' );
		update_option( 'wp_queue_api_version', '1.0.0', 'no' );
		update_option( 'wp_queue_debug', 'true', 'yes' );

	}
}

if ( ! function_exists( 'wp_queue_uninstall_options' ) ) {
	/**
	 * WP Queue Uninstall Options.
	 *
	 * @access public
	 * @return void
	 */
	function wp_queue_uninstall_options() {

		delete_option( 'wp_queue_version' );
		delete_option( 'wp_queue_db_version' );
		delete_option( 'wp_queue_api_version' );
		delete_option( 'wp_queue_debug' );

	}
}

if ( ! function_exists( 'wp_queue_install_tables' ) ) {
	/**
	 * Install database tables
	 */
	function wp_queue_install_tables() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$wpdb->hide_errors();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$wpdb->prefix}queue_jobs (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				job longtext NOT NULL,
				category tinytext NOT NULL,
				attempts tinyint(3) NOT NULL DEFAULT 0,
				priority tinyint(4) NOT NULL DEFAULT 0,
				reserved_at datetime DEFAULT NULL,
				available_at datetime NOT NULL,
				created_at datetime NOT NULL,
				PRIMARY KEY  (id)
				) $charset_collate;";

		dbDelta( $sql );

		$sql = "CREATE TABLE {$wpdb->prefix}queue_failures (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				job longtext NOT NULL,
				error text DEFAULT NULL,
				failed_at datetime NOT NULL,
				PRIMARY KEY  (id)
				) $charset_collate;";

		dbDelta( $sql );
	}
}


if ( ! function_exists( 'wp_queue_empty_tables' ) ) {
	/**
	 * Empty database tables.
	 */
	function wp_queue_empty_tables() {

		global $wpdb;

		$table_jobs     = $wpdb->prefix . 'queue_jobs';
		$table_failures = $wpdb->prefix . 'queue_failures';

		$wpdb->query( $wpdb->prepare( 'TRUNCATE TABLE %s', $table_jobs ) );
		$wpdb->query( $wpdb->prepare( 'TRUNCATE TABLE %s', $table_failures ) );

	}
}

if ( ! function_exists( 'wp_queue_uninstall_tables' ) ) {
	/**
	 * Un-Install database tables
	 */
	function wp_queue_uninstall_tables() {

		global $wpdb;

		$table_jobs     = $wpdb->prefix . 'queue_jobs';
		$table_failures = $wpdb->prefix . 'queue_failures';

		$wpdb->query( "DROP TABLE IF EXISTS $table_jobs" );
		$wpdb->query( "DROP TABLE IF EXISTS $table_failures" );

	}
}

if ( ! function_exists( 'wp_queue_count_jobs' ) ) {

	/**
	 * WP Queue Count Jobs.
	 *
	 * @access public
	 * @param string $category (default: '')
	 * @return void
	 */
	function wp_queue_count_jobs( $category = '' ) {

		global $wpdb;

		// TODO:
		// Arguments to get count by category
		// Arguments to get count by attempts
		// Arguments to get count by priority
		// Arguments to get count by reserved_at, available_at, created_at dates or date ranges
		$job_count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'queue_jobs' . '' );

		return $job_count;

	}
}

if ( ! function_exists( 'wp_queue_get_jobs' ) ) {

	/**
	 * WP Queue Count Jobs.
	 *
	 * @access public
	 * @param string $args Arguments
	 * @return void
	 */
	function wp_queue_get_jobs( $args = '' ) {

		global $wpdb;

		// TODO:
		// Arguments to get by category
		// Arguments to get by attempts
		// Arguments to get by priority
		// Arguments to get by reserved_at, available_at, created_at dates or date ranges
		$jobs = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'queue_jobs' . '' );

		return $jobs;

	}
}

if ( ! function_exists( 'wp_queue_get_job_failures' ) ) {

	/**
	 * WP Queue Count Jobs.
	 *
	 * @access public
	 * @param string $args Arguments
	 * @return void
	 */
	function wp_queue_get_job_failures( $args = '' ) {

		global $wpdb;

		// TODO:
		// Arguments to get by category
		// Arguments to get by attempts
		// Arguments to get by priority
		// Arguments to get by reserved_at, available_at, created_at dates or date ranges
		$failures = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'queue_failures' . '' );

		return $failures;

	}
}


if ( ! function_exists( 'wp_queue_debug' ) ) {

	/**
	 * WP Queue Debug Mode
	 *
	 * @access public
	 * @param string $debug_mode (default: 'false') Debug Mode.
	 * @return void
	 */
	function wp_queue_debug( $debug_mode = 'false' ) {

		if ( 'true' === $debug_mode ) {

			update_option( 'wp_queue_debug', 'true', 'yes' );

			add_filter(
				'wp_queue_default_connection', function() {
					return 'sync';
				}
			);

		} else {

			update_option( 'wp_queue_debug', 'false', 'no' );

		}

	}
}
