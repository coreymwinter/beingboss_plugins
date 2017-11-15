<?php
/*
Plugin Name: Being Boss - BeingBoss.club
Plugin URI:  https://www.beingboss.club
Description: Custom PHP Functions for Being Boss
Version:     1.1.5
Author:      Corey Winter
Author URI:  https://coreymwinter.com
*/

/**
 * Get the bootstrap!
 */
if ( file_exists( __DIR__ . '/cmb2/init.php' ) ) {
  require_once __DIR__ . '/cmb2/init.php';
} elseif ( file_exists(  __DIR__ . '/CMB2/init.php' ) ) {
  require_once __DIR__ . '/CMB2/init.php';
}


add_action( 'cmb2_admin_init', 'cmb2_bbpage_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbpage_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbpage_';

	/**
	 * Initiate the metabox
	 */
	$bbpage = new_cmb2_box( array(
		'id'            => 'bbpage_metabox',
		'title'         => __( 'Being Boss - Page Specific Options', 'cmb2' ),
		'object_types'  => array( 'page', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbpage->add_field( array(
    		'name'    => 'Header Image',
    		'desc'    => 'Upload an image or enter an URL.',
    		'id'      => $prefix . 'header_image',
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
    		),
	) );

	$bbpage->add_field( array(
    		'name' => 'Header Text Overlay',
    		'desc' => 'Text overlay for page header image (HTML-enabled)',
    		'default' => '',
    		'id' => $prefix . 'header_text',
    		'type' => 'textarea_small'
	) );

	$bbpage->add_field( array(
    		'name' => 'Hide Footer Subscribe Section',
    		'desc' => 'Check box to hide footer subscribe section',
    		'id'   => $prefix . 'hide_subscribe',
    		'type' => 'checkbox',
	) );

	$bbpage->add_field( array(
    		'name'    => 'Page Top Padding',
    		'desc'    => 'Optional padding for top of content area',
    		'default' => '0',
    		'id'      => $prefix . 'top_padding',
    		'type'    => 'text',
	) );

	$bbpage->add_field( array(
    		'name' => 'Page Specific CSS',
    		'desc' => 'Optional custom CSS for this individual page',
    		'default' => '',
    		'id' => $prefix . 'page_css',
    		'type' => 'textarea_small'
	) );

}



/**
 * Add Download URL fields to media uploader
 */
  
function be_attachment_field_credit( $form_fields, $post ) {
 
    $form_fields['be-download-url'] = array(
        'label' => 'Download URL',
        'input' => 'text',
        'value' => get_post_meta( $post->ID, 'be_download_url', true ),
        'helps' => 'Add Download URL',
    );
 
    return $form_fields;
}
 
add_filter( 'attachment_fields_to_edit', 'be_attachment_field_credit', 10, 2 );
 
/**
 * Save values of Download URL in media uploader
 *
 */
 
function be_attachment_field_credit_save( $post, $attachment ) {
 
    if( isset( $attachment['be-download-url'] ) )
update_post_meta( $post['ID'], 'be_download_url', esc_url( $attachment['be-download-url'] ) );
 
    return $post;
}
 
add_filter( 'attachment_fields_to_save', 'be_attachment_field_credit_save', 10, 2 );






add_action( 'after_setup_theme', 'new_image_sizes' );
function new_image_sizes() {
    add_image_size( 'archive-thumb', 350 ); // 350 pixels wide (and unlimited height)
}




/**
 * Add User Profile Fields
 */

$extra_fields =  array( 
	array( 'bb_facebook', __( 'BB Facebook', 'rc_cucm' ), true ),
	array( 'bb_twitter', __( 'BB Twitter', 'rc_cucm' ), true ),
	array( 'bb_googleplus', __( 'BB Google+', 'rc_cucm' ), true ),
	array( 'bb_linkedin', __( 'BB LinkedIn', 'rc_cucm' ), false ),
	array( 'bb_pinterest', __( 'BB Pinterest', 'rc_cucm' ), false ),
	array( 'bb_instagram', __( 'BB Instagram', 'rc_cucm' ), false ),
	array( 'bb_youtube', __( 'BB Youtube', 'rc_cucm' ), false ),
	array( 'bb_website', __( 'BB Website', 'rc_cucm' ), false ),
	array( 'bb_customavatar', __( 'BB Custom Avatar', 'rc_cucm' ), false )
);

// Use the user_contactmethods to add new fields
add_filter( 'user_contactmethods', 'rc_add_user_contactmethods' );


/**
 * Add custom users custom contact methods
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function rc_add_user_contactmethods( $user_contactmethods ) {

	// Get fields
	global $extra_fields;
	
	// Display each fields
	foreach( $extra_fields as $field ) {
		if ( !isset( $contactmethods[ $field[0] ] ) )
    		$user_contactmethods[ $field[0] ] = $field[1];
	}

    // Returns the contact methods
    return $user_contactmethods;
}










/*
* Initializing the Webinar Replays custom post type
*/
 
function webinar_post_type() {
 
// Set UI labels for Webinar post type
    $labels = array(
        'name'                => _x( 'Webinars', 'Post Type General Name' ),
        'singular_name'       => _x( 'Webinar', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Webinars' ),
        'parent_item_colon'   => __( 'Parent Webinar' ),
        'all_items'           => __( 'All Webinars' ),
        'view_item'           => __( 'View Webinar' ),
        'add_new_item'        => __( 'Add New Webinar' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Webinar' ),
        'update_item'         => __( 'Update Webinar' ),
        'search_items'        => __( 'Search Webinars' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Webinar post type
     
    $args = array(
        'label'               => __( 'webinar' ),
        'description'         => __( 'Being Boss Webinar Replays' ),
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
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'webinar', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'webinar_post_type', 0 );



/**
 * Creates Resources Taxonomy for Webinar Replay
 */
function custom_resources_webinar_init(){

  //set some options for our new custom taxonomy
  $args = array(
    'label' => __( 'Related Resources' ),
    'hierarchical' => true,
    'capabilities' => array(
      // allow anyone editing posts to assign terms
      'assign_terms' => 'edit_posts',
      /* 
      * but you probably don't want anyone except 
      * admins messing with what gets auto-generated! 
      */
      'edit_terms' => 'administrator'
    )
  );

  /* 
  * create the custom taxonomy and attach it to
  * custom post type A 
  */
  register_taxonomy( 'related-resources-webinar', 'webinar', $args);
}

add_action( 'init', 'custom_resources_webinar_init' );




/**
 * Populates Resources Taxonomy with Webinar Custom Post Type
 */
function update_resources_webinar_terms($post_id) {

  // only update terms if it's a post-type-B post
  if ( 'resources' != get_post_type($post_id)) {
    return;
  }

  // don't create or update terms for system generated posts
  if (get_post_status($post_id) == 'auto-draft') {
    return;
  }
    
  /*
  * Grab the post title and slug to use as the new 
  * or updated term name and slug
  */
  $term_title = get_the_title($post_id);
  $term_slug = get_post( $post_id )->post_name;

  /*
  * Check if a corresponding term already exists by comparing 
  * the post ID to all existing term descriptions. 
  */
  $existing_terms = get_terms('related-resources-webinar', array(
    'hide_empty' => false
    )
  );

  foreach($existing_terms as $term) {
    if ($term->description == $post_id) {
      //term already exists, so update it and we're done
      wp_update_term($term->term_id, 'related-resources-webinar', array(
        'name' => $term_title,
        'slug' => $term_slug
        )
      );
      return;
    }
  }

  /* 
  * If we didn't find a match above, this is a new post, 
  * so create a new term.
  */
  wp_insert_term($term_title, 'related-resources-webinar', array(
    'slug' => $term_slug,
    'description' => $post_id
    )
  );
}

//run the update function whenever a post is created or edited
add_action('save_post', 'update_resources_webinar_terms');








?>
