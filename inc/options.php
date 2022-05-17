<?php
/**
 * Get theme options easily
 *
 * @param string $key
 * @param string $default
 * @return void
 */
function smexample_get_option( $key = '', $default = false ) {
	if ( function_exists( 'cmb2_get_option' ) ) {
		// Use cmb2_get_option as it passes through some key filters.
		return cmb2_get_option( 'smexample_theme_options', $key, $default );
	}

	// Fallback to get_option if CMB2 is not loaded yet.
	$opts = get_option( 'smexample_theme_options', $default );

	$val = $default;

	if ( 'all' == $key ) {
		$val = $opts;
	} elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
		$val = $opts[ $key ];
	}

	return $val;
}

/**
 * Get post metaboxes easily
 *
 * @param int $post_id
 * @param string $field_name
 * @return void
 */
function smexample_get_meta( $post_id, $field_name ) {
	return get_post_meta( $post_id, $field_name, true );
}

add_action( 'cmb2_admin_init', 'smexample_register_theme_options_metabox' );
/**
 * Hook in and register a metabox to handle a theme options page and adds a menu item.
 */
function smexample_register_theme_options_metabox() {

	/**
	 * Registers options page menu item and form.
	 */
	$cmb_options = new_cmb2_box( array(
		'id'           => 'smexample_theme_options_page',
		'title'        => esc_html__( 'Theme Options', 'smexample' ),
		'object_types' => array( 'options-page' ),

		/*
		 * The following parameters are specific to the options-page box
		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
		 */

		'option_key'      => 'smexample_theme_options', // The option key and admin menu page slug.
		'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
		// 'menu_title'      => esc_html__( 'Options', 'cmb2' ), // Falls back to 'title' (above).
		// 'parent_slug'     => 'themes.php', // Make options page a submenu item of the themes menu.
		// 'capability'      => 'manage_options', // Cap required to view options-page.
		// 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
		// 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
		// 'save_button'     => esc_html__( 'Save Theme Options', 'cmb2' ), // The text for the options-page save button. Defaults to 'Save'.
		// 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
		// 'message_cb'      => 'smexample_options_page_message_callback',
	) );

	$slider_group = $cmb_options->add_field( array(
		'id'          => 'hero_slider',
		'name'       => 'Hero slider (home)',
		'type'        => 'group',
		'repeatable'  => true,
		'options'     => array(
			'group_title'       => __( 'Slide {#}', 'smexample' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'        => __( 'Add slide', 'smexample' ),
			'remove_button'     => __( 'Remove slide', 'smexample' ),
			'sortable'          => true,
			'closed'         => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'smexample' ), // Performs confirmation before removing group.
		),
	) );

	$cmb_options->add_group_field( $slider_group, array(
		'name' => 'Image',
		'id'   => 'image',
		'type' => 'file',
	) );
	
	$cmb_options->add_group_field( $slider_group, array(
		'name' => 'Slogan',
		'id'   => 'slogan',
		'type' => 'text',
	) );

	$cmb_options->add_group_field( $slider_group, array(
		'name' => 'Link',
		'id'   => 'link',
		'type' => 'text',
	) );

	$cmb_options->add_field( array(
		'name' => 'Cookie consent message',
		'id' => 'cookie_consent',
		'type' => 'textarea'
	) );

	// contact

	$cmb_options->add_field( array(
		'name'    => 'Contact name',
		'id'      => 'contact_name',
		'type'    => 'text_medium'
	) );

	$cmb_options->add_field( array(
		'name'    => 'Contact subname',
		'id'      => 'contact_subname',
		'type'    => 'text_medium'
	) );

	$cmb_options->add_field( array(
		'name' => 'Contact email',
		'id'   => 'contact_email',
		'type' => 'text_email',
	) );

	$cmb_options->add_field( array(
		'name'    => 'Contact picture',
		'id'      => 'contact_img',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		'query_args' => array(
			// Or only allow gif, jpg, or png images
			// 'type' => array(
			// 	'image/gif',
			// 	'image/jpeg',
			// 	'image/png',
			// ),
		),
		'preview_size' => 'thumbnail', // Image size to use when previewing in the admin.
	) );

	
	$reports_group = $cmb_options->add_field( array(
		'id'          => 'reports',
		'name'       => 'Reports',
		'type'        => 'group',
		'repeatable'  => true,
		'options'     => array(
			'group_title'       => __( 'Item {#}', 'smexample' ), // since version 1.1.4, {#} gets replaced by row number
			'add_button'        => __( 'Add item', 'smexample' ),
			'remove_button'     => __( 'Remove item', 'smexample' ),
			'sortable'          => true,
			'closed'         => true, // true to have the groups closed by default
			// 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'smexample' ), // Performs confirmation before removing group.
		),
	) );

	$cmb_options->add_group_field( $reports_group, array(
		'name' => 'Title',
		'id'   => 'title',
		'type' => 'text',
	) );

	$cmb_options->add_group_field( $reports_group, array(
		'name' => 'Icon',
		'id'   => 'image',
		'type' => 'file',
	) );

	$cmb_options->add_group_field( $reports_group, array(
		'name' => 'Content',
		'id'   => 'content',
		'type' => 'wysiwyg',
	) );

	//CPT

	$cmb_reports = new_cmb2_box( array(
		'id'            => 'reports_metabox',
		'title'         => __( 'Options', 'smexample' ),
		'object_types'  => array( 'report' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$cmb_reports->add_field( array(
		'name'    => 'Subname',
		'id'      => 'subname',
		'type'    => 'text_medium'
	) );

	$cmb_reports->add_field( array(
		'name'    => 'PDF file',
		'id'      => 'file',
		'type'    => 'file',
		// Optional:
		'options' => array(
			'url' => false, // Hide the text input for the url
		),
		'text'    => array(
			'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
		),
		// query_args are passed to wp.media's library query.
		'query_args' => array(
			'type' => 'application/pdf', // Make library only display PDFs.
			// Or only allow gif, jpg, or png images
			// 'type' => array(
			// 	'image/gif',
			// 	'image/jpeg',
			// 	'image/png',
			// ),
		),
		'preview_size' => 'thumbnail', // Image size to use when previewing in the admin.
	) );

}
?>