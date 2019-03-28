<?php


/*
* Initializing the Events custom post type
*/
 
function events_post_type() {
 
// Set UI labels for Events post type
    $labels = array(
        'name'                => _x( 'Events', 'Post Type General Name' ),
        'singular_name'       => _x( 'Event', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Events' ),
        'parent_item_colon'   => __( 'Parent Event Item' ),
        'all_items'           => __( 'Events' ),
        'view_item'           => __( 'View Event Item' ),
        'add_new_item'        => __( 'Add New Event Item' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Event Item' ),
        'update_item'         => __( 'Update Event Item' ),
        'search_items'        => __( 'Search Event Items' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Event post type
     
    $args = array(
        'label'               => __( 'events' ),
        'description'         => __( 'Being Boss Events' ),
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
        'show_in_menu'        => 'bbsettings',
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'events', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'events_post_type', 0 );






add_action( 'cmb2_admin_init', 'cmb2_events_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_events_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbevents_';

	/**
	 * Initiate the metabox
	 */
	$bbevents = new_cmb2_box( array(
		'id'            => 'bbevents_metabox',
		'title'         => __( 'Event Details - Public', 'cmb2' ),
		'object_types'  => array( 'events', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

    $bbevents->add_field( array(
            'name'    => 'Event Time',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'event_time',
            'type'    => 'textarea_small',
    ) );

    $bbevents->add_field( array(
            'name'    => 'Event Where',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'event_where',
            'type'    => 'textarea_small',
    ) );
	
	$bbevents->add_field( array(
    		'name'    => 'Event Details',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'event_details',
    		'type'    => 'textarea_small',
	) );

	$bbevents->add_field( array(
    		'name'    => 'Event Link',
    		'desc'    => '',
    		'default' => '',
    		'id'      => $prefix . 'event_link',
    		'type'    => 'text',
	) );

    $bbevents->add_field( array(
            'name'    => 'Event Link Label',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'event_label',
            'type'    => 'text',
    ) );
	
	$bbevents->add_field( array(
		'name'	=> 'Thrive Leads Vacation Video ID',
		'desc'	=> 'Popup Video',
		'default'	=> '',
		'id'		=> $prefix . 'vacation_video',
		'type'		=> 'text',
	) );

    $bbevents->add_field( array(
        'name'  => 'Popup Maker Vacation Video CSS Class',
        'desc'  => 'Popup Video',
        'default'   => '',
        'id'        => $prefix . 'vacation_video_class',
        'type'      => 'text',
    ) );

}





add_action( 'cmb2_admin_init', 'cmb2_events_club_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_events_club_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bbeventsclub_';

    /**
     * Initiate the metabox
     */
    $bbeventsclub = new_cmb2_box( array(
        'id'            => 'bbevents_metabox_club',
        'title'         => __( 'Event Details - Club', 'cmb2' ),
        'object_types'  => array( 'events', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );

    $bbeventsclub->add_field( array(
            'name'    => 'Event Time',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'event_time',
            'type'    => 'textarea_small',
    ) );

    $bbeventsclub->add_field( array(
            'name'    => 'Event Link',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'event_link',
            'type'    => 'text',
    ) );

    $bbeventsclub->add_field( array(
            'name'    => 'Event Link Label',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'event_label',
            'type'    => 'text',
    ) );

    $bbeventsclub->add_field( array(
            'name' => 'Is Emily hosting?',
            'desc' => 'If Emily will be attending, check the box',
            'id'   => $prefix . 'emily_host',
            'type' => 'checkbox',
    ) );

    $bbeventsclub->add_field( array(
            'name' => 'Is Kathleen hosting?',
            'desc' => 'If Kathleen will be attending, check the box',
            'id'   => $prefix . 'kathleen_host',
            'type' => 'checkbox',
    ) );

    $bbeventsclub->add_field( array(
            'name'    => 'Guest 1 - Name',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'guest_one_name',
            'type'    => 'text',
    ) );

    $bbeventsclub->add_field( array(
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

    $bbeventsclub->add_field( array(
            'name'    => 'Guest 2 - Name',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'guest_two_name',
            'type'    => 'text',
    ) );

    $bbeventsclub->add_field( array(
            'name' => 'Guest 2 - Image',
            'desc' => '',
            'default' => '',
            'id' => $prefix . 'guest_two_image',
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

}






// Add new Event Type taxonomy
add_action( 'init', 'create_eventtype_hierarchical_taxonomy', 0 );
 
function create_eventtype_hierarchical_taxonomy() {
 
  $labels = array(
    'name' => _x( 'Event Type', 'taxonomy general name' ),
    'singular_name' => _x( 'Event Type', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Types' ),
    'all_items' => __( 'All Event Types' ),
    'parent_item' => __( 'Parent Type' ),
    'parent_item_colon' => __( 'Parent Type:' ),
    'edit_item' => __( 'Edit Event Type' ), 
    'update_item' => __( 'Update Event Type' ),
    'add_new_item' => __( 'Add New Event Type' ),
    'new_item_name' => __( 'New Event Type Name' ),
    'menu_name' => __( 'Event Types' ),
  );    
 
// Registers the taxonomy
 
  register_taxonomy('eventtype',array('events'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'eventtype' ),
  ));
 
}









?>