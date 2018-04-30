<?php

use PHPUnit\Framework\TestCase;
use WP_Queue\Job;

/**
 * TestJobAbstract class.
 *
 * @extends TestCase
 */
class TestJobAbstract extends TestCase {

	/**
	 * instance
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $instance;

	/**
	 * setUp function.
	 *
	 * @access public
	 * @return void
	 */
	public function setUp() {
		WP_Mock::setUp();
		$this->instance = $this->getMockForAbstractClass( Job::class );
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
	 * test_release function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_release() {
		$this->assertFalse( $this->instance->released() );
		$this->instance->release();
		$this->assertTrue( $this->instance->released() );
	}

	/**
	 * test_fail function.
	 *
	 * @access public
	 * @return void
	 */
	public function test_fail() {
		$this->assertFalse( $this->instance->failed() );
		$this->instance->fail();
		$this->assertTrue( $this->instance->failed() );
	}
}
