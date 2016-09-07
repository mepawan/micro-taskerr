<?php
/**
 * Addons query tests
 *
 * @package Components\Addons\Tests
 */
require_once APP_TESTS_LIB . '/testcase.php';

/**
 * @group addons
 */
class APP_Addons_Query extends APP_UnitTestCase{

	function setUp(){
		parent::setUp();

		$this->author = new WP_User( $this->factory->user->create( array( 'role' => 'editor' ) ) );
		$this->author2 = new WP_User( $this->factory->user->create( array( 'role' => 'editor' ) ) );

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

	public function test_query_filter(){

		appthemes_register_addon( 'some-addon-name' );

		appthemes_add_addon( $this->post_id, 'some-addon-name', 100 );
		$posts = new WP_Query( array( 'addon' => 'some-addon-name' ) );
		$this->assertNotEmpty( $posts->posts );
		$this->assertEquals( 1, $posts->post_count );

	}
}
