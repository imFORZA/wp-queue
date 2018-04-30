<?php

use PHPUnit\Framework\TestCase;
use WP_Queue\Connections\ConnectionInterface;
use WP_Queue\Cron;
use WP_Queue\Job;
use WP_Queue\Queue;
use WP_Queue\Worker;

/**
 * TestQueue class.
 *
 * @extends TestCase
 */
class TestQueue extends TestCase {

	/**
	 * setUp function.
	 *
	 * @access public
	 * @return void
	 */
	public function setUp() {
		WP_Mock::setUp();
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
	 * test_push_success function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_push_success() {
		$insert_id  = 12345;
		$connection = Mockery::mock( ConnectionInterface::class );
		$connection->shouldReceive( 'push' )->once()->andReturn( $insert_id );

		$queue = new Queue( $connection );

		$this->assertEquals( $insert_id, $queue->push( new TestJob() ) );
	}

	/**
	 * test_push_fail function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_push_fail() {
		$connection = Mockery::mock( ConnectionInterface::class );
		$connection->shouldReceive( 'push' )->once()->andReturn( false );

		$queue = new Queue( $connection );

		$this->assertFalse( $queue->push( new TestJob() ) );
	}

	/**
	 * test_cron function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_cron() {
		$connection = Mockery::mock( ConnectionInterface::class );
		$queue      = new Queue( $connection );

		WP_Mock::userFunction( 'wp_next_scheduled', array(
			'return' => time(),
		) );

		$this->assertInstanceOf( Cron::class, $queue->cron() );
	}

	/**
	 * test_worker function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_worker() {
		$connection = Mockery::mock( ConnectionInterface::class );
		$queue      = new Queue( $connection );

		$this->assertInstanceOf( Worker::class, $queue->worker( 3 ) );
	}
}

if ( ! class_exists( 'TestJob' ) ) {

	/**
	 * TestJob class.
	 *
	 * @extends Job
	 */
	class TestJob extends Job {

		/**
		 * handle function.
		 *
		 * @access public
		 * @return void
		 */
		public function handle() {}
	}
}
