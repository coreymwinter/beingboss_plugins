<?php

class Boss_Directory_Plugin {
    public function __construct() {
		// Hook into the admin menu
		add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
	}
	
	public function create_plugin_settings_page() {
		// Add the menu item and page
		$page_title = 'Boss Directory Settings';
		$menu_title = 'Settings';
		$capability = 'manage_options';
		$slug = 'boss_directory';
		$callback = array( $this, 'plugin_settings_page_content' );
		$icon = 'dashicons-admin-plugins';
		$position = 99;

		add_submenu_page( 'edit.php?post_type=listing', $page_title, $menu_title, $capability, $slug, $callback );
	}
	
	public function plugin_settings_page_content() {
		echo 'Hello World!';
	}
}
new Boss_Directory_Plugin();






/*
* Initializing the Directory custom post type
*/
 
function directory_post_type() {
 
// Set UI labels for Directory post type
    $labels = array(
        'name'                => _x( 'Directory Listings', 'Post Type General Name' ),
        'singular_name'       => _x( 'Listing', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Boss Directory' ),
        'parent_item_colon'   => __( 'Parent Listing' ),
        'all_items'           => __( 'All Listings' ),
        'view_item'           => __( 'View Listing' ),
        'add_new_item'        => __( 'Add New Listing' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Listing' ),
        'update_item'         => __( 'Update Listing' ),
        'search_items'        => __( 'Search Listings' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Directory post type
     
    $args = array(
        'label'               => __( 'listing' ),
        'description'         => __( 'Being Boss Directory Listings' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 55,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
		'menu_icon'			  => 'https://beingboss.club/wp-content/themes/beingboss2018/img/Icon_Resource_Blue.png'
    );
     
    // Registering your Custom Post Type
    register_post_type( 'listing', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'directory_post_type', 0 );





// add_action( 'admin_init', 'add_admin_menu_separator' );
// function add_admin_menu_separator( $position ) {

// 	global $menu;

// 	$menu[ $position ] = array(
// 		0	=>	'',
// 		1	=>	'read',
// 		2	=>	'separator' . $position,
// 		3	=>	'',
// 		4	=>	'wp-menu-separator'
// 	);

// }

// add_action( 'admin_menu', 'set_admin_menu_separator' );
// function set_admin_menu_separator() {
// 	do_action( 'admin_init', 52 );
// } // end set_admin_menu_separator





add_action( 'cmb2_init', 'cmb2_bbdirectory_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbdirectory_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbdirectory_';

	/**
	 * Initiate the metabox
	 */
	$bbdirectory = new_cmb2_box( array(
		'id'            => 'bbdirectory_metabox',
		'title'         => __( 'Public Listing Details', 'cmb2' ),
		'object_types'  => array( 'listing', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


	$bbdirectory->add_field( array(
    		'name' => 'Header Text Overlay',
    		'desc' => 'Text overlay for page header image (HTML-enabled)',
    		'default' => '',
    		'id' => $prefix . 'header_text',
    		'type' => 'textarea_small'
	) );


	$bbdirectory->add_field( array(
    		'name'    => 'Webinar Month',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'month',
    		'type'    => 'text',
	) );

	$bbdirectory->add_field( array(
    		'name'    => 'Webinar Day',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'day',
    		'type'    => 'text',
	) );

	$bbdirectory->add_field( array(
    		'name'    => 'Guest 1 - Name',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'guest_one_name',
    		'type'    => 'textarea_small',
	) );

	$bbdirectory->add_field( array(
    		'name' => 'Guest 1 - Image',
    		'desc' => '',
    		'default' => '',
    		'id' => $prefix . 'guest_one_image',
    		'type' => 'file',
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
    		),

	) );

	$bbdirectory->add_field( array(
    		'name'    => 'Replay Video',
    		'desc'    => 'Embed code for replay video',
    		'default' => '',
    		'id'      => $prefix . 'replay_video',
    		'type'    => 'text',
	) );

	$bbdirectory->add_field( array(
    		'name'    => 'External Link',
    		'desc'    => 'External link (such as for the homepage)',
    		'default' => '',
    		'id'      => $prefix . 'external_link',
    		'type'    => 'text',
	) );

}












add_action( 'cmb2_init', 'cmb2_bbdirectory_private_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbdirectory_private_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbdp_';

	/**
	 * Initiate the metabox
	 */
	$bbdp = new_cmb2_box( array(
		'id'            => 'bbdirectory_private_mb',
		'title'         => __( 'Private Listing Details', 'cmb2' ),
		'object_types'  => array( 'listing', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );


	$bbdp->add_field( array(
    		'name' => 'Header Text Overlay',
    		'desc' => 'Text overlay for page header image (HTML-enabled)',
    		'default' => '',
    		'id' => $prefix . 'header_text',
    		'type' => 'textarea_small'
	) );


	$bbdp->add_field( array(
    		'name'    => 'Webinar Month',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'month',
    		'type'    => 'text',
	) );


}













// Add new Directory category taxonomy
add_action( 'init', 'create_directory_hierarchical_taxonomy', 0 );
 
function create_directory_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Directory Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Directory Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Categories' ),
    'all_items' => __( 'All Directory Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Directory Category' ), 
    'update_item' => __( 'Update Directory Category' ),
    'add_new_item' => __( 'Add New Directory Category' ),
    'new_item_name' => __( 'New Directory Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('directorycategories',array('listing'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'directorycategories' ),
  ));
 
}


// Add new Directory tier taxonomy
add_action( 'init', 'create_directory_tier_taxonomy', 0 );
 
function create_directory_tier_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Directory Tiers', 'taxonomy general name' ),
    'singular_name' => _x( 'Directory Tier', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Tiers' ),
    'all_items' => __( 'All Directory Tiers' ),
    'parent_item' => __( 'Parent Tier' ),
    'parent_item_colon' => __( 'Parent Tier:' ),
    'edit_item' => __( 'Edit Directory Tier' ), 
    'update_item' => __( 'Update Directory Tier' ),
    'add_new_item' => __( 'Add New Directory Tier' ),
    'new_item_name' => __( 'New Directory Tier Name' ),
    'menu_name' => __( 'Tiers' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('directorytiers',array('listing'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'directorytiers' ),
  ));
 
}








add_shortcode( 'cmb-form', 'cmb2_do_frontend_form_shortcode' );
/**
 * Shortcode to display a CMB2 form for a post ID.
 * @param  array  $atts Shortcode attributes
 * @return string       Form HTML markup
 */
function cmb2_do_frontend_form_shortcode( $atts = array() ) {
	global $post;

	/**
	 * Depending on your setup, check if the user has permissions to edit_posts
	 */
	if ( ! current_user_can( 'edit_posts' ) ) {
		return __( 'You do not have permissions to edit this post.', 'lang_domain' );
	}

	/**
	 * Make sure a WordPress post ID is set.
	 * We'll default to the current post/page
	 */
	if ( ! isset( $atts['post_id'] ) ) {
		$atts['post_id'] = $post->ID;
	}

	// If no metabox id is set, yell about it
	if ( empty( $atts['id'] ) ) {
		return __( "Please add an 'id' attribute to specify the CMB2 form to display.", 'lang_domain' );
	}

	$metabox_id = esc_attr( $atts['id'] );
	$object_id = absint( $atts['post_id'] );
	// Get our form
	$form = cmb2_get_metabox_form( $metabox_id, $object_id );

	return $form;
}




?>