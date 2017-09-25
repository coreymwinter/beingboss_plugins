<?php
/*
Plugin Name: Being Boss - Shownotes
Plugin URI:  https://www.beingboss.club
Description: Custom Shownote Fields for Being Boss
Version:     1
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






add_action( 'cmb2_admin_init', 'cmb2_bbshownotes_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbshownotes_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbshownotes_';

	/**
	 * Initiate the metabox
	 */
	$bbshownotes = new_cmb2_box( array(
		'id'            => 'bbshownotes_metabox',
		'title'         => __( 'Being Boss - Shownote Details', 'cmb2' ),
		'object_types'  => array( 'post', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Soundcloud Player',
    		'desc' => 'Paste the entire Soundcloud player embed code',
    		'default' => '',
    		'id' => $prefix . 'soundcloud',
    		'type' => 'textarea_code'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Top Quote',
    		'desc' => 'Main quote for the top of the page',
    		'default' => '',
    		'id' => $prefix . 'top_quote',
    		'type' => 'textarea_small'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Top Quote Author',
    		'desc' => 'Top Quote Author',
    		'id'   => $prefix . 'quote_author',
    		'type' => 'text'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Topics',
    		'desc' => 'Topics discussed in this episode',
    		'id'   => $prefix . 'topics',
    		'type' => 'wysiwyg'
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Resources',
    		'desc' => 'Resources discussed in this episode',
    		'id'   => $prefix . 'resources',
    		'type' => 'wysiwyg'
	) );

	$bbshownoteguest = $bbshownotes->add_field( array(
    		'id'          => $prefix . 'morefrom',
    		'type'        => 'group',
    		'description' => __( 'The More From Guest section. Click Add Another Guest for multiple guests.', 'cmb2' ),
    		'repeatable'  => true, // use false if you want non-repeatable group
    		'options'     => array(
        		'group_title'   => __( 'Guest {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        		'add_button'    => __( 'Add Another Guest', 'cmb2' ),
        		'remove_button' => __( 'Remove Entry', 'cmb2' ),
        		'sortable'      => true, // beta
        		// 'closed'     => true, // true to have the groups closed by default
    		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$bbshownotes->add_group_field( $bbshownoteguest, array(
    		'name' => 'Guest Name',
    		'id'   => $prefix . 'guestname',
    		'type' => 'text',
	) );

	$bbshownotes->add_group_field( $bbshownoteguest, array(
    		'name' => 'Content',
    		'description' => 'The content for the More From Guest section',
    		'id'   => $prefix . 'guestinfo',
    		'type' => 'wysiwyg',
	) );

	$bbshownotes->add_field( array(
    		'name' => 'Pinterest Images',
    		'desc' => '',
    		'id'   => $prefix . 'pinitimages',
    		'type' => 'file_list',
    		// 'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
        		// 'query_args' => array( 'type' => 'image' ), // Only images attachment
    		// Optional, override default text strings
	) );

	$bbshownotes->add_field( array(
    		'name'       => __( 'Episode sponsors', 'cmb2' ),
    		'desc'       => __( 'Select all episode sponsors', 'cmb2' ),
    		'id'         => $prefix . 'sponsor_select',
    		'type'       => 'multicheck',
    		'options_cb' => 'cmb2_get_sponsors_list',
	) );

}






/**
 *
 * @param  string  $bbshownotes_pinitimages
 * @param  string  $img_size
 */
function cmb2_bbshownotes_pinterest_images( $file_list_meta_key, $img_size = 'medium' ) {

    // Get the list of files
    $files = get_post_meta( get_the_ID(), $file_list_meta_key, 1 );

    echo '<div class="file-list-wrap">';
    // Loop through them and output an image
    foreach ( (array) $files as $attachment_id => $attachment_url ) {
        echo '<div class="file-list-image" style="display: inline-block;">';
        echo wp_get_attachment_image( $attachment_id, $img_size );
        echo '</div>';
    }
    echo '</div>';
}












/**
 * Gets a number of posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'sponsors',
        'numberposts' => 100,
    ) );

    $posts = get_posts( $args );

    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    }

    return $post_options;
}

/**
 * Gets 100 posts for your_post_type and displays them as options
 * @return array An array of options that matches the CMB2 options array
 */
function cmb2_get_sponsors_list() {
    return cmb2_get_post_options( array( 'post_type' => 'sponsors', 'numberposts' => 100 ) );
}





?>