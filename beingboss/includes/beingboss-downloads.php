<?php

add_action( 'cmb2_admin_init', 'cmb2_download_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_download_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbdownload_';

	/**
	 * Initiate the metabox
	 */
	$bbdownload = new_cmb2_box( array(
		'id'            => 'bbdownload_metabox',
		'title'         => __( 'Download Item Details', 'cmb2' ),
		'object_types'  => array( 'dlm_download' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbdownload->add_field( array(
    		'name'    => 'Link Label',
    		'desc'    => '',
    		'default' => 'Download Now',
    		'id'      => $prefix . 'link_label',
    		'type'    => 'text',
	) );

    $bbdownload->add_field( array(
            'name'    => 'Material Byline',
            'desc'    => '',
            'default' => 'a worksheet by Being Boss',
            'id'      => $prefix . 'byline',
            'type'    => 'text',
    ) );

	$bbdownload->add_field( array(
    		'name'    => 'ThriveLeads Opt-In',
    		'desc'    => 'ID only (Do NOT paste entire shortcode)',
    		'default' => '',
    		'id'      => $prefix . 'thrive_id',
    		'type'    => 'text',
	) );

}