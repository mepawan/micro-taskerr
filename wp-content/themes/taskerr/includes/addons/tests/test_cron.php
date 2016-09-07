<?php
/**
 * Addons Cron Tests
 *
 * @package Components\Addons\Tests
 */
require_once APP_TESTS_LIB . '/testcase.php';

/**
 * @group addons
 */
class APP_Addons_Cron extends APP_UnitTestCase{

	const DAY_IN_SECONDS = 86400;

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

	public function test_prune_post_addons(){

		$addon_type = 'test-addon-type';
		appthemes_register_addon( $addon_type );

		appthemes_add_addon( $this->post_id, $addon_type, -100 );
		$this->asserttrue( appthemes_has_addon( $this->post_id, $addon_type, true ) );

		appthemes_prune_addons();

		$this->assertfalse( appthemes_has_addon( $this->post_id, $addon_type ) );
		$this->assertfalse( appthemes_has_addon( $this->post_id, $addon_type, true ) );

	}

	public function test_prune_user_addons(){

		$addon_type = 'test-user-addon-type';
		$object_id = $this->author->ID;
		appthemes_register_addon( $addon_type, array( 'type' => 'user' ) );

		appthemes_add_addon( $object_id, $addon_type, -100 );
		$this->asserttrue( appthemes_has_addon( $object_id, $addon_type, true ) );

		appthemes_prune_addons();

		$this->assertfalse( appthemes_has_addon( $object_id, $addon_type ) );
		$this->assertfalse( appthemes_has_addon( $object_id, $addon_type, true ) );

	}

	public function test_prune_addons_hook(){

		$addon_type = 'test-addon-type';
		appthemes_register_addon( $addon_type );

		appthemes_add_addon( $this->post_id, $addon_type, -100 );
		appthemes_add_addon( $this->post_id2, $addon_type, 100 );
		$this->asserttrue( appthemes_has_addon( $this->post_id, $addon_type, true ) );
		$this->asserttrue( appthemes_has_addon( $this->post_id2, $addon_type, true ) );

		do_action( 'appthemes_prune_addons_hourly' );

		$this->assertfalse( appthemes_has_addon( $this->post_id, $addon_type ) );
		$this->assertfalse( appthemes_has_addon( $this->post_id, $addon_type, true ) );

		$this->asserttrue( appthemes_has_addon( $this->post_id2, $addon_type ) );
		$this->asserttrue( appthemes_has_addon( $this->post_id2, $addon_type, true ) );
	}

	public function test_prune_addons_users_hook(){

		$addon_type = 'test-addon-type';
		appthemes_register_addon( $addon_type, array( 'type' => 'user' ) );

		$object_id = $this->author->ID;
		$object_id_2 = $this->author2->ID;

		appthemes_add_addon( $object_id, $addon_type, -100 );
		appthemes_add_addon( $object_id_2, $addon_type, 100 );
		$this->asserttrue( appthemes_has_addon( $object_id, $addon_type, true ) );
		$this->asserttrue( appthemes_has_addon( $object_id_2, $addon_type, true ) );

		do_action( 'appthemes_prune_addons_hourly' );

		$this->assertfalse( appthemes_has_addon( $object_id, $addon_type ) );
		$this->assertfalse( appthemes_has_addon( $object_id, $addon_type, true ) );

		$this->asserttrue( appthemes_has_addon( $object_id_2, $addon_type ) );
		$this->asserttrue( appthemes_has_addon( $object_id_2, $addon_type, true ) );
	}
}
