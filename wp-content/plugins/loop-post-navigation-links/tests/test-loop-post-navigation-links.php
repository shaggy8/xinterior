<?php

defined( 'ABSPATH' ) or die();

class Link_Post_Navigation_Links_Test extends WP_UnitTestCase {

	private static $posts = array();

	public function setUp() {
		parent::setUp();
		$this->posts = $this->create_posts();
	}

	public function tearDown() {
		parent::tearDown();
		foreach( array( 'c2c_next_or_loop_post_link_output', 'c2c_previous_or_loop_post_link_output') as $filter )
			remove_filter( $filter, array( $this, 'filter_append' ) );
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//

	protected function create_posts() {
		$posts = array();
		$posts[] = $this->factory->post->create( array( 'post_title' => 'Post A', 'post_date' => '2013-12-01 15:01:02' ) );
		$posts[] = $this->factory->post->create( array( 'post_title' => 'Post B', 'post_date' => '2013-12-02 15:01:02' ) );
		$posts[] = $this->factory->post->create( array( 'post_title' => 'Post C', 'post_date' => '2013-12-03 15:01:02' ) );

		// A draft, which should never appear.
		$posts[] = $this->factory->post->create( array( 'post_title' => 'Post D', 'post_date' => '2013-12-04 15:01:02', 'post_status' => 'draft' ) );

		$posts[] = $this->factory->post->create( array( 'post_title' => 'Post E', 'post_date' => '2013-12-05 15:01:02', 'post_type' => 'abc' ) );
		$posts[] = $this->factory->post->create( array( 'post_title' => 'Post F', 'post_date' => '2013-12-06 15:01:02' ) );
		return $posts;
	}

	/**
	 * Loads post as if in loop.
	 *
	 * @param int $post_id Post ID.
	 */
	protected function load_post( $post_id ) {
		global $post;
		$post = get_post( $post_id );
		setup_postdata( $post );
		return $post;
	}

	protected function get_echo_output( $index, $next = true, $args = array(), $via_filter = false ) {
		$post_id = $this->posts[ $index ];
		$this->load_post( $post_id );

		$defaults = array(
			'format'         => '',
			'link'           => '%title',
			'in_same_term'   => false,
			'excluded_terms' => '',
			'taxonomy'       => 'category',
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		ob_start();

		if ( $next ) {
			if ( empty( $format ) )
				$format = '%link &raquo;';

			if ( $via_filter ) {
				do_action( 'c2c_next_or_loop_post_link', $format, $link, $in_same_term, $excluded_terms, $taxonomy );
			} else
				c2c_next_or_loop_post_link( $format, $link, $in_same_term, $excluded_terms, $taxonomy );
		} else {
			if ( empty( $format ) )
				$format = '&laquo; %link';
			if ( $via_filter )
				do_action( 'c2c_previous_or_loop_post_link', $format, $link, $in_same_term, $excluded_terms, $taxonomy );
			else
				c2c_previous_or_loop_post_link( $format, $link, $in_same_term, $excluded_terms, $taxonomy );
		}

		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	protected function expected( $index, $next = true ) {
		$post_id = $this->posts[ $index ];
		$post = get_post( $post_id );

		$dir = $next ? 'next' : 'prev';

		$str = $next ? '' : '&laquo; ';
		$str .= sprintf(
			'<a href="http://example.org/?p=%d" rel="%s">%s</a>',
			$post_id,
			$dir,
			$post->post_title
		);
		$str .= $next ? ' &raquo;' : '';

		return $str;
	}

	public function filter_append( $text ) {
		return $text . '(extra)';
	}


	//
	//
	// TESTS
	//
	//


	public function test_plugin_version() {
		$this->assertEquals( '2.6.1', c2c_LoopPostNavigationLinks::version() );
	}

	public function test_class_is_available() {
		$this->assertTrue( class_exists( 'c2c_LoopPostNavigationLinks' ) );
	}

	public function test_c2c_next_or_loop_post_link() {
		$this->assertEquals( $this->expected( 1 ), $this->get_echo_output( 0 ) );
		$this->assertEquals( $this->expected( 2 ), $this->get_echo_output( 1 ) );
		$this->assertEquals( $this->expected( 5 ), $this->get_echo_output( 2 ) );
		$this->assertEquals( $this->expected( 0 ), $this->get_echo_output( 5 ) );
	}

	public function test_c2c_next_or_loop_post_link_with_non_looping_post() {
		$this->assertEmpty( $this->get_echo_output( 4 ) );
	}

	public function test_c2c_previous_or_loop_post_link() {
		$this->assertEquals( $this->expected( 5, false ), $this->get_echo_output( 0, false ) );
		$this->assertEquals( $this->expected( 0, false ), $this->get_echo_output( 1, false ) );
		$this->assertEquals( $this->expected( 1, false ), $this->get_echo_output( 2, false ) );
		$this->assertEquals( $this->expected( 2, false ), $this->get_echo_output( 5, false ) );
	}

	public function test_c2c_previous_or_loop_post_link_with_non_looping_post() {
		$this->assertEmpty( $this->get_echo_output( 4, false ) );
	}

	public function test_c2c_get_next_or_loop_post() {
		$this->load_post( $this->posts[0] );
		$next = c2c_get_next_or_loop_post();
		$this->assertTrue( is_a( $next, 'WP_Post' ) );
		$this->assertEquals( $this->posts[1], $next->ID );

		$this->load_post( $this->posts[1] );
		$this->assertEquals( $this->posts[2], c2c_get_next_or_loop_post()->ID );

		$this->load_post( $this->posts[2] );
		$this->assertEquals( $this->posts[5], c2c_get_next_or_loop_post()->ID );

		$this->load_post( $this->posts[5] );
		$this->assertEquals( $this->posts[0], c2c_get_next_or_loop_post()->ID );
	}

	public function test_c2c_get_next_or_loop_post_with_non_looping_post() {
		$this->load_post( $this->posts[4] );
		$this->assertNull( c2c_get_next_or_loop_post() );
	}

	public function test_c2c_get_previous_or_loop_post() {
		$this->load_post( $this->posts[0] );
		$next = c2c_get_previous_or_loop_post();
		$this->assertTrue( is_a( $next, 'WP_Post' ) );
		$this->assertEquals( $this->posts[5], $next->ID );

		$this->load_post( $this->posts[1] );
		$this->assertEquals( $this->posts[0], c2c_get_previous_or_loop_post()->ID );

		$this->load_post( $this->posts[2] );
		$this->assertEquals( $this->posts[1], c2c_get_previous_or_loop_post()->ID );

		$this->load_post( $this->posts[5] );
		$this->assertEquals( $this->posts[2], c2c_get_previous_or_loop_post()->ID );
	}

	public function test_c2c_get_previous_or_loop_post_with_non_looping_post() {
		$this->load_post( $this->posts[4] );
		$this->assertNull( c2c_get_previous_or_loop_post() );
	}

	public function test_arg_format() {
		$this->assertEquals( str_replace( '&raquo;', '->', $this->expected( 1 ) ), $this->get_echo_output( 0, true, array( 'format' => '%link ->' ) ) );
		$this->assertEquals( str_replace( '&laquo;', '<-', $this->expected( 0, false ) ), $this->get_echo_output( 1, false, array( 'format' => '<- %link' ) ) );
	}

	public function test_arg_link() {
		$this->assertEquals( str_replace( 'Post B', 'Post B December 2, 2013', $this->expected( 1 ) ), $this->get_echo_output( 0, true, array( 'link' => '%title %date' ) ) );
		$this->assertEquals( str_replace( 'Post A', 'Post A December 1, 2013', $this->expected( 0, false ) ), $this->get_echo_output( 1, false, array( 'link' => '%title %date' ) ) );
	}

	public function test_arg_in_same_term() {
		//TODO; yeah, i know
	}

	public function test_arg_excluded_terms() {
		//TODO; yeah, i know
	}

	public function test_arg_taxonomy() {
		//TODO; yeah, i know
	}

	public function test_filter_invocation() {
		$this->assertEquals( str_replace( 'Post B', 'Post B December 2, 2013', $this->expected( 1 ) ), $this->get_echo_output( 0, true, array( 'link' => '%title %date' ), true ) );
		$this->assertEquals( str_replace( 'Post A', 'Post A December 1, 2013', $this->expected( 0, false ) ), $this->get_echo_output( 1, false, array( 'link' => '%title %date' ), true ) );
	}

	public function test_filter_c2c_next_or_loop_post_link_output() {
		add_filter( 'c2c_next_or_loop_post_link_output', array( $this, 'filter_append' ) );

		$this->assertEquals( $this->expected( 1 ) . '(extra)', $this->get_echo_output( 0 ) );
	}

	public function test_filter_c2c_previous_or_loop_post_link_output() {
		add_filter( 'c2c_previous_or_loop_post_link_output', array( $this, 'filter_append' ) );

		$this->assertEquals( $this->expected( 5, false ) . '(extra)', $this->get_echo_output( 0, false ) );
	}
}
