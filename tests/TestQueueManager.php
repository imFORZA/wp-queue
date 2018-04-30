<?php

use PHPUnit\Framework\TestCase;
use WP_Queue\Exceptions\ConnectionNotFoundException;
use WP_Queue\Queue;
use WP_Queue\QueueManager;

/**
 * TestQueueManager class.
 *
 * @extends TestCase
 */
class TestQueueManager extends TestCase {

	/**
	 * setUp function.
	 *
	 * @access public
	 * @return void
	 */
	public function setUp() {
		WP_Mock::setUp();

		global $wpdb;
		$wpdb         = Mockery::mock( 'WPDB' );
		$wpdb->prefix = 'wp_';
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
	 * test_resolve function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_resolve() {
		$queue = QueueManager::resolve( 'database' );
		$this->assertInstanceOf( Queue::class, $queue );
		$queue = QueueManager::resolve( 'database' );
		$this->assertInstanceOf( Queue::class, $queue );
	}

	/**
	 * test_resolve_exception function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_resolve_exception() {
		$this->expectException( ConnectionNotFoundException::class );
		QueueManager::resolve( 'wibble' );
	}
}
