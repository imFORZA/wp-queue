<?php

use PHPUnit\Framework\TestCase;
use WP_Queue\Connections\DatabaseConnection;
use WP_Queue\Job;

/**
 * TestDatabaseConnection class.
 *
 * @extends TestCase
 */
class TestDatabaseConnection extends TestCase {

	/**
	 * wpdb
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $wpdb;

	/**
	 * setUp function.
	 *
	 * @access public
	 * @return void
	 */
	public function setUp() {
		WP_Mock::setUp();

		$this->wpdb = Mockery::spy( 'WPDB' );;
		$this->wpdb->prefix = "wp_";
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
		$insert_id = 12345;
		$this->wpdb->shouldReceive( 'insert' )->once()->andReturn( 1 );
		$this->wpdb->insert_id = $insert_id;

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertEquals( $insert_id, $instance->push( new TestJob() ) );
	}

	/**
	 * test_push_fail function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_push_fail() {
		$this->wpdb->shouldReceive( 'insert' )->once()->andReturn( false );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertFalse( $instance->push( new TestJob() ) );
	}

	/**
	 * test_pop_success function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_pop_success() {
		$this->wpdb->shouldReceive( 'get_row' )->once()->andReturn( (object) array(
			'id'           => 12345,
			'job'          => serialize( new TestJob() ),
			'attempts'     => 0,
			'reserved_at'  => null,
			'available_at' => '2017-10-09 00:00:00',
			'created_at'   => '2017-10-09 00:00:00',
		) );
		$instance = new DatabaseConnection( $this->wpdb );
		$job = $instance->pop();

		$this->assertInstanceOf( TestJob::class, $job );
		$this->assertEquals( 12345, $job->id() );
		$this->assertEquals( 0, $job->attempts() );
		$this->assertNull( $job->reserved_at() );
		// $this->assertInstanceOf( Carbon::class, $job->available_at() );
		// $this->assertInstanceOf( Carbon::class, $job->created_at() );
	}

	/**
	 * test_pop_fail function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_pop_fail() {
		$this->wpdb->shouldReceive( 'get_row' )->once()->andReturn( null );
		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertFalse( $instance->pop() );
	}

	/**
	 * test_delete_success function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_delete_success() {
		$this->wpdb->shouldReceive( 'delete' )->once()->andReturn( 1 );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertTrue( $instance->delete( new TestJob() ) );
	}

	/**
	 * test_delete_fail function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_delete_fail() {
		$this->wpdb->shouldReceive( 'delete' )->once()->andReturn( false );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertFalse( $instance->delete( new TestJob() ) );
	}

	/**
	 * test_release_success function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_release_success() {
		$this->wpdb->shouldReceive( 'update' )->once()->andReturn( 1 );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertTrue( $instance->release( new TestJob() ) );
	}

	/**
	 * test_release_fail function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_release_fail() {
		$this->wpdb->shouldReceive( 'update' )->once()->andReturn( false );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertFalse( $instance->release( new TestJob() ) );
	}

	/**
	 * test_failure_success function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_failure_success() {
		$this->wpdb->shouldReceive( 'insert' )->once()->andReturn( 1 );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertTrue( $instance->failure( new TestJob(), new Exception() ) );
	}

	/**
	 * test_failure_fail function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_failure_fail() {
		$this->wpdb->shouldReceive( 'insert' )->once()->andReturn( false );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertFalse( $instance->failure( new TestJob(), new Exception() ) );
	}

	/**
	 * test_jobs function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_jobs() {
		$count = rand( 1, 100 );
		$this->wpdb->shouldReceive( 'get_var' )->once()->andReturn( $count );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertEquals( $count, $instance->jobs() );
	}

	/**
	 * test_failed_jobs function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_failed_jobs() {
		$count = rand( 1, 100 );
		$this->wpdb->shouldReceive( 'get_var' )->once()->andReturn( $count );

		$instance = new DatabaseConnection( $this->wpdb );

		$this->assertEquals( $count, $instance->failed_jobs() );
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
