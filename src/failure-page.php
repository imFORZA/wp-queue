<?php

/**
 * Rest Routes initialization class.
 */
class WP_Queue_REST {
	/**
	 * Create the rest API routes.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// WP Queue Basic Stats Endpoint.
		add_action(
			'rest_api_init', function() {
				register_rest_route(
					'queue/v1', 'stats', array(
						'methods'  => array( 'get' ),
						'callback' => array( $this, 'get_stats' ),
					// 'permission_callback' => array( $this, 'permission_check' ),
					)
				);
			}
		);

		// Get All Jobs.
		add_action(
			'rest_api_init', function() {
				register_rest_route(
					'queue/v1', 'jobs', array(
						'methods'  => array( 'get' ),
						'callback' => array( $this, 'get_jobs' ),
					// 'permission_callback' => array( $this, 'permission_check' ),
					)
				);
			}
		);

		// Get Job Count
		add_action(
			'rest_api_init', function() {
				register_rest_route(
					'queue/v1', 'jobs/count', array(
						'methods'  => array( 'get' ),
						'callback' => array( $this, 'count_jobs' ),
					// 'permission_callback' => array( $this, 'permission_check' ),
					)
				);
			}
		);

	}

	/**
	 * Get Basic Stats.
	 *
	 * @access public
	 * @return void
	 */
	public function get_stats() {

		$response = array(
			'db_version'  => get_option( 'wp_queue_db_version' ),
			'version'     => get_option( 'wp_queue_version' ),
			'api_version' => get_option( 'wp_queue_api_version' ),
		);

		return rest_ensure_response( $response );

	}

	/**
	 * get_queue function.
	 *
	 * @access public
	 * @return void
	 */
	public function get_jobs() {

		global $wpdb;

		$table_jobs = $wpdb->prefix . 'queue_jobs';

		$jobs = $wpdb->get_results( "SELECT * FROM $table_jobs" );

		return rest_ensure_response( $jobs );

	}

	/**
	 * Count Jobs
	 *
	 * @access public
	 * @return void
	 */
	public function count_jobs() {

		$response = array(
			'total_jobs' => wp_queue_count_jobs(),
		);

		return rest_ensure_response( $response );

	}

	/**
	 * Check whether the function is allowed to be run.
	 *
	 * Must have either capabilities to enact action, or a valid nonce.
	 *
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function permission_check( $data ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'forbidden', 'You are not allowed to do that.', array( 'status' => 403 ) );
		}
		return true;
	}

}
new WP_Queue_REST();
