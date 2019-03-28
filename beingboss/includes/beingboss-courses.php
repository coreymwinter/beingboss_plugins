<?php


add_action( 'cmb2_admin_init', 'cmb2_bblessons_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bblessons_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bblessons_';

	/**
	 * Initiate the metabox
	 */
	$bblessons = new_cmb2_box( array(
		'id'            => 'bblessons_metabox',
		'title'         => __( 'Being Boss - Materials', 'cmb2' ),
		'object_types'  => array( 'sfwd-lessons', 'sfwd-courses' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bblessonsfiles = $bblessons->add_field( array(
    		'id'          => $prefix . 'files',
    		'type'        => 'group',
    		'description' => __( 'Resources/files for this lesson or one-page course', 'cmb2' ),
    		'repeatable'  => true, // use false if you want non-repeatable group
    		'options'     => array(
        		'group_title'   => __( 'Resource Files', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
        		'add_button'    => __( 'Add Another File', 'cmb2' ),
        		'remove_button' => __( 'Remove File', 'cmb2' ),
        		'sortable'      => true, // beta
        		// 'closed'     => true, // true to have the groups closed by default
    		),
	) );

	// Id's for group's fields only need to be unique for the group. Prefix is not needed.
	$bblessons->add_group_field( $bblessonsfiles, array(
    		'name' => 'Resource file label',
    		'id'   => $prefix . 'resource_label',
    		'type' => 'text',
	) );

	$bblessons->add_group_field( $bblessonsfiles, array(
    		'name'    => 'Resource file upload',
    		'desc'    => 'Upload a file.',
    		'id'      => $prefix . 'resource_file',
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

	$bblessons->add_group_field( $bblessonsfiles, array(
    		'name' => 'Resource file link',
    		'desc'    => 'Alternatively, enter a link to the file download address',
    		'id'   => $prefix . 'resource_link',
    		'type' => 'text',
	) );


}




add_action( 'cmb2_admin_init', 'cmb2_bbcoursedetails_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbcoursedetails_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bbcoursedetails_';

    /**
     * Initiate the metabox
     */
    $bbcoursedetails = new_cmb2_box( array(
        'id'            => 'bbcoursedetails_metabox',
        'title'         => __( 'Being Boss - Course Details', 'cmb2' ),
        'object_types'  => array( 'sfwd-courses' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Course Header Background Image',
            'desc'    => 'Upload an image or enter an URL.',
            'id'      => $prefix . 'header_background',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
            ),
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Course Header Logo',
            'desc'    => 'Upload an image or enter an URL.',
            'id'      => $prefix . 'header_logo',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
            ),
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Course Header Text',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'header_text',
            'type'    => 'text',
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Logged Out Banner Ad',
            'desc'    => 'Upload an image or enter an URL.',
            'id'      => $prefix . 'banner_ad',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
            ),
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Logged Out Testimonial',
            'desc'    => 'Testimonial quote',
            'default' => '',
            'id'      => $prefix . 'testimonial_quote',
            'type'    => 'text',
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Logged Out Testimonial Author',
            'desc'    => 'Testimonial author',
            'default' => '',
            'id'      => $prefix . 'testimonial_author',
            'type'    => 'text',
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Course Author Byline',
            'desc'    => 'ex: a course by Being Boss',
            'default' => 'a course by Being Boss',
            'id'      => $prefix . 'author',
            'type'    => 'text',
    ) );

    $bbcoursedetails->add_field( array(
            'name'    => 'Course List Icon',
            'desc'    => 'Upload an icon',
            'id'      => $prefix . 'icon',
            'type'    => 'file',
            // Optional:
            'options' => array(
                'url' => false, // Hide the text input for the url
            ),
            'text'    => array(
                'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
            ),
    ) );

    $bbcoursedetails->add_field( array(
            'name' => 'Course Has Bonuses',
            'id'   => $prefix . 'bonus_check',
            'type' => 'checkbox',
    ) );

    $bbcoursedetails->add_field( array(
            'name'       => __( 'Associated Product', 'cmb2' ),
            'desc'       => __( 'Select a product to associate with this course', 'cmb2' ),
            'id'         => $prefix . 'product_select',
            'type'       => 'select',
            'show_option_none' => true,
            'options_cb' => 'cmb2_get_course_products_list',
    ) );

     $bbcoursedetails->add_field( array(
            'name'    => 'Enroll Now Custom Link',
            'desc'    => 'if this course has a full sales page (i.e. CEO Day Kit,) use this to insert a link into the Enroll Now buttons',
            'id'      => $prefix . 'enroll_now_link',
            'type'    => 'text',
    ) );

    $bbcoursedetails->add_field( array(
            'name'       => __( 'Associated Forum', 'cmb2' ),
            'desc'       => __( 'Select this forum associated with this course', 'cmb2' ),
            'id'         => $prefix . 'forum_select',
            'type'       => 'select',
            'show_option_none' => true,
            'options_cb' => 'cmb2_get_lesson_forum_list',
    ) );

}






/**
 * Gets a number of optin posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_product_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'product',
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
function cmb2_get_course_products_list() {
    return cmb2_get_product_post_options( array( 'post_type' => 'product', 'numberposts' => 100 ) );
}






/**
 * LearnDash - Add custom content to the single Course template output after the Course Status section
 *
 * @param string $output current content to display. The filter function should append to this and return
 * @param string $course_status 'not_started', 'in_progress' or 'complete'.
 * @param int $course_id ID of the Course being viewed. 
 * @param int $user_id ID of the User viewing the Course. 
 * 
 * @return string The $output variable containing any new content.
 *
 * @since LeansDash 2.3
 */
//add_filter('ld_after_course_status_template_container', 'add_content_after_course_status', 10, 4 );
//function add_content_after_course_status( $output = '', $course_status = 'not_started', $course_id = 0, $user_id = 0 ) {
    

  //  if ( $course_status == 'not_started' ) {
  //      $output .= '<p>Today is a good day to start learning.</p>';
  //  } 
    
   // else if ( $course_status == 'in_progress' ) {
  //      $output .= '<p>Continue the Course and continue learning.</p>';
   // }
    
   // else if ( $course_status == 'complete' ) {
   //     $output .= '<p>Thank You for completing this Course.</p>';
   // }
        
    //return $output;
//}





add_action( 'cmb2_admin_init', 'cmb2_bbgroupforum_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbgroupforum_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bbgroupforum_';

    /**
     * Initiate the metabox
     */
    $bbgroupforum = new_cmb2_box( array(
        'id'            => 'bbgroupforum_metabox',
        'title'         => __( 'Being Boss - Group Forum Details', 'cmb2' ),
        'object_types'  => array( 'forum' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );

    $bbgroupforum->add_field( array(
            'name'    => 'Course Header Background Image',
            'desc'    => 'Upload an image or enter an URL.',
            'id'      => $prefix . 'header_background',
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


}









add_action( 'cmb2_admin_init', 'cmb2_bblessondetails_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bblessondetails_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bblessondetails_';

    /**
     * Initiate the metabox
     */
    $bblessondetails = new_cmb2_box( array(
        'id'            => 'bblessondetails_metabox',
        'title'         => __( 'Being Boss - Lesson Details', 'cmb2' ),
        'object_types'  => array( 'sfwd-lessons' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );

    $bblessondetails->add_field( array(
            'name'       => __( 'Associated Forum', 'cmb2' ),
            'desc'       => __( 'Select this forum associated with this lesson', 'cmb2' ),
            'id'         => $prefix . 'forum_select',
            'type'       => 'select',
            'show_option_none' => true,
            'options_cb' => 'cmb2_get_lesson_forum_list',
    ) );
}



/**
 * Gets a number of optin posts and displays them as options
 * @param  array $query_args Optional. Overrides defaults.
 * @return array             An array of options that matches the CMB2 options array
 */
function cmb2_get_lesson_forum_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'forum',
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
function cmb2_get_lesson_forum_list() {
    return cmb2_get_lesson_forum_post_options( array( 'post_type' => 'forum', 'numberposts' => 100 ) );
}





?>