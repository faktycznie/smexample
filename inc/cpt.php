<?php
add_action( 'init', 'smexample_reports_init' );
/**
 * Register reports post type
 *
 * @return void
 */
function smexample_reports_init() {
	$labels = array(
		'name'                  => _x( 'Reports', 'Post type general name', 'smexample' ),
		'singular_name'         => _x( 'Report', 'Post type singular name', 'smexample' ),
		'menu_name'             => _x( 'Reports', 'Admin Menu text', 'smexample' ),
		'name_admin_bar'        => _x( 'Report', 'Add New on Toolbar', 'smexample' ),
		'add_new'               => __( 'Add New', 'smexample' ),
		'add_new_item'          => __( 'Add New Report', 'smexample' ),
		'new_item'              => __( 'New Report', 'smexample' ),
		'edit_item'             => __( 'Edit Report', 'smexample' ),
		'view_item'             => __( 'View Report', 'smexample' ),
		'all_items'             => __( 'All Reports', 'smexample' ),
		'search_items'          => __( 'Search Reports', 'smexample' ),
		'parent_item_colon'     => __( 'Parent Reports:', 'smexample' ),
		'not_found'             => __( 'No reports found.', 'smexample' ),
		'not_found_in_trash'    => __( 'No reports found in Trash.', 'smexample' ),
		'archives'              => _x( 'Report archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'smexample' ),
		'insert_into_item'      => _x( 'Insert into report', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'smexample' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this report', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'smexample' ),
		'filter_items_list'     => _x( 'Filter reports list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'smexample' ),
		'items_list_navigation' => _x( 'Reports list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'smexample' ),
		'items_list'            => _x( 'Reports list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'smexample' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'report' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author','excerpt' ),
	);

	register_post_type( 'report', $args );
}
?>