<?php
/*
Plugin Name: Being Boss - BeingBoss.club
Plugin URI:  https://www.beingboss.club
Description: Custom PHP Functions for Being Boss
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

}






?>