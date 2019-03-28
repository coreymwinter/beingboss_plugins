<?php

/*
* Initializing the Affiliates custom post type
*/
 
function affiliates_post_type() {
 
// Set UI labels for Affiliates post type
    $labels = array(
        'name'                => _x( 'Affiliates', 'Post Type General Name' ),
        'singular_name'       => _x( 'Affiliate', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Affiliates' ),
        'parent_item_colon'   => __( 'Parent Affiliate' ),
        'all_items'           => __( 'Affiliates' ),
        'view_item'           => __( 'View Affiliate' ),
        'add_new_item'        => __( 'Add New Affiliate' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Affiliate' ),
        'update_item'         => __( 'Update Affiliate' ),
        'search_items'        => __( 'Search Affiliates' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Affiliates post type
     
    $args = array(
        'label'               => __( 'affiliates' ),
        'description'         => __( 'Being Boss Affiliates' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'author', 'thumbnail', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => 'bbsettings',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'affiliates', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'affiliates_post_type', 0 );



add_action( 'cmb2_admin_init', 'cmb2_affiliates_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_affiliates_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbaffiliates_';

	/**
	 * Initiate the metabox
	 */
	$bbaffiliates = new_cmb2_box( array(
		'id'            => 'bbaffiliates_metabox',
		'title'         => __( 'Affiliate Item Details', 'cmb2' ),
		'object_types'  => array( 'affiliates', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbaffiliates->add_field( array(
    		'name'    => 'Affiliate Link',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'link',
    		'type'    => 'text',
	) );

	$bbaffiliates->add_field( array(
    		'name'    => 'Weight/Order (1 is listed first)',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'weight',
    		'type'    => 'text',
	) );

}






// Add new Affiliate category taxonomy
add_action( 'init', 'create_affiliate_hierarchical_taxonomy', 0 );
 
function create_affiliate_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Affiliate Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Affiliate Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Affiliates' ),
    'all_items' => __( 'All Affiliate Categories' ),
    'parent_item' => __( 'Parent Category' ),
    'parent_item_colon' => __( 'Parent Category:' ),
    'edit_item' => __( 'Edit Affiliate Category' ), 
    'update_item' => __( 'Update Affiliate Category' ),
    'add_new_item' => __( 'Add New Affiliate Category' ),
    'new_item_name' => __( 'New Affiliate Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('affiliatecategories',array('affiliates'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'affiliatecategories' ),
  ));
 
}






?>
