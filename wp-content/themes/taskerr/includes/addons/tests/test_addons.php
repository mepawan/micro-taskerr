<?php
/**
 * Addons Tests
 *
 * @package Components\Addons\Tests
 */
require_once APP_TESTS_LIB . '/testcase.php';

/**
 * @group addons
 */
class APP_Addons_Test extends APP_UnitTestCase{

	const DAY_IN_SECONDS = 86400;

	function setUp(){
		parent::setUp();

		$this->author = new WP_User( $this->factory->user->create( array( 'role' => 'editor' ) ) );

		$this->post_id = wp_insert_post( array(
			'post_author' => $this->author->ID,
			'post_status' => 'publish',
			'post_content' => rand_str(),
			'post_title' => rand_str(),
		) );

		$this->post_id2 = wp_insert_post( array(
			'post_author' => $this->author->ID,
			'post_status' => 'publish',
			'post_content' => rand_str(),
			'post_title' => rand_str(),
		) );
	}

	public function test_set_addon_post(){

		$object_id = $this->post_id;
		$addon_type = 'test-addon-type';
		$addon_duration = mt_rand( 100, 1000 );
		$addon_start_date = current_time( 'mysql' );
		$addon_end_time = strtotime( $addon_start_date ) + ( DAY_IN_SECONDS * $addon_duration );
		$addon_end_date = gmdate( 'Y-m-d H:i:s', $addon_end_time );
		appthemes_register_addon( $addon_type );

		$this->assertFalse( appthemes_has_addon( $object_id, $addon_type ) );

		appthemes_set_addon( $object_id, $addon_type, $addon_duration, $addon_start_date );

		$test_duration = appthemes_get_addon_duration( $object_id, $addon_type );
		$this->assertEquals( $addon_duration, $test_duration );

		$test_start_date = appthemes_get_addon_start_date( $object_id, $addon_type );
		$this->assertEquals( $addon_start_date, $test_start_date );

		$test_end_date = appthemes_get_addon_end_date( $object_id, $addon_type );
		$this->assertEquals( $addon_end_date, $test_end_date );

		$this->assertTrue( appthemes_has_addon( $object_id, $addon_type ) );
		$this->assertFalse( appthemes_has_addon( $object_id, $addon_type . 'bad' ) );


	}

	public function test_add_addon_post(){

		$object_id = $this->post_id;
		$addon_type = 'test-addon-type';
		$addon_duration = mt_rand( 100, 1000 );
		$addon_duration_2 = mt_rand( 100, 1000 );
		$addon_start_date = current_time( 'mysql' );
		$addon_end_time = strtotime( $addon_start_date ) + ( DAY_IN_SECONDS * $addon_duration );
		$addon_end_date = gmdate( 'Y-m-d H:i:s', $addon_end_time );
		$addon_end_time_2 = strtotime( $addon_end_date ) + ( DAY_IN_SECONDS * $addon_duration_2 );
		$addon_end_date_2 = gmdate( 'Y-m-d H:i:s', $addon_end_time_2 );
		appthemes_register_addon( $addon_type );

		$this->assertFalse( appthemes_has_addon( $object_id, $addon_type ) );

		appthemes_add_addon( $object_id, $addon_type, $addon_duration );
		$this->assertTrue( appthemes_has_addon( $object_id, $addon_type ) );

		$test_duration = appthemes_get_addon_duration( $object_id, $addon_type );
		$this->assertEquals( $addon_duration, $test_duration );

		$test_start_date = appthemes_get_addon_start_date( $object_id, $addon_type );
		$this->assertEquals( $addon_start_date, $test_start_date );

		$test_end_date = appthemes_get_addon_end_date( $object_id, $addon_type );
		$this->assertEquals( $addon_end_date, $test_end_date );

		appthemes_add_addon( $object_id, $addon_type, $addon_duration_2 );
		$this->assertTrue( appthemes_has_addon( $object_id, $addon_type ) );

		$test_start_date_2 = appthemes_get_addon_start_date( $object_id, $addon_type );
		$this->assertEquals( $addon_start_date, $test_start_date_2 );

		$test_end_date_2 = appthemes_get_addon_end_date( $object_id, $addon_type );
		$this->assertEquals( $addon_end_date_2, $test_end_date_2 );

	}

	public function test_set_addon_user(){

		$object_id = $this->author->ID;
		$addon_type = 'test-addon-type';
		$addon_duration = mt_rand( 100, 1000 );
		$addon_start_date = current_time( 'mysql' );
		$addon_end_time = strtotime( $addon_start_date ) + ( DAY_IN_SECONDS * $addon_duration );
		$addon_end_date = gmdate( 'Y-m-d H:i:s', $addon_end_time );
		appthemes_register_addon( $addon_type, array( 'type' => 'user' ) );

		$this->assertFalse( appthemes_has_addon( $object_id, $addon_type ) );

		appthemes_set_addon( $object_id, $addon_type, $addon_duration, $addon_start_date );

		$test_duration = appthemes_get_addon_duration( $object_id, $addon_type );
		$this->assertEquals( $addon_duration, $test_duration );

		$test_start_date = appthemes_get_addon_start_date( $object_id, $addon_type );
		$this->assertEquals( $addon_start_date, $test_start_date );

		$test_end_date = appthemes_get_addon_end_date( $object_id, $addon_type );
		$this->assertEquals( $addon_end_date, $test_end_date );

		$this->assertTrue( appthemes_has_addon( $object_id, $addon_type ) );
		$this->assertFalse( appthemes_has_addon( $object_id, $addon_type . 'bad' ) );


	}

	public function test_add_addon_user(){

		$object_id = $this->author->ID;
		$addon_type = 'test-addon-type';
		$addon_duration = mt_rand( 100, 1000 );
		$addon_duration_2 = mt_rand( 100, 1000 );
		$addon_start_date = current_time( 'mysql' );
		$addon_end_time = strtotime( $addon_start_date ) + ( DAY_IN_SECONDS * $addon_duration );
		$addon_end_date = gmdate( 'Y-m-d H:i:s', $addon_end_time );
		$addon_end_time_2 = strtotime( $addon_end_date ) + ( DAY_IN_SECONDS * $addon_duration_2 );
		$addon_end_date_2 = gmdate( 'Y-m-d H:i:s', $addon_end_time_2 );
		appthemes_register_addon( $addon_type, array( 'type' => 'user' ) );

		$this->assertFalse( appthemes_has_addon( $object_id, $addon_type ) );

		appthemes_add_addon( $object_id, $addon_type, $addon_duration );
		$this->assertTrue( appthemes_has_addon( $object_id, $addon_type ) );

		$test_duration = appthemes_get_addon_duration( $object_id, $addon_type );
		$this->assertEquals( $addon_duration, $test_duration );

		$test_start_date = appthemes_get_addon_start_date( $object_id, $addon_type );
		$this->assertEquals( $addon_start_date, $test_start_date );

		$test_end_date = appthemes_get_addon_end_date( $object_id, $addon_type );
		$this->assertEquals( $addon_end_date, $test_end_date );

		appthemes_add_addon( $object_id, $addon_type, $addon_duration_2 );
		$this->assertTrue( appthemes_has_addon( $object_id, $addon_type ) );

		$test_start_date_2 = appthemes_get_addon_start_date( $object_id, $addon_type );
		$this->assertEquals( $addon_start_date, $test_start_date_2 );

		$test_end_date_2 = appthemes_get_addon_end_date( $object_id, $addon_type );
		$this->assertEquals( $addon_end_date_2, $test_end_date_2 );

	}

	public function test_has_addon(){

		$addon_type = 'test-addon-type';
		appthemes_register_addon( $addon_type );

		$this->assertFalse( appthemes_has_addon( $this->post_id, $addon_type ) );

		appthemes_add_addon( $this->post_id, $addon_type, -100 );
		$this->assertFalse( appthemes_has_addon( $this->post_id, $addon_type ) );
		$this->assertTrue( appthemes_has_addon( $this->post_id, $addon_type, true ) );

	}

}
