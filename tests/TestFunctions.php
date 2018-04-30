<?php

use PHPUnit\Framework\TestCase;
use WP_Queue\Queue;

/**
 * TestFunctions class.
 *
 * @extends TestCase
 */
class TestFunctions extends TestCase {

	/**
	 * setUp function.
	 *
	 * @access public
	 * @return void
	 */
	public function setUp() {
		WP_Mock::setUp();

		global $wpdb;
		$wpdb = Mockery::mock( 'WPDB' );;
		$wpdb->prefix = "wp_";
	}

	/**
	 * tearDown function.
	 *
	 * @access public
	 * @return void
	 */
	public function tearDown() {
		WP_Mock::tearDown();
	}

	/**
	 * test_wp_queue function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_wp_queue() {
		$this->assertInstanceOf( Queue::class, wp_queue() );
	}
}
