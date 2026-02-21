<?php
/**
 * Register and manage the Sermon custom post type.
 *
 * @package    WP_Sermons
 */

class WP_Sermons_Post_Type {

	/**
	 * The post type slug.
	 *
	 * @var string
	 */
	const POST_TYPE = 'sermon';

	/**
	 * Register the Sermon custom post type.
	 */
	public function register() {
		$labels = array(
			'name'                  => _x( 'Sermons', 'Post type general name', 'wp-sermons' ),
			'singular_name'         => _x( 'Sermon', 'Post type singular name', 'wp-sermons' ),
			'menu_name'             => _x( 'Sermons', 'Admin Menu text', 'wp-sermons' ),
			'name_admin_bar'        => _x( 'Sermon', 'Add New on Toolbar', 'wp-sermons' ),
			'add_new'               => __( 'Add New', 'wp-sermons' ),
			'add_new_item'          => __( 'Add New Sermon', 'wp-sermons' ),
			'new_item'              => __( 'New Sermon', 'wp-sermons' ),
			'edit_item'             => __( 'Edit Sermon', 'wp-sermons' ),
			'view_item'             => __( 'View Sermon', 'wp-sermons' ),
			'all_items'             => __( 'All Sermons', 'wp-sermons' ),
			'search_items'          => __( 'Search Sermons', 'wp-sermons' ),
			'parent_item_colon'     => __( 'Parent Sermons:', 'wp-sermons' ),
			'not_found'             => __( 'No sermons found.', 'wp-sermons' ),
			'not_found_in_trash'    => __( 'No sermons found in Trash.', 'wp-sermons' ),
			'featured_image'        => _x( 'Sermon Image', 'Overrides the "Featured Image" phrase', 'wp-sermons' ),
			'set_featured_image'    => _x( 'Set sermon image', 'Overrides the "Set featured image" phrase', 'wp-sermons' ),
			'remove_featured_image' => _x( 'Remove sermon image', 'Overrides the "Remove featured image" phrase', 'wp-sermons' ),
			'use_featured_image'    => _x( 'Use as sermon image', 'Overrides the "Use as featured image" phrase', 'wp-sermons' ),
			'archives'              => _x( 'Sermon archives', 'The post type archive label used in nav menus', 'wp-sermons' ),
			'insert_into_item'      => _x( 'Insert into sermon', 'Overrides the "Insert into post" phrase', 'wp-sermons' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this sermon', 'Overrides the "Uploaded to this post" phrase', 'wp-sermons' ),
			'filter_items_list'     => _x( 'Filter sermons list', 'Screen reader text for the filter links', 'wp-sermons' ),
			'items_list_navigation' => _x( 'Sermons list navigation', 'Screen reader text for the pagination', 'wp-sermons' ),
			'items_list'            => _x( 'Sermons list', 'Screen reader text for the items list', 'wp-sermons' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'sermons' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-book-alt',
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
			'show_in_rest'       => true,
		);

		register_post_type( self::POST_TYPE, $args );

		// Register taxonomies
		$this->register_taxonomies();
	}

	/**
	 * Register custom taxonomies for sermons.
	 */
	private function register_taxonomies() {
		// Sermon Series taxonomy
		$series_labels = array(
			'name'              => _x( 'Sermon Series', 'taxonomy general name', 'wp-sermons' ),
			'singular_name'     => _x( 'Series', 'taxonomy singular name', 'wp-sermons' ),
			'search_items'      => __( 'Search Series', 'wp-sermons' ),
			'all_items'         => __( 'All Series', 'wp-sermons' ),
			'parent_item'       => __( 'Parent Series', 'wp-sermons' ),
			'parent_item_colon' => __( 'Parent Series:', 'wp-sermons' ),
			'edit_item'         => __( 'Edit Series', 'wp-sermons' ),
			'update_item'       => __( 'Update Series', 'wp-sermons' ),
			'add_new_item'      => __( 'Add New Series', 'wp-sermons' ),
			'new_item_name'     => __( 'New Series Name', 'wp-sermons' ),
			'menu_name'         => __( 'Series', 'wp-sermons' ),
		);

		$series_args = array(
			'hierarchical'      => true,
			'labels'            => $series_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sermon-series' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'sermon_series', array( self::POST_TYPE ), $series_args );

		// Speaker taxonomy
		$speaker_labels = array(
			'name'              => _x( 'Speakers', 'taxonomy general name', 'wp-sermons' ),
			'singular_name'     => _x( 'Speaker', 'taxonomy singular name', 'wp-sermons' ),
			'search_items'      => __( 'Search Speakers', 'wp-sermons' ),
			'all_items'         => __( 'All Speakers', 'wp-sermons' ),
			'parent_item'       => __( 'Parent Speaker', 'wp-sermons' ),
			'parent_item_colon' => __( 'Parent Speaker:', 'wp-sermons' ),
			'edit_item'         => __( 'Edit Speaker', 'wp-sermons' ),
			'update_item'       => __( 'Update Speaker', 'wp-sermons' ),
			'add_new_item'      => __( 'Add New Speaker', 'wp-sermons' ),
			'new_item_name'     => __( 'New Speaker Name', 'wp-sermons' ),
			'menu_name'         => __( 'Speakers', 'wp-sermons' ),
		);

		$speaker_args = array(
			'hierarchical'      => false,
			'labels'            => $speaker_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'speaker' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'sermon_speaker', array( self::POST_TYPE ), $speaker_args );

		// Topic taxonomy
		$topic_labels = array(
			'name'              => _x( 'Topics', 'taxonomy general name', 'wp-sermons' ),
			'singular_name'     => _x( 'Topic', 'taxonomy singular name', 'wp-sermons' ),
			'search_items'      => __( 'Search Topics', 'wp-sermons' ),
			'all_items'         => __( 'All Topics', 'wp-sermons' ),
			'parent_item'       => __( 'Parent Topic', 'wp-sermons' ),
			'parent_item_colon' => __( 'Parent Topic:', 'wp-sermons' ),
			'edit_item'         => __( 'Edit Topic', 'wp-sermons' ),
			'update_item'       => __( 'Update Topic', 'wp-sermons' ),
			'add_new_item'      => __( 'Add New Topic', 'wp-sermons' ),
			'new_item_name'     => __( 'New Topic Name', 'wp-sermons' ),
			'menu_name'         => __( 'Topics', 'wp-sermons' ),
		);

		$topic_args = array(
			'hierarchical'      => false,
			'labels'            => $topic_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sermon-topic' ),
			'show_in_rest'      => true,
		);

		register_taxonomy( 'sermon_topic', array( self::POST_TYPE ), $topic_args );
	}
}
